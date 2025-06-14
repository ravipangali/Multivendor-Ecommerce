<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasSetting;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportException;
// In a real application, you would have a Setting model
// use App\Models\Setting;

class SaasSettingsController extends Controller
{
    /**
     * Display settings dashboard.
     */
    public function index()
    {
        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_index', compact('settings'));
    }

    /**
     * Display and update general settings.
     */
    public function general(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'site_name' => 'required|string|max:255',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif|max:1024',
                'site_description' => 'nullable|string',
                'site_keywords' => 'nullable|string',
                'site_footer' => 'nullable|string',
                'site_email' => 'required|email',
                'site_phone' => 'nullable|string|max:20',
                'site_address' => 'nullable|string',
                'site_facebook' => 'nullable|url',
                'site_twitter' => 'nullable|url',
                'site_instagram' => 'nullable|url',
                'site_linkedin' => 'nullable|url',
                'site_youtube' => 'nullable|url',
                'site_currency_symbol' => 'required|string|max:10',
                'site_currency_code' => 'required|string|max:10',
            ]);

            $settings = SaasSetting::first() ?? new SaasSetting();

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                if ($settings->site_logo) {
                    Storage::disk('public')->delete($settings->site_logo);
                }
                $settings->site_logo = $request->file('site_logo')->store('settings', 'public');
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                if ($settings->site_favicon) {
                    Storage::disk('public')->delete($settings->site_favicon);
                }
                $settings->site_favicon = $request->file('site_favicon')->store('settings', 'public');
            }

            // Update other fields
            $settings->fill($request->except(['site_logo', 'site_favicon']));
            $settings->save();

            // Clear cache
            SettingsService::clearCache();

            toast('General settings updated successfully', 'success');
            return redirect()->route('admin.settings.general');
        }

        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_general', compact('settings'));
    }

    /**
     * Display and update email settings.
     */
    public function email(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'mail_host' => 'required|string',
                'mail_port' => 'required|numeric',
                'mail_username' => 'required|string',
                'mail_password' => 'required|string',
                'mail_encryption' => 'required|in:TLS,SSL,STARTTLS',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);

            $settings = SaasSetting::first() ?? new SaasSetting();
            $settings->fill($request->all());
            $settings->save();

            // Clear cache
            SettingsService::clearCache();

            toast('Email settings updated successfully', 'success');
            return redirect()->route('admin.settings.email');
        }

        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_email', compact('settings'));
    }

    /**
     * Display and update payment settings.
     */
    public function payment(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'minimum_withdrawal_amount' => 'required|numeric|min:0',
                'gateway_transaction_fee' => 'required|numeric|min:0|max:100',
                'esewa_merchant_id' => 'nullable|string',
                'esewa_secret_key' => 'nullable|string',
                'khalti_public_key' => 'nullable|string',
                'khalti_secret_key' => 'nullable|string',
                'withdrawal_policy' => 'nullable|string',
            ]);

            $settings = SaasSetting::first() ?? new SaasSetting();
            $settings->fill($request->all());
            $settings->save();

            // Clear cache
            SettingsService::clearCache();

            toast('Payment settings updated successfully', 'success');
            return redirect()->route('admin.settings.payment');
        }

        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_payment', compact('settings'));
    }

    /**
     * Display and update shipping settings.
     */
    public function shipping(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'shipping_enable_free' => 'nullable|boolean',
                'shipping_free_min_amount' => 'required_if:shipping_enable_free,1|nullable|numeric|min:0',
                'shipping_flat_rate_enable' => 'nullable|boolean',
                'shipping_flat_rate_cost' => 'required_if:shipping_flat_rate_enable,1|nullable|numeric|min:0',
                'shipping_enable_local_pickup' => 'nullable|boolean',
                'shipping_local_pickup_cost' => 'required_if:shipping_enable_local_pickup,1|nullable|numeric|min:0',
                'shipping_allow_seller_config' => 'nullable|boolean',
                'shipping_seller_free_enable' => 'nullable|boolean',
                'shipping_seller_flat_rate_enable' => 'nullable|boolean',
                'shipping_seller_zone_based_enable' => 'nullable|boolean',
                'shipping_policy_info' => 'nullable|string',
                'shipping_weight_rate' => 'nullable|numeric|min:0',
                'shipping_min_weight' => 'nullable|numeric|min:0',
                'shipping_max_weight' => 'nullable|numeric|min:0',
                'shipping_zone_based_enable' => 'nullable|boolean',
                'shipping_local_rate' => 'nullable|numeric|min:0',
                'shipping_regional_rate' => 'nullable|numeric|min:0',
                'shipping_remote_rate' => 'nullable|numeric|min:0',
            ]);

            $settings = SaasSetting::first() ?? new SaasSetting();
            $settings->fill($request->all());
            $settings->save();

            // Clear cache
            SettingsService::clearCache();

            toast('Shipping settings updated successfully', 'success');
            return redirect()->route('admin.settings.shipping');
        }

        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_shipping', compact('settings'));
    }

    /**
     * Display and update tax settings.
     */
    public function tax(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'tax_enable' => 'nullable|boolean',
                'tax_rate' => 'required_if:tax_enable,1|nullable|numeric|min:0|max:100',
                'tax_shipping' => 'nullable|boolean',
                'tax_inclusive_pricing' => 'nullable|boolean',
            ], [
                'tax_rate.required_if' => 'Tax rate is required when tax system is enabled.',
                'tax_rate.min' => 'Tax rate cannot be negative.',
                'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            ]);

            $settings = SaasSetting::first() ?? new SaasSetting();

            // Handle tax enable/disable
            $settings->tax_enable = $request->boolean('tax_enable', false);

            // Only update tax-related settings if tax is enabled
            if ($settings->tax_enable) {
                $settings->tax_rate = $request->tax_rate ?? 13.00;
                $settings->tax_shipping = $request->boolean('tax_shipping', false);
                $settings->tax_inclusive_pricing = $request->boolean('tax_inclusive_pricing', false);
            } else {
                // When tax is disabled, set reasonable defaults but don't null them out
                // This preserves settings for when tax is re-enabled
                if (!$settings->tax_rate) {
                    $settings->tax_rate = 13.00; // Default Nepal VAT rate
                }
                $settings->tax_shipping = false;
                $settings->tax_inclusive_pricing = false;
            }

            $settings->save();

            // Clear cache
            SettingsService::clearCache();

            $message = $settings->tax_enable ?
                'Tax settings updated successfully. Tax system is now enabled.' :
                'Tax settings updated successfully. Tax system is now disabled.';

            toast($message, 'success');
            return redirect()->route('admin.settings.tax');
        }

        $settings = SaasSetting::first() ?? new SaasSetting();
        return view('saas_admin.saas_settings.saas_tax', compact('settings'));
    }

    /**
     * Test SMTP email configuration
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $settings = SaasSetting::first();

            if (!$settings || !$settings->mail_host || !$settings->mail_port || !$settings->mail_username || !$settings->mail_password) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP settings are incomplete. Please configure all required fields first.'
                ]);
            }

            // Configure mail on the fly
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $settings->mail_host,
                'mail.mailers.smtp.port' => $settings->mail_port,
                'mail.mailers.smtp.username' => $settings->mail_username,
                'mail.mailers.smtp.password' => $settings->mail_password,
                'mail.mailers.smtp.encryption' => strtolower($settings->mail_encryption),
                'mail.from.address' => $settings->mail_from_address ?: 'noreply@example.com',
                'mail.from.name' => $settings->mail_from_name ?: 'Multi Tenant E-commerce',
            ]);

            // Create a custom mailable for testing
            $mailable = new \Illuminate\Mail\Mailable();
            $mailable->subject('SMTP Test Email - ' . ($settings->site_name ?: 'Multi Tenant E-commerce'));
            $mailable->html('
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                    <h2>SMTP Test Email</h2>
                    <p>This is a test email sent from your <strong>' . ($settings->site_name ?: 'Multi Tenant E-commerce') . '</strong> platform.</p>
                    <p>If you received this email, your SMTP settings are configured correctly.</p>
                    <hr>
                    <p><small>Sent at: ' . now()->format('Y-m-d H:i:s') . '</small></p>
                </div>
            ');

            Mail::to($request->email)->send($mailable);
            Log::info('SMTP test email sent successfully to ' . $request->email);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->email
            ]);
        } catch (TransportException $e) {
            Log::error('SMTP test failed (transport error): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage(),
                'error_type' => 'transport',
                'error_details' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error('SMTP test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        SettingsService::clearCache();

        toast('Settings cache cleared successfully', 'success');
        return redirect()->back();
    }

    /**
     * Export settings configuration
     */
    public function exportSettings()
    {
        $settings = SaasSetting::first();

        if (!$settings) {
            toast('No settings found to export', 'error');
            return redirect()->back();
        }

        // Remove sensitive information from export
        $exportData = $settings->toArray();
        unset($exportData['id'], $exportData['created_at'], $exportData['updated_at']);

        // Remove sensitive fields
        $sensitiveFields = ['mail_password', 'esewa_secret_key', 'khalti_secret_key'];
        foreach ($sensitiveFields as $field) {
            if (isset($exportData[$field])) {
                $exportData[$field] = '***HIDDEN***';
            }
        }

        $fileName = 'saas-settings-' . now()->format('Y-m-d-H-i-s') . '.json';

        return response()->json($exportData)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get settings status information
     */
    public function getSettingsStatus()
    {
        $settings = SaasSetting::first() ?? new SaasSetting();

        return [
            'general_complete' => !empty($settings->site_name) && !empty($settings->site_email),
            'email_complete' => !empty($settings->mail_host) && !empty($settings->mail_port) && !empty($settings->mail_username),
            'payment_configured' => !empty($settings->esewa_merchant_id) || !empty($settings->khalti_public_key),
            'tax_enabled' => $settings->tax_enable ?? false,
            'shipping_configured' => $settings->shipping_enable_free || $settings->shipping_flat_rate_enable || $settings->shipping_enable_local_pickup,
            'logo_uploaded' => !empty($settings->site_logo),
            'currency_set' => !empty($settings->site_currency_code),
        ];
    }
}
