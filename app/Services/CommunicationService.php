<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CommunicationService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('communication');
    }

    /**
     * Send OTP via SMS
     */
    public function sendOtpSms(string $phone, string $otp, string $type = 'login'): array
    {
        try {
            if (!$this->config['sms']['enabled']) {
                throw new \Exception('SMS service is disabled');
            }

            $message = $this->getOtpMessage($otp, $type);
            $result = $this->sendSms($phone, $message);

            if ($result['success']) {
                // Store OTP in cache for verification
                $this->storeOtpInCache($phone, $otp, $type);
                
                Log::info('OTP SMS sent successfully', [
                    'phone' => $phone,
                    'type' => $type,
                    'provider' => $result['provider']
                ]);

                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'provider' => $result['provider'],
                    'expires_in' => $this->config['otp']['expiry_minutes'] * 60
                ];
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Failed to send OTP SMS', [
                'phone' => $phone,
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS using configured provider
     */
    public function sendSms(string $phone, string $message, array $options = []): array
    {
        $provider = $this->config['sms']['default_provider'] ?? 'twilio';

        switch ($provider) {
            case 'twilio':
                return $this->sendSmsViaTwilio($phone, $message, $options);
            case 'nexmo':
                return $this->sendSmsViaNexmo($phone, $message, $options);
            case 'saudi_telecom':
                return $this->sendSmsViaSaudiTelecom($phone, $message, $options);
            default:
                throw new \Exception("Unsupported SMS provider: {$provider}");
        }
    }

    /**
     * Send SMS via Twilio
     */
    protected function sendSmsViaTwilio(string $phone, string $message, array $options = []): array
    {
        $accountSid = $this->config['sms']['providers']['twilio']['account_sid'];
        $authToken = $this->config['sms']['providers']['twilio']['auth_token'];
        $fromNumber = $this->config['sms']['providers']['twilio']['from_number'];

        $response = Http::withBasicAuth($accountSid, $authToken)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'From' => $fromNumber,
                'To' => $phone,
                'Body' => $message
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'provider' => 'twilio',
                'message_sid' => $data['sid'] ?? null,
                'status' => $data['status'] ?? null
            ];
        }

        throw new \Exception('Twilio API request failed: ' . $response->body());
    }

    /**
     * Send SMS via Nexmo (Vonage)
     */
    protected function sendSmsViaNexmo(string $phone, string $message, array $options = []): array
    {
        $apiKey = $this->config['sms']['providers']['nexmo']['api_key'];
        $apiSecret = $this->config['sms']['providers']['nexmo']['api_secret'];
        $fromNumber = $this->config['sms']['providers']['nexmo']['from_number'];

        $response = Http::post('https://rest.nexmo.com/sms/json', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'from' => $fromNumber,
            'to' => $phone,
            'text' => $message,
            'type' => 'text'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['messages'][0]['status']) && $data['messages'][0]['status'] === '0') {
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'provider' => 'nexmo',
                    'message_id' => $data['messages'][0]['message-id'] ?? null,
                    'status' => 'sent'
                ];
            }
        }

        throw new \Exception('Nexmo API request failed: ' . $response->body());
    }

    /**
     * Send SMS via Saudi Telecom
     */
    protected function sendSmsViaSaudiTelecom(string $phone, string $message, array $options = []): array
    {
        $apiKey = $this->config['sms']['providers']['saudi_telecom']['api_key'];
        $senderId = $this->config['sms']['providers']['saudi_telecom']['sender_id'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json'
        ])->post($this->config['sms']['providers']['saudi_telecom']['api_url'], [
            'sender_id' => $senderId,
            'recipients' => [$phone],
            'message' => $message,
            'language' => 'ar'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'provider' => 'saudi_telecom',
                'message_id' => $data['message_id'] ?? null,
                'status' => 'sent'
            ];
        }

        throw new \Exception('Saudi Telecom API request failed: ' . $response->body());
    }

    /**
     * Send email notification
     */
    public function sendEmail(User $user, string $subject, string $template, array $data = []): array
    {
        try {
            if (!$this->config['email']['enabled']) {
                throw new \Exception('Email service is disabled');
            }

            if (!$user->email) {
                throw new \Exception('User does not have an email address');
            }

            $emailData = array_merge([
                'user' => $user,
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'current_year' => now()->year
            ], $data);

            // Send email using Laravel's mail system
            Mail::send("emails.{$template}", $emailData, function ($message) use ($user, $subject) {
                $message->to($user->email, $user->name)
                        ->subject($subject);
            });

            Log::info('Email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'template' => $template,
                'subject' => $subject
            ]);

            return [
                'success' => true,
                'message' => 'Email sent successfully',
                'email' => $user->email
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'template' => $template,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send transaction notification email
     */
    public function sendTransactionEmail(User $user, array $transactionData): array
    {
        $subject = $this->getTransactionEmailSubject($transactionData['type']);
        $template = 'transaction';
        
        return $this->sendEmail($user, $subject, $template, [
            'transaction' => $transactionData,
            'wallet' => $user->wallet
        ]);
    }

    /**
     * Send welcome email
     */
    public function sendWelcomeEmail(User $user): array
    {
        $subject = $user->language === 'ar' ? 'مرحباً بك في P-Finance' : 'Welcome to P-Finance';
        $template = 'welcome';
        
        return $this->sendEmail($user, $subject, $template);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(User $user, string $resetToken): array
    {
        $subject = $user->language === 'ar' ? 'إعادة تعيين كلمة المرور' : 'Password Reset';
        $template = 'password-reset';
        
        return $this->sendEmail($user, $subject, $template, [
            'reset_token' => $resetToken,
            'reset_url' => route('password.reset', ['token' => $resetToken, 'email' => $user->email])
        ]);
    }

    /**
     * Send push notification
     */
    public function sendPushNotification(User $user, string $title, string $message, array $data = []): array
    {
        try {
            if (!$this->config['push']['enabled']) {
                throw new \Exception('Push notification service is disabled');
            }

            $devices = $user->pushNotificationDevices()->where('is_active', true)->get();
            
            if ($devices->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No active devices found for user'
                ];
            }

            $results = [];
            foreach ($devices as $device) {
                $result = $this->sendToDevice($device, $title, $message, $data);
                $results[] = $result;
            }

            $successCount = collect($results)->where('success', true)->count();
            $totalCount = count($results);

            Log::info('Push notifications sent', [
                'user_id' => $user->id,
                'successful' => $successCount,
                'total' => $totalCount
            ]);

            return [
                'success' => $successCount > 0,
                'message' => "Sent to {$successCount} out of {$totalCount} devices",
                'results' => $results
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send push notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send push notification',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send notification to specific device
     */
    protected function sendToDevice($device, string $title, string $message, array $data = []): array
    {
        $provider = $device->platform === 'ios' ? 'apns' : 'fcm';
        
        switch ($provider) {
            case 'fcm':
                return $this->sendToFCM($device, $title, $message, $data);
            case 'apns':
                return $this->sendToAPNS($device, $title, $message, $data);
            default:
                throw new \Exception("Unsupported push provider: {$provider}");
        }
    }

    /**
     * Send to Firebase Cloud Messaging (Android)
     */
    protected function sendToFCM($device, string $title, string $message, array $data = []): array
    {
        $serverKey = $this->config['push']['providers']['fcm']['server_key'];
        
        $payload = [
            'to' => $device->device_token,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'sound' => 'default'
            ],
            'data' => $data,
            'priority' => 'high'
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json'
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        if ($response->successful()) {
            $result = $response->json();
            return [
                'success' => true,
                'message' => 'Push notification sent to FCM',
                'device_id' => $device->id,
                'fcm_message_id' => $result['message_id'] ?? null
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to send to FCM',
            'device_id' => $device->id,
            'error' => $response->body()
        ];
    }

    /**
     * Send to Apple Push Notification Service (iOS)
     */
    protected function sendToAPNS($device, string $title, string $message, array $data = []): array
    {
        $certificatePath = $this->config['push']['providers']['apns']['certificate_path'];
        $passphrase = $this->config['push']['providers']['apns']['passphrase'];
        $environment = $this->config['push']['providers']['apns']['environment'];
        
        $url = $environment === 'production' 
            ? 'ssl://gateway.push.apple.com:2195'
            : 'ssl://gateway.sandbox.push.apple.com:2195';

        $payload = [
            'aps' => [
                'alert' => [
                    'title' => $title,
                    'body' => $message
                ],
                'sound' => 'default',
                'badge' => 1
            ],
            'data' => $data
        ];

        $payload = json_encode($payload);
        $deviceToken = $device->device_token;

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certificatePath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        $fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            return [
                'success' => false,
                'message' => 'Failed to connect to APNS',
                'device_id' => $device->id,
                'error' => "Failed to connect: {$err} {$errstr}"
            ];
        }

        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Push notification sent to APNS',
                'device_id' => $device->id
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to send to APNS',
            'device_id' => $device->id
        ];
    }

    /**
     * Store OTP in cache for verification
     */
    protected function storeOtpInCache(string $phone, string $otp, string $type): void
    {
        $key = "otp:{$phone}:{$type}";
        $expiry = $this->config['otp']['expiry_minutes'] * 60;
        
        Cache::put($key, $otp, $expiry);
    }

    /**
     * Get OTP message based on type and language
     */
    protected function getOtpMessage(string $otp, string $type): string
    {
        $messages = [
            'login' => [
                'ar' => "رمز التحقق الخاص بك هو: {$otp}. صالح لمدة 5 دقائق.",
                'en' => "Your verification code is: {$otp}. Valid for 5 minutes."
            ],
            'registration' => [
                'ar' => "مرحباً! رمز التحقق الخاص بك هو: {$otp}. صالح لمدة 5 دقائق.",
                'en' => "Welcome! Your verification code is: {$otp}. Valid for 5 minutes."
            ],
            'reset' => [
                'ar' => "رمز إعادة تعيين كلمة المرور: {$otp}. صالح لمدة 5 دقائق.",
                'en' => "Password reset code: {$otp}. Valid for 5 minutes."
            ]
        ];

        $language = app()->getLocale();
        return $messages[$type][$language] ?? $messages[$type]['en'];
    }

    /**
     * Get transaction email subject
     */
    protected function getTransactionEmailSubject(string $type): string
    {
        $subjects = [
            'payment' => [
                'ar' => 'تم استلام الدفع بنجاح',
                'en' => 'Payment Received Successfully'
            ],
            'transfer' => [
                'ar' => 'تم إرسال التحويل بنجاح',
                'en' => 'Transfer Sent Successfully'
            ],
            'withdrawal' => [
                'ar' => 'تم إرسال طلب السحب',
                'en' => 'Withdrawal Request Sent'
            ]
        ];

        $language = app()->getLocale();
        return $subjects[$type][$language] ?? $subjects[$type]['en'];
    }

    /**
     * Send bulk SMS to multiple users
     */
    public function sendBulkSms(array $phoneNumbers, string $message): array
    {
        $results = [];
        $successCount = 0;

        foreach ($phoneNumbers as $phone) {
            $result = $this->sendSms($phone, $message);
            $results[] = $result;
            
            if ($result['success']) {
                $successCount++;
            }
        }

        return [
            'success' => $successCount > 0,
            'message' => "Sent to {$successCount} out of " . count($phoneNumbers) . " numbers",
            'results' => $results,
            'successful' => $successCount,
            'total' => count($phoneNumbers)
        ];
    }

    /**
     * Send bulk email to multiple users
     */
    public function sendBulkEmail(array $users, string $subject, string $template, array $data = []): array
    {
        $results = [];
        $successCount = 0;

        foreach ($users as $user) {
            $result = $this->sendEmail($user, $subject, $template, $data);
            $results[] = $result;
            
            if ($result['success']) {
                $successCount++;
            }
        }

        return [
            'success' => $successCount > 0,
            'message' => "Sent to {$successCount} out of " . count($users) . " users",
            'results' => $results,
            'successful' => $successCount,
            'total' => count($users)
        ];
    }
}
