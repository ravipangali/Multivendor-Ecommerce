<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $section = $this->route()->parameter('section') ?? $this->input('section');

        switch ($section) {
            case 'general':
                return $this->generalRules();
            case 'email':
                return $this->emailRules();
            case 'payment':
                return $this->paymentRules();
            case 'shipping':
                return $this->shippingRules();
            case 'tax':
                return $this->taxRules();
            default:
                return [];
        }
    }

    /**
     * General settings validation rules
     */
    private function generalRules(): array
    {
        return [
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif|max:1024',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:255',
            'site_footer' => 'nullable|string|max:500',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'site_address' => 'nullable|string|max:500',
            'site_facebook' => 'nullable|url|max:255',
            'site_twitter' => 'nullable|url|max:255',
            'site_instagram' => 'nullable|url|max:255',
            'site_linkedin' => 'nullable|url|max:255',
            'site_youtube' => 'nullable|url|max:255',
            'site_currency_symbol' => 'required|string|max:10',
            'site_currency_code' => 'required|string|size:3|regex:/^[A-Z]{3}$/',
        ];
    }

    /**
     * Email settings validation rules
     */
    private function emailRules(): array
    {
        return [
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|between:1,65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|in:TLS,SSL,STARTTLS',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ];
    }

    /**
     * Payment settings validation rules
     */
    private function paymentRules(): array
    {
        return [
            'minimum_withdrawal_amount' => 'required|numeric|min:0|max:999999.99',
            'gateway_transaction_fee' => 'required|numeric|min:0|max:100',
            'esewa_merchant_id' => 'nullable|string|max:255',
            'esewa_secret_key' => 'nullable|string|max:255',
            'khalti_public_key' => 'nullable|string|max:255',
            'khalti_secret_key' => 'nullable|string|max:255',
            'withdrawal_policy' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Shipping settings validation rules
     */
    private function shippingRules(): array
    {
        return [
            'shipping_enable_free' => 'nullable|boolean',
            'shipping_free_min_amount' => 'required_if:shipping_enable_free,1|nullable|numeric|min:0|max:999999.99',
            'shipping_flat_rate_enable' => 'nullable|boolean',
            'shipping_flat_rate_cost' => 'required_if:shipping_flat_rate_enable,1|nullable|numeric|min:0|max:999999.99',
            'shipping_enable_local_pickup' => 'nullable|boolean',
            'shipping_local_pickup_cost' => 'required_if:shipping_enable_local_pickup,1|nullable|numeric|min:0|max:999999.99',
            'shipping_allow_seller_config' => 'nullable|boolean',
            'shipping_seller_free_enable' => 'nullable|boolean',
            'shipping_seller_flat_rate_enable' => 'nullable|boolean',
            'shipping_seller_zone_based_enable' => 'nullable|boolean',
            'shipping_policy_info' => 'nullable|string|max:2000',
            'shipping_weight_rate' => 'nullable|numeric|min:0|max:999.99',
            'shipping_min_weight' => 'nullable|numeric|min:0|max:999.99',
            'shipping_max_weight' => 'nullable|numeric|min:0.01|max:9999.99|gt:shipping_min_weight',
            'shipping_zone_based_enable' => 'nullable|boolean',
            'shipping_local_rate' => 'nullable|numeric|min:0|max:999999.99',
            'shipping_regional_rate' => 'nullable|numeric|min:0|max:999999.99',
            'shipping_remote_rate' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    /**
     * Tax settings validation rules
     */
    private function taxRules(): array
    {
        return [
            'tax_enable' => 'nullable|boolean',
            'tax_rate' => 'required_if:tax_enable,1|nullable|numeric|min:0|max:100',
            'tax_shipping' => 'nullable|boolean',
            'tax_inclusive_pricing' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'site_currency_code.regex' => 'Currency code must be a valid 3-letter ISO code (e.g., NPR, USD, EUR).',
            'site_phone.regex' => 'Phone number format is invalid. Please use a valid phone number format.',
            'mail_port.between' => 'Mail port must be between 1 and 65535.',
            'gateway_transaction_fee.max' => 'Transaction fee cannot exceed 100%.',
            'shipping_max_weight.gt' => 'Maximum weight must be greater than minimum weight.',
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'site_name' => 'site name',
            'site_email' => 'site email',
            'site_phone' => 'site phone',
            'site_address' => 'site address',
            'site_currency_symbol' => 'currency symbol',
            'site_currency_code' => 'currency code',
            'mail_host' => 'SMTP host',
            'mail_port' => 'SMTP port',
            'mail_username' => 'SMTP username',
            'mail_password' => 'SMTP password',
            'mail_encryption' => 'SMTP encryption',
            'mail_from_address' => 'from email address',
            'mail_from_name' => 'from name',
            'minimum_withdrawal_amount' => 'minimum withdrawal amount',
            'gateway_transaction_fee' => 'transaction fee',
            'esewa_merchant_id' => 'eSewa merchant ID',
            'esewa_secret_key' => 'eSewa secret key',
            'khalti_public_key' => 'Khalti public key',
            'khalti_secret_key' => 'Khalti secret key',
            'shipping_free_min_amount' => 'free shipping minimum amount',
            'shipping_flat_rate_cost' => 'flat rate shipping cost',
            'shipping_local_pickup_cost' => 'local pickup cost',
            'shipping_weight_rate' => 'weight-based shipping rate',
            'shipping_min_weight' => 'minimum weight',
            'shipping_max_weight' => 'maximum weight',
            'shipping_local_rate' => 'local shipping rate',
            'shipping_regional_rate' => 'regional shipping rate',
            'shipping_remote_rate' => 'remote shipping rate',
            'tax_rate' => 'tax rate',
        ];
    }
}
