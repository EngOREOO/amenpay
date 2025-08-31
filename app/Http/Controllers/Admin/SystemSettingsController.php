<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SystemSettingsController extends Controller
{
    public function index()
    {
        return view('admin.system.settings');
    }

    public function general()
    {
        return view('admin.system.general');
    }

    public function security()
    {
        return view('admin.system.security');
    }

    public function notifications()
    {
        return view('admin.system.notifications');
    }

    public function integrations()
    {
        return view('admin.system.integrations');
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'currency' => 'required|string|max:10',
            'language' => 'required|string|max:10',
            'date_format' => 'required|string',
            'maintenance_mode' => 'boolean',
            'debug_mode' => 'boolean',
            'auto_backup' => 'boolean',
            'email_notifications' => 'boolean',
            'cache_driver' => 'required|string',
            'session_driver' => 'required|string',
            'queue_driver' => 'required|string',
            'log_level' => 'required|string',
            'api_rate_limit' => 'required|integer|min:100|max:10000',
            'api_timeout' => 'required|integer|min:10|max:300',
            'api_versioning' => 'boolean',
            'api_documentation' => 'boolean',
        ]);

        // Store settings in cache/database (you can implement your own storage method)
        $settings = $request->all();
        Cache::put('system.general.settings', $settings, 86400); // Cache for 24 hours

        return response()->json([
            'success' => true,
            'message' => 'General settings updated successfully!',
            'data' => $settings
        ]);
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'min_password_length' => 'required|integer|min:8|max:32',
            'password_expiry_days' => 'required|integer|min:0|max:365',
            'session_timeout_minutes' => 'required|integer|min:5|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration_minutes' => 'required|integer|min:5|max:1440',
            'two_factor_auth' => 'required|string',
            'require_uppercase' => 'boolean',
            'require_lowercase' => 'boolean',
            'require_numbers' => 'boolean',
            'require_special_chars' => 'boolean',
            'prevent_password_reuse' => 'boolean',
            'force_password_change' => 'boolean',
            'ip_whitelist' => 'nullable|string',
            'ip_blacklist' => 'nullable|string',
            'allowed_user_agents' => 'nullable|string',
            'blocked_user_agents' => 'nullable|string',
            'enable_ip_control' => 'boolean',
            'enable_user_agent_filtering' => 'boolean',
            'enable_geographic_restrictions' => 'boolean',
            'rate_limiting' => 'required|integer|min:10|max:1000',
            'csrf_token_lifetime' => 'required|integer|min:5|max:1440',
            'jwt_token_lifetime' => 'required|integer|min:1|max:168',
            'refresh_token_lifetime' => 'required|integer|min:1|max:365',
            'enable_rate_limiting' => 'boolean',
            'enable_csrf_protection' => 'boolean',
            'enable_xss_protection' => 'boolean',
            'enable_sql_injection_protection' => 'boolean',
            'enable_clickjacking_protection' => 'boolean',
            'enable_hsts' => 'boolean',
        ]);

        // Store security settings
        $settings = $request->all();
        Cache::put('system.security.settings', $settings, 86400);

        return response()->json([
            'success' => true,
            'message' => 'Security settings updated successfully!',
            'data' => $settings
        ]);
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'transaction_alerts' => 'boolean',
            'security_alerts' => 'boolean',
            'kyc_updates' => 'boolean',
            'system_maintenance' => 'boolean',
            'mobile_push' => 'boolean',
            'browser_notifications' => 'boolean',
            'critical_alerts_sms' => 'boolean',
            'otp_codes_sms' => 'boolean',
            'email_frequency' => 'required|string',
            'push_frequency' => 'required|string',
            'quiet_hours_start' => 'required|date_format:H:i',
            'quiet_hours_end' => 'required|date_format:H:i',
        ]);

        // Store notification settings
        $settings = $request->all();
        Cache::put('system.notifications.settings', $settings, 86400);

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully!',
            'data' => $settings
        ]);
    }

    public function updateIntegrations(Request $request)
    {
        $request->validate([
            'stripe_enabled' => 'boolean',
            'stripe_publishable_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'paypal_enabled' => 'boolean',
            'bank_transfer_enabled' => 'boolean',
            'twilio_enabled' => 'boolean',
            'twilio_account_sid' => 'nullable|string',
            'twilio_auth_token' => 'nullable|string',
            'sendgrid_enabled' => 'boolean',
            'sendgrid_api_key' => 'nullable|string',
            'sendgrid_from_email' => 'nullable|email',
            'google_analytics_enabled' => 'boolean',
            'mixpanel_enabled' => 'boolean',
            'webhook_url' => 'nullable|url',
            'webhook_secret' => 'nullable|string',
            'webhook_retries' => 'boolean',
        ]);

        // Store integration settings
        $settings = $request->all();
        Cache::put('system.integrations.settings', $settings, 86400);

        return response()->json([
            'success' => true,
            'message' => 'Integration settings updated successfully!',
            'data' => $settings
        ]);
    }

    public function updateOverview(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'currency' => 'required|string|max:10',
            'language' => 'required|string|max:10',
            'date_format' => 'required|string',
        ]);

        // Store overview settings
        $settings = $request->all();
        Cache::put('system.overview.settings', $settings, 86400);

        return response()->json([
            'success' => true,
            'message' => 'Overview settings updated successfully!',
            'data' => $settings
        ]);
    }
}
