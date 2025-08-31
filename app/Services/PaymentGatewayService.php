<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    protected $config;
    protected $gateway;

    public function __construct()
    {
        $this->config = config('payment');
    }

    /**
     * Process payment through the appropriate gateway
     */
    public function processPayment(PaymentTransaction $transaction, string $gatewayType = 'mada'): array
    {
        try {
            $this->gateway = PaymentGateway::where('type', $gatewayType)->first();
            
            if (!$this->gateway) {
                throw new \Exception("Payment gateway {$gatewayType} not found");
            }

            switch ($gatewayType) {
                case 'stc_pay':
                    return $this->processStcPay($transaction);
                case 'mada':
                    return $this->processMada($transaction);
                case 'apple_pay':
                    return $this->processApplePay($transaction);
                case 'bank_transfer':
                    return $this->processBankTransfer($transaction);
                default:
                    throw new \Exception("Unsupported payment gateway: {$gatewayType}");
            }
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'transaction_id' => $transaction->id,
                'gateway' => $gatewayType,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Payment processing failed',
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ];
        }
    }

    /**
     * Process STC Pay payment
     */
    protected function processStcPay(PaymentTransaction $transaction): array
    {
        $payload = [
            'merchant_id' => $this->gateway->merchant_id,
            'amount' => $transaction->amount,
            'currency' => 'SAR',
            'order_id' => $transaction->reference_id,
            'customer_phone' => $transaction->user->phone,
            'callback_url' => route('payment.webhook.stc_pay'),
            'return_url' => route('payment.return.stc_pay'),
            'timestamp' => now()->timestamp,
        ];

        $payload['signature'] = $this->generateSignature($payload, $this->gateway->secret_key);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->gateway->api_key,
            'Content-Type' => 'application/json'
        ])->post($this->gateway->api_url . '/payments', $payload);

        if ($response->successful()) {
            $data = $response->json();
            
            $transaction->update([
                'gateway_response' => $data,
                'gateway_transaction_id' => $data['transaction_id'] ?? null,
                'status' => 'pending'
            ]);

            return [
                'success' => true,
                'message' => 'Payment initiated successfully',
                'payment_url' => $data['payment_url'] ?? null,
                'transaction_id' => $transaction->id,
                'gateway_transaction_id' => $data['transaction_id'] ?? null
            ];
        }

        throw new \Exception('STC Pay API request failed: ' . $response->body());
    }

    /**
     * Process Mada payment
     */
    protected function processMada(PaymentTransaction $transaction): array
    {
        $payload = [
            'merchant_id' => $this->gateway->merchant_id,
            'amount' => $transaction->amount * 100, // Convert to halalah
            'currency' => 'SAR',
            'order_id' => $transaction->reference_id,
            'customer_email' => $transaction->user->email,
            'customer_phone' => $transaction->user->phone,
            'callback_url' => route('payment.webhook.mada'),
            'return_url' => route('payment.return.mada'),
            'language' => $transaction->user->language ?? 'ar',
        ];

        $payload['signature'] = $this->generateMadaSignature($payload, $this->gateway->secret_key);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->gateway->api_key,
            'Content-Type' => 'application/json'
        ])->post($this->gateway->api_url . '/payments', $payload);

        if ($response->successful()) {
            $data = $response->json();
            
            $transaction->update([
                'gateway_response' => $data,
                'gateway_transaction_id' => $data['transaction_id'] ?? null,
                'status' => 'pending'
            ]);

            return [
                'success' => true,
                'message' => 'Mada payment initiated successfully',
                'payment_url' => $data['payment_url'] ?? null,
                'transaction_id' => $transaction->id,
                'gateway_transaction_id' => $data['transaction_id'] ?? null
            ];
        }

        throw new \Exception('Mada API request failed: ' . $response->body());
    }

    /**
     * Process Apple Pay payment
     */
    protected function processApplePay(PaymentTransaction $transaction): array
    {
        // Apple Pay requires special handling with payment tokens
        $payload = [
            'merchant_id' => $this->gateway->merchant_id,
            'amount' => $transaction->amount,
            'currency' => 'SAR',
            'order_id' => $transaction->reference_id,
            'payment_token' => request()->input('payment_token'),
            'callback_url' => route('payment.webhook.apple_pay'),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->gateway->api_key,
            'Content-Type' => 'application/json'
        ])->post($this->gateway->api_url . '/apple-pay', $payload);

        if ($response->successful()) {
            $data = $response->json();
            
            $transaction->update([
                'gateway_response' => $data,
                'gateway_transaction_id' => $data['transaction_id'] ?? null,
                'status' => $data['status'] ?? 'pending'
            ]);

            return [
                'success' => true,
                'message' => 'Apple Pay processed successfully',
                'transaction_id' => $transaction->id,
                'gateway_transaction_id' => $data['transaction_id'] ?? null,
                'status' => $data['status'] ?? 'pending'
            ];
        }

        throw new \Exception('Apple Pay API request failed: ' . $response->body());
    }

    /**
     * Process bank transfer
     */
    protected function processBankTransfer(PaymentTransaction $transaction): array
    {
        // Generate bank transfer details
        $transferDetails = $this->generateBankTransferDetails($transaction);
        
        $transaction->update([
            'gateway_response' => $transferDetails,
            'status' => 'pending',
            'metadata' => array_merge($transaction->metadata ?? [], [
                'bank_transfer_details' => $transferDetails
            ])
        ]);

        return [
            'success' => true,
            'message' => 'Bank transfer details generated',
            'transaction_id' => $transaction->id,
            'transfer_details' => $transferDetails,
            'status' => 'pending'
        ];
    }

    /**
     * Generate QR code for payment
     */
    public function generateQRCode(PaymentTransaction $transaction): array
    {
        $qrData = [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'currency' => 'SAR',
            'merchant_id' => $this->gateway->merchant_id,
            'timestamp' => now()->timestamp
        ];

        $qrData['signature'] = $this->generateSignature($qrData, $this->gateway->secret_key);

        // Generate QR code using a library like SimpleSoftwareIO/simple-qrcode
        $qrCode = \QrCode::format('png')
            ->size(300)
            ->margin(10)
            ->generate(json_encode($qrData));

        return [
            'success' => true,
            'qr_code' => base64_encode($qrCode),
            'qr_data' => $qrData,
            'transaction_id' => $transaction->id
        ];
    }

    /**
     * Verify payment status
     */
    public function verifyPaymentStatus(string $gatewayTransactionId, string $gatewayType): array
    {
        $gateway = PaymentGateway::where('type', $gatewayType)->first();
        
        if (!$gateway) {
            throw new \Exception("Gateway {$gatewayType} not found");
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $gateway->api_key
        ])->get($gateway->api_url . "/transactions/{$gatewayTransactionId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Payment verification failed: ' . $response->body());
    }

    /**
     * Generate signature for payment gateway
     */
    protected function generateSignature(array $data, string $secretKey): string
    {
        ksort($data);
        $signatureString = '';
        
        foreach ($data as $key => $value) {
            if ($key !== 'signature') {
                $signatureString .= $key . '=' . $value . '&';
            }
        }
        
        $signatureString = rtrim($signatureString, '&');
        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    /**
     * Generate Mada-specific signature
     */
    protected function generateMadaSignature(array $data, string $secretKey): string
    {
        // Mada uses a different signature algorithm
        $signatureString = implode('|', [
            $data['merchant_id'],
            $data['order_id'],
            $data['amount'],
            $data['currency'],
            $data['timestamp'] ?? now()->timestamp
        ]);
        
        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    /**
     * Generate bank transfer details
     */
    protected function generateBankTransferDetails(PaymentTransaction $transaction): array
    {
        return [
            'bank_name' => 'البنك السعودي الفرنسي',
            'account_number' => 'SA0380000000608010167519',
            'iban' => 'SA0380000000608010167519',
            'swift_code' => 'BSFRSARI',
            'beneficiary_name' => 'P-Finance',
            'reference' => $transaction->reference_id,
            'amount' => $transaction->amount,
            'currency' => 'SAR',
            'expires_at' => now()->addDays(7)->toISOString()
        ];
    }

    /**
     * Process payment webhook
     */
    public function processWebhook(array $webhookData, string $gatewayType): array
    {
        $gateway = PaymentGateway::where('type', $gatewayType)->first();
        
        if (!$gateway) {
            throw new \Exception("Gateway {$gatewayType} not found");
        }

        // Verify webhook signature
        if (!$this->verifyWebhookSignature($webhookData, $gateway)) {
            throw new \Exception('Invalid webhook signature');
        }

        $transaction = PaymentTransaction::where('gateway_transaction_id', $webhookData['transaction_id'])->first();
        
        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        // Update transaction status
        $transaction->update([
            'status' => $webhookData['status'],
            'gateway_response' => array_merge($transaction->gateway_response ?? [], $webhookData),
            'processed_at' => now()
        ]);

        // Process based on status
        if ($webhookData['status'] === 'completed') {
            $this->processSuccessfulPayment($transaction);
        } elseif (in_array($webhookData['status'], ['failed', 'cancelled'])) {
            $this->processFailedPayment($transaction);
        }

        return [
            'success' => true,
            'transaction_id' => $transaction->id,
            'status' => $webhookData['status']
        ];
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(array $data, PaymentGateway $gateway): bool
    {
        $signature = $data['signature'] ?? null;
        unset($data['signature']);
        
        $expectedSignature = $this->generateSignature($data, $gateway->secret_key);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process successful payment
     */
    protected function processSuccessfulPayment(PaymentTransaction $transaction): void
    {
        // Update wallet balance
        $wallet = $transaction->wallet;
        $wallet->increment('balance', $transaction->amount);
        
        // Create transaction record
        $transaction->wallet->transactions()->create([
            'type' => 'deposit',
            'amount' => $transaction->amount,
            'currency' => 'SAR',
            'description' => 'Payment received via ' . $transaction->gateway_type,
            'status' => 'completed',
            'reference_id' => $transaction->reference_id,
            'metadata' => [
                'payment_transaction_id' => $transaction->id,
                'gateway_type' => $transaction->gateway_type
            ]
        ]);

        // Send notification
        event(new \App\Events\PaymentSuccessful($transaction));
    }

    /**
     * Process failed payment
     */
    protected function processFailedPayment(PaymentTransaction $transaction): void
    {
        // Send notification
        event(new \App\Events\PaymentFailed($transaction));
    }
}
