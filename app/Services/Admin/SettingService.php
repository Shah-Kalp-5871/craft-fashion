<?php

namespace App\Services\Admin;

use App\Helpers\SettingsHelper;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SettingService
{
    /**
     * Get all settings, optionally filtered by group
     */
    public function getSettings(?string $group = null): array
    {
        $query = Setting::query()
            ->orderBy('group')
            ->orderBy('sort_order');

        if ($group) {
            $query->where('group', $group);
        }

        $settings = Setting::query()
            ->when($group, fn($q) => $q->where('group', $group))
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();
        // Group settings by group
        $groupedSettings = [];

        foreach ($settings as $setting) {
            $groupedSettings[$setting->group][] = [
                'key' => $setting->key,
                'value' => $this->getSettingValue($setting),
                'label' => $setting->label,
                'description' => $setting->description,
                'type' => $setting->type,
                'options' => $setting->options,
                'is_encrypted' => $setting->is_encrypted,
                'is_public' => $setting->is_public,
                'sort_order' => $setting->sort_order
            ];
        }

        return $groupedSettings;
    }

    /**
     * Get setting value with proper type casting
     */
    private function getSettingValue(Setting $setting): mixed
    {
        if (!$setting) {
            return null;
        }

        $value = $setting->value;

        if ($setting->is_encrypted && $value) {
            $value = decrypt($value);
        }

        switch ($setting->type) {
            case 'boolean':
            case 'checkbox':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);

            case 'number':
            case 'integer':
                return is_numeric($value) ? (int) $value : 0;

            case 'decimal':
            case 'float':
                return is_numeric($value) ? (float) $value : 0.0;

            case 'array':
            case 'json':
                return $value ? json_decode($value, true) : [];

            default:
                return $value ?? '';
        }
    }


    /**
     * Bulk update settings
     */
    public function bulkUpdate(array $settings): int
    {
        $updated = 0;

        foreach ($settings as $settingData) {
            try {
                $setting = Setting::where('key', $settingData['key'])->first();

                if ($setting) {
                    $value = $settingData['value'] ?? null;

                    // Handle different types
                    if ($setting->type === 'boolean' || $setting->type === 'checkbox') {
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
                    } elseif ($setting->type === 'array' || $setting->type === 'json') {
                        $value = is_array($value) ? json_encode($value) : $value;
                    }

                    // Encrypt if needed
                    if ($setting->is_encrypted && $value) {
                        $value = encrypt($value);
                    }

                    $setting->value = $value;
                    $setting->save();

                    SettingsHelper::clearCache($setting->key);
                    $updated++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to update setting {$settingData['key']}: " . $e->getMessage());
            }
        }
        
        // Clear public settings cache as well since bulk update might affect public settings
        SettingsHelper::clearCache('settings.public');


        return $updated;
    }

    /**
     * Reset settings to defaults
     */
    public function resetToDefaults(): void
    {
        DB::beginTransaction();

        try {
            // Get default settings configuration
            $defaultSettings = $this->getDefaultSettings();

            foreach ($defaultSettings as $group => $settings) {
                foreach ($settings as $setting) {
                    $existing = Setting::where('key', $setting['key'])->first();

                    if ($existing) {
                        $existing->value = $setting['default'];
                        $existing->save();
                    } else {
                        Setting::create([
                            'group' => $group,
                            'key' => $setting['key'],
                            'value' => $setting['default'],
                            'type' => $setting['type'],
                            'options' => $setting['options'] ?? null,
                            'label' => $setting['label'],
                            'description' => $setting['description'] ?? null,
                            'is_encrypted' => $setting['is_encrypted'] ?? false,
                            'is_public' => $setting['is_public'] ?? true,
                            'sort_order' => $setting['sort_order'] ?? 0
                        ]);
                    }
                }
            }

            DB::commit();
            
            SettingsHelper::clearCache();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reset settings to defaults: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get default settings configuration
     */
    private function getDefaultSettings(): array
    {
        return [
            'general' => [
                [
                    'key' => 'store_name',
                    'label' => 'Store Name',
                    'value' => 'Craft Fashion',
                    'type' => 'text',
                    'default' => 'Craft Fashion',
                    'description' => 'The name of your online store',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 10
                ],
                [
                    'key' => 'store_email',
                    'label' => 'Store Email',
                    'value' => 'info@craftfashion.com',
                    'type' => 'text',
                    'default' => 'info@craftfashion.com',
                    'description' => 'Primary contact email for the store',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 20
                ],
                [
                    'key' => 'store_phone',
                    'label' => 'Phone Number',
                    'value' => '+91 9588181384',
                    'type' => 'text',
                    'default' => '+91 9588181384',
                    'description' => 'Store contact phone number',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 30
                ],
                [
                    'key' => 'currency',
                    'label' => 'Currency',
                    'value' => 'INR',
                    'type' => 'select',
                    'default' => 'INR',
                    'options' => [
                        ['value' => 'INR', 'label' => 'Indian Rupee (₹)'],
                        ['value' => 'USD', 'label' => 'US Dollar ($)'],
                        ['value' => 'EUR', 'label' => 'Euro (€)'],
                        ['value' => 'GBP', 'label' => 'British Pound (£)'],
                        ['value' => 'CAD', 'label' => 'Canadian Dollar (C$)']
                    ],
                    'description' => 'Default currency for the store',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 40
                ],
                [
                    'key' => 'store_address',
                    'label' => 'Store Address',
                    'value' => '123 Fashion Street, 135001',
                    'type' => 'textarea',
                    'default' => '123 Fashion Street, 135001',
                    'description' => 'Physical address of the store',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 50
                ],
                [
                    'key' => 'store_pincode',
                    'label' => 'Pincode / Zip Code',
                    'value' => '135001',
                    'type' => 'text',
                    'default' => '135001',
                    'description' => 'Store location pincode',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 60
                ]
            ],
            'seo' => [
                [
                    'key' => 'meta_title',
                    'label' => 'Meta Title',
                    'value' => 'Craft Fashion | Premium Women\'s & Kids\' Boutique',
                    'type' => 'text',
                    'default' => 'Craft Fashion | Premium Women\'s & Kids\' Boutique',
                    'description' => 'Default meta title for pages',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 10
                ],
                [
                    'key' => 'meta_description',
                    'label' => 'Meta Description',
                    'value' => 'Craft Fashion offers exquisite kurtis, tops, kurti-bottom sets, and boutique garments for women, girls, and kids in Yamuna Nagar.',
                    'type' => 'textarea',
                    'default' => 'Craft Fashion offers exquisite kurtis, tops, kurti-bottom sets, and boutique garments for women, girls, and kids in Yamuna Nagar.',
                    'description' => 'Default meta description for pages',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 20
                ],
                [
                    'key' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                    'value' => 'kurti, women\'s clothing, kids fashion, boutique garments, traditional wear, Craft Fashion Yamuna Nagar',
                    'type' => 'text',
                    'default' => 'kurti, women\'s clothing, kids fashion, boutique garments, traditional wear, Craft Fashion Yamuna Nagar',
                    'description' => 'Default meta keywords for SEO',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 30
                ],
                [
                    'key' => 'google_analytics',
                    'label' => 'Google Analytics Code',
                    'value' => '',
                    'type' => 'textarea',
                    'default' => '',
                    'description' => 'Google Analytics tracking code',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 40
                ]
            ],
            'payment' => [
                [
                    'key' => 'razorpay_enabled',
                    'label' => 'Enable Razorpay Payments',
                    'value' => '1',
                    'type' => 'checkbox',
                    'default' => '1',
                    'description' => 'Enable or disable Razorpay (UPI, Cards, Netbanking)',
                    'is_encrypted' => false,
                    'is_public' => false,
                    'sort_order' => 10
                ],
                [
                    'key' => 'razorpay_key_id',
                    'label' => 'Razorpay Key ID',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'Razorpay API Key ID',
                    'is_encrypted' => true,
                    'is_public' => false,
                    'sort_order' => 20
                ],
                [
                    'key' => 'razorpay_key_secret',
                    'label' => 'Razorpay Key Secret',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'Razorpay API Key Secret',
                    'is_encrypted' => true,
                    'is_public' => false,
                    'sort_order' => 30
                ],
                [
                    'key' => 'cod_enabled',
                    'label' => 'Enable Cash on Delivery',
                    'value' => '1',
                    'type' => 'checkbox',
                    'default' => '1',
                    'description' => 'Enable or disable Cash on Delivery payment method',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 40
                ]
            ],
            'shipping' => [
                [
                    'key' => 'default_shipping_rate',
                    'label' => 'Default Shipping Rate',
                    'value' => '99.00',
                    'type' => 'decimal',
                    'default' => '99.00',
                    'description' => 'Default shipping rate for orders',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 10
                ],
                [
                    'key' => 'tax_rate',
                    'label' => 'Tax Rate GST (%)',
                    'value' => '3.0',
                    'type' => 'decimal',
                    'default' => '3.0',
                    'description' => 'Default tax rate (GST) percentage',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 20
                ],
                [
                    'key' => 'free_shipping_min',
                    'label' => 'Free Shipping Minimum',
                    'value' => '999.00',
                    'type' => 'decimal',
                    'default' => '999.00',
                    'description' => 'Minimum order amount for free shipping',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 30
                ]
            ],
            'social' => [
                [
                    'key' => 'social_facebook',
                    'label' => 'Facebook URL',
                    'value' => 'https://facebook.com/craftfashion',
                    'type' => 'text',
                    'default' => 'https://facebook.com/craftfashion',
                    'description' => 'Facebook page URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 10
                ],
                [
                    'key' => 'social_instagram',
                    'label' => 'Instagram URL',
                    'value' => 'https://instagram.com/craftfashion',
                    'type' => 'text',
                    'default' => 'https://instagram.com/craftfashion',
                    'description' => 'Instagram profile URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 20
                ],
                [
                    'key' => 'social_twitter',
                    'label' => 'Twitter/X URL',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'Twitter profile URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 30
                ],
                [
                    'key' => 'social_linkedin',
                    'label' => 'LinkedIn URL',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'LinkedIn company page URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 40
                ]
            ],
            'appearance' => [
                [
                    'key' => 'theme_color',
                    'label' => 'Theme Color',
                    'value' => '#4f46e5',
                    'type' => 'color',
                    'default' => '#4f46e5',
                    'description' => 'Primary theme color',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 10
                ],
                [
                    'key' => 'logo_url',
                    'label' => 'Logo URL',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'Store logo URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 20
                ],
                [
                    'key' => 'favicon_url',
                    'label' => 'Favicon URL',
                    'value' => '',
                    'type' => 'text',
                    'default' => '',
                    'description' => 'Favicon URL',
                    'is_encrypted' => false,
                    'is_public' => true,
                    'sort_order' => 30
                ]
            ]
        ];
    }

    /**
     * Initialize database with default settings
     */
    public function initializeSettings(): void
    {
        $defaultSettings = $this->getDefaultSettings();
        $existingKeys = Setting::pluck('key')->toArray();

        foreach ($defaultSettings as $group => $settings) {
            foreach ($settings as $setting) {
                if (!in_array($setting['key'], $existingKeys)) {
                    Setting::create([
                        'group' => $group,
                        'key' => $setting['key'],
                        'value' => $setting['default'],
                        'type' => $setting['type'],
                        'options' => $setting['options'] ?? null,
                        'label' => $setting['label'],
                        'description' => $setting['description'] ?? null,
                        'is_encrypted' => $setting['is_encrypted'] ?? false,
                        'is_public' => $setting['is_public'] ?? true,
                        'sort_order' => $setting['sort_order'] ?? 0
                    ]);
                }
            }
        }
    }

    /**
     * Get a specific setting value by key
     */
    public function getValue(string $key, $default = null): mixed
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $this->getSettingValue($setting);
    }

}
