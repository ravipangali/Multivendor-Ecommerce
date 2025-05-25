<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasSetting;
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
     * Display and update general settings.
     */
    public function general(Request $request)
    {
        // If request is POST, update the settings
        if ($request->isMethod('post')) {
            $request->validate([
                'site_name' => 'required|string|max:255',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif|max:1024',
                'site_email' => 'required|email',
                'site_phone' => 'nullable|string|max:20',
                'site_address' => 'nullable|string',
                'site_description' => 'nullable|string',
                'site_keywords' => 'nullable|string',
                'footer_text' => 'nullable|string',
                'social_facebook' => 'nullable|url',
                'social_twitter' => 'nullable|url',
                'social_instagram' => 'nullable|url',
                'social_linkedin' => 'nullable|url',
                'currency_symbol' => 'required|string|max:10',
                'currency_code' => 'required|string|max:10',
            ]);

            // Save settings
            $settings = $request->except(['_token', 'site_logo', 'favicon']);

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                // Delete old logo if exists
                $oldLogo = SaasSetting::getValue('site_logo');
                if ($oldLogo) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $logoPath = $request->file('site_logo')->store('settings', 'public');
                $settings['site_logo'] = $logoPath;
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                $oldFavicon = SaasSetting::getValue('favicon');
                if ($oldFavicon) {
                    Storage::disk('public')->delete($oldFavicon);
                }

                $faviconPath = $request->file('favicon')->store('settings', 'public');
                $settings['favicon'] = $faviconPath;
            }

            // Save settings to database
            foreach ($settings as $key => $value) {
                SaasSetting::setValue($key, $value, 'general');
            }

            // Clear cache
            SaasSetting::clearAllCache();

            toast('General settings updated successfully', 'success');
            return redirect()->route('admin.settings.general');
        }

        // If request is GET, display the settings form
        $settings = SaasSetting::getByGroup('general');

        // Convert to key-value array format for easier access in view
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->key] = $setting->value;
        }

        // Set default values if not set
        $defaults = [
            'site_name' => 'Multi Tenant E-commerce',
            'site_email' => 'admin@example.com',
            'currency_symbol' => '$',
            'currency_code' => 'USD',
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($settingsArray[$key])) {
                $settingsArray[$key] = $value;
            }
        }

        return view('saas_admin.saas_settings.saas_general', compact('settingsArray'));
    }

    /**
     * Display and update email settings.
     */
    public function email(Request $request)
    {
        // If request is POST, update the settings
        if ($request->isMethod('post')) {
            $request->validate([
                'mail_driver' => 'required|string',
                'mail_host' => 'required|string',
                'mail_port' => 'required|numeric',
                'mail_username' => 'required|string',
                'mail_password' => 'required|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);

            // Save settings
            $settings = $request->except('_token');

            // Save settings to database
            foreach ($settings as $key => $value) {
                SaasSetting::setValue($key, $value, 'email');
            }

            // Clear cache
            SaasSetting::clearAllCache();

            toast('Email settings updated successfully', 'success');
            return redirect()->route('admin.settings.email');
        }

        // If request is GET, display the settings form
        $settings = SaasSetting::getByGroup('email');

        // Convert to array format for easier access in view
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->key] = $setting->value;
        }

        return view('saas_admin.saas_settings.saas_email', compact('settingsArray'));
    }

    /**
     * Display and update payment settings.
     */
    public function payment(Request $request)
    {
        // If request is POST, update the settings
        if ($request->isMethod('post')) {
            $request->validate([
                'payment_bank_enable' => 'nullable|boolean',
                'payment_bank_details' => 'required_if:payment_bank_enable,1|nullable|string',
                'payment_esewa_enable' => 'nullable|boolean',
                'payment_esewa_merchant_id' => 'required_if:payment_esewa_enable,1|nullable|string',
                'payment_esewa_secret_key' => 'required_if:payment_esewa_enable,1|nullable|string',
                'payment_khalti_enable' => 'nullable|boolean',
                'payment_khalti_public_key' => 'required_if:payment_khalti_enable,1|nullable|string',
                'payment_khalti_secret_key' => 'required_if:payment_khalti_enable,1|nullable|string',
                'payment_cod_enable' => 'nullable|boolean',
                'payment_gateway_transaction_fee' => 'nullable|numeric|min:0|max:100',
                'payment_min_withdrawal_amount' => 'nullable|numeric|min:0',
            ]);

            // Save settings
            $settings = $request->except('_token');

            // Save settings to database
            foreach ($settings as $key => $value) {
                SaasSetting::setValue($key, $value, 'payment');
            }

            // Clear cache
            SaasSetting::clearAllCache();

            toast('Payment settings updated successfully', 'success');
            return redirect()->route('admin.settings.payment');
        }

        // If request is GET, display the settings form
        $settings = SaasSetting::getByGroup('payment');

        // Convert to array format for easier access in view
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->key] = $setting->value;
        }

        return view('saas_admin.saas_settings.saas_payment', compact('settingsArray'));
    }

    /**
     * Display and update shipping settings.
     */
    public function shipping(Request $request)
    {
        // If request is POST, update the settings
        if ($request->isMethod('post')) {
            $request->validate([
                'shipping_enable_free' => 'nullable|boolean',
                'shipping_free_min_amount' => 'required_if:shipping_enable_free,1|nullable|numeric',
                'shipping_flat_rate_enable' => 'nullable|boolean',
                'shipping_flat_rate_cost' => 'required_if:shipping_flat_rate_enable,1|nullable|numeric',
                'shipping_enable_local_pickup' => 'nullable|boolean',
                'shipping_local_pickup_cost' => 'required_if:shipping_enable_local_pickup,1|nullable|numeric',
                'shipping_allow_seller_config' => 'nullable|boolean',
                'shipping_seller_free_enable' => 'nullable|boolean',
                'shipping_seller_flat_rate_enable' => 'nullable|boolean',
                'shipping_seller_zone_based_enable' => 'nullable|boolean',
                'shipping_policy_info' => 'nullable|string',
            ]);

            // Save settings
            $settings = $request->except('_token');

            // Save settings to database
            foreach ($settings as $key => $value) {
                SaasSetting::setValue($key, $value, 'shipping');
            }

            // Clear cache
            SaasSetting::clearAllCache();

            toast('Shipping settings updated successfully', 'success');
            return redirect()->route('admin.settings.shipping');
        }

        // If request is GET, display the settings form
        $settings = SaasSetting::getByGroup('shipping');

        // Convert to array format for easier access in view
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->key] = $setting->value;
        }

        return view('saas_admin.saas_settings.saas_shipping', compact('settingsArray'));
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
            // Get mail settings from database
            $mailDriver = SaasSetting::getValue('mail_driver', 'smtp');
            $mailHost = SaasSetting::getValue('mail_host');
            $mailPort = SaasSetting::getValue('mail_port');
            $mailUsername = SaasSetting::getValue('mail_username');
            $mailPassword = SaasSetting::getValue('mail_password');
            $mailEncryption = SaasSetting::getValue('mail_encryption');
            $mailFromAddress = SaasSetting::getValue('mail_from_address');
            $mailFromName = SaasSetting::getValue('mail_from_name');

            // Check for missing required settings
            if (empty($mailHost) || empty($mailPort) || empty($mailUsername) || empty($mailPassword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP settings are incomplete. Please configure all required fields first.'
                ]);
            }

            // Configure mail on the fly
            config([
                'mail.default' => $mailDriver,
                'mail.mailers.smtp.host' => $mailHost,
                'mail.mailers.smtp.port' => $mailPort,
                'mail.mailers.smtp.username' => $mailUsername,
                'mail.mailers.smtp.password' => $mailPassword,
                'mail.mailers.smtp.encryption' => $mailEncryption,
                'mail.from.address' => $mailFromAddress ?: 'noreply@example.com',
                'mail.from.name' => $mailFromName ?: 'Multi Tenant E-commerce',
            ]);

            // Create a custom mailable for testing
            $mailable = new \Illuminate\Mail\Mailable();
            $mailable->subject('SMTP Test Email');
            $mailable->html('<p>This is a test email sent from your Multi Tenant E-commerce platform.</p><p>If you received this email, your SMTP settings are configured correctly.</p>');

            // Send test email
            Mail::to($request->email)->send($mailable);

            // Log the successful test
            Log::info('SMTP test email sent successfully to ' . $request->email);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->email
            ]);
        } catch (TransportException $e) {
            // Connection errors
            Log::error('SMTP test failed (transport error): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage(),
                'error_type' => 'transport',
                'error_details' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            // Other errors
            Log::error('SMTP test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ]);
        }
    }
}
