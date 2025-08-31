<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $key = $this->resolveRequestSignature($request, $type);
        $maxAttempts = $this->getMaxAttempts($type);
        $decayMinutes = $this->getDecayMinutes($type);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_id' => $request->user()?->id,
                'endpoint' => $request->path(),
                'type' => $type,
                'retry_after' => $retryAfter
            ]);

            return $this->buildRateLimitResponse($retryAfter, $type);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addRateLimitHeaders($response, $key, $maxAttempts);
    }

    /**
     * Resolve the request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request, string $type): string
    {
        $identifier = $this->getIdentifier($request, $type);
        
        return sha1($identifier . '|' . $request->ip() . '|' . $request->userAgent());
    }

    /**
     * Get the identifier for rate limiting based on type.
     */
    protected function getIdentifier(Request $request, string $type): string
    {
        switch ($type) {
            case 'auth':
                return 'auth:' . $request->ip();
            
            case 'otp':
                return 'otp:' . $request->input('phone', $request->ip());
            
            case 'payment':
                return 'payment:' . ($request->user()?->id ?? $request->ip());
            
            case 'api':
                return 'api:' . ($request->user()?->id ?? $request->ip());
            
            case 'sms':
                return 'sms:' . $request->input('phone', $request->ip());
            
            case 'file_upload':
                return 'upload:' . ($request->user()?->id ?? $request->ip());
            
            default:
                return 'default:' . ($request->user()?->id ?? $request->ip());
        }
    }

    /**
     * Get maximum attempts based on type.
     */
    protected function getMaxAttempts(string $type): int
    {
        return match ($type) {
            'auth' => config('rate_limit.auth.max_attempts', 5),
            'otp' => config('rate_limit.otp.max_attempts', 3),
            'payment' => config('rate_limit.payment.max_attempts', 10),
            'api' => config('rate_limit.api.max_attempts', 60),
            'sms' => config('rate_limit.sms.max_attempts', 5),
            'file_upload' => config('rate_limit.file_upload.max_attempts', 20),
            default => config('rate_limit.default.max_attempts', 30),
        };
    }

    /**
     * Get decay minutes based on type.
     */
    protected function getDecayMinutes(string $type): int
    {
        return match ($type) {
            'auth' => config('rate_limit.auth.decay_minutes', 15),
            'otp' => config('rate_limit.otp.decay_minutes', 5),
            'payment' => config('rate_limit.payment.decay_minutes', 1),
            'api' => config('rate_limit.api.decay_minutes', 1),
            'sms' => config('rate_limit.sms.decay_minutes', 1),
            'file_upload' => config('rate_limit.file_upload.decay_minutes', 1),
            default => config('rate_limit.default.decay_minutes', 1),
        };
    }

    /**
     * Build rate limit exceeded response.
     */
    protected function buildRateLimitResponse(int $retryAfter, string $type): JsonResponse
    {
        $message = $this->getRateLimitMessage($type);
        
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => 'rate_limit_exceeded',
            'retry_after' => $retryAfter,
            'retry_after_formatted' => $this->formatRetryAfter($retryAfter)
        ], 429);
    }

    /**
     * Get rate limit message based on type.
     */
    protected function getRateLimitMessage(string $type): string
    {
        $messages = [
            'auth' => [
                'ar' => 'تم تجاوز الحد الأقصى لمحاولات تسجيل الدخول. يرجى المحاولة بعد فترة.',
                'en' => 'Too many login attempts. Please try again later.'
            ],
            'otp' => [
                'ar' => 'تم تجاوز الحد الأقصى لطلبات رمز التحقق. يرجى المحاولة بعد 5 دقائق.',
                'en' => 'Too many OTP requests. Please try again in 5 minutes.'
            ],
            'payment' => [
                'ar' => 'تم تجاوز الحد الأقصى لطلبات الدفع. يرجى المحاولة بعد دقيقة.',
                'en' => 'Too many payment requests. Please try again in 1 minute.'
            ],
            'api' => [
                'ar' => 'تم تجاوز الحد الأقصى لطلبات API. يرجى المحاولة بعد دقيقة.',
                'en' => 'Too many API requests. Please try again in 1 minute.'
            ],
            'sms' => [
                'ar' => 'تم تجاوز الحد الأقصى لرسائل SMS. يرجى المحاولة بعد دقيقة.',
                'en' => 'Too many SMS requests. Please try again in 1 minute.'
            ],
            'file_upload' => [
                'ar' => 'تم تجاوز الحد الأقصى لرفع الملفات. يرجى المحاولة بعد دقيقة.',
                'en' => 'Too many file upload requests. Please try again in 1 minute.'
            ],
            'default' => [
                'ar' => 'تم تجاوز الحد الأقصى للطلبات. يرجى المحاولة بعد دقيقة.',
                'en' => 'Too many requests. Please try again in 1 minute.'
            ]
        ];

        $language = app()->getLocale();
        return $messages[$type][$language] ?? $messages[$type]['en'];
    }

    /**
     * Format retry after time for user-friendly display.
     */
    protected function formatRetryAfter(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . ' seconds';
        }
        
        $minutes = ceil($seconds / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }

    /**
     * Add rate limit headers to response.
     */
    protected function addRateLimitHeaders(Response $response, string $key, int $maxAttempts): Response
    {
        $remaining = RateLimiter::remaining($key, $maxAttempts);
        $resetTime = RateLimiter::availableAt($key);

        return $response->header('X-RateLimit-Limit', $maxAttempts)
                       ->header('X-RateLimit-Remaining', $remaining)
                       ->header('X-RateLimit-Reset', $resetTime);
    }

    /**
     * Check if request should be exempt from rate limiting.
     */
    protected function shouldExempt(Request $request): bool
    {
        // Exempt admin users
        if ($request->user() && $request->user()->hasRole('admin')) {
            return true;
        }

        // Exempt whitelisted IPs
        $whitelistedIps = config('rate_limit.whitelisted_ips', []);
        if (in_array($request->ip(), $whitelistedIps)) {
            return true;
        }

        // Exempt health check endpoints
        if ($request->is('health') || $request->is('ping')) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining attempts for a key.
     */
    public static function getRemainingAttempts(string $key): int
    {
        $maxAttempts = config('rate_limit.default.max_attempts', 30);
        return RateLimiter::remaining($key, $maxAttempts);
    }

    /**
     * Clear rate limit for a key.
     */
    public static function clearRateLimit(string $key): void
    {
        RateLimiter::clear($key);
    }

    /**
     * Get rate limit info for a key.
     */
    public static function getRateLimitInfo(string $key): array
    {
        $maxAttempts = config('rate_limit.default.max_attempts', 30);
        $remaining = RateLimiter::remaining($key, $maxAttempts);
        $resetTime = RateLimiter::availableAt($key);

        return [
            'remaining' => $remaining,
            'max_attempts' => $maxAttempts,
            'reset_time' => $resetTime,
            'reset_time_formatted' => now()->addSeconds($resetTime - time())->diffForHumans()
        ];
    }
}
