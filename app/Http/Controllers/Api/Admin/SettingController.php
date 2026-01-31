<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Admin\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display all settings grouped by category
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $group = $request->get('group');
            $settings = $this->settingService->getSettings($group);

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Settings retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Settings index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings'
            ], 500);
        }
    }

    /**
     * Get specific setting by key
     */
    public function show($key): JsonResponse
    {
        try {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $setting,
                'message' => 'Setting retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Setting show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve setting'
            ], 500);
        }
    }

    /**
     * Update multiple settings at once
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
                'settings.*.key' => 'required|string',
                'settings.*.value' => 'nullable'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            $updated = $this->settingService->bulkUpdate($request->settings);

            return response()->json([
                'success' => true,
                'data' => [
                    'updated_count' => $updated
                ],
                'message' => 'Settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Settings bulk update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings'
            ], 500);
        }
    }

    /**
     * Update a specific setting
     */
    public function update(Request $request, $key): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'value' => 'nullable'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }

            $setting->value = $request->value;
            $setting->save();

            return response()->json([
                'success' => true,
                'data' => $setting,
                'message' => 'Setting updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Setting update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting'
            ], 500);
        }
    }

    /**
     * Get settings for dropdown/select
     */
    public function dropdown(): JsonResponse
    {
        try {
            $settings = Setting::where('is_public', true)
                ->orderBy('group')
                ->orderBy('sort_order')
                ->get(['key', 'label', 'value', 'group', 'type']);

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Settings dropdown retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Settings dropdown error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings dropdown'
            ], 500);
        }
    }

    /**
     * Get settings grouped by category
     */
    public function groups(): JsonResponse
    {
        try {
            $groups = Setting::select('group')
                ->distinct()
                ->orderBy('group')
                ->pluck('group');

            $settingsByGroup = [];

            foreach ($groups as $group) {
                $settingsByGroup[$group] = Setting::where('group', $group)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(function ($setting) {
                        return [
                            'key' => $setting->key,
                            'label' => $setting->label,
                            'value' => $setting->value,
                            'type' => $setting->type,
                            'options' => $setting->options,
                            'description' => $setting->description,
                            'sort_order' => $setting->sort_order
                        ];
                    });
            }

            return response()->json([
                'success' => true,
                'data' => $settingsByGroup,
                'message' => 'Settings groups retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Settings groups error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings groups'
            ], 500);
        }
    }

    /**
     * Reset settings to defaults
     */
    public function reset(): JsonResponse
    {
        try {
            $this->settingService->resetToDefaults();

            return response()->json([
                'success' => true,
                'message' => 'Settings reset to defaults successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Settings reset error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings'
            ], 500);
        }
    }

    /**
     * Get specific group settings
     */
    public function getByGroup($group): JsonResponse
    {
        try {
            $settings = Setting::where('group', $group)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'label' => $setting->label,
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'options' => $setting->options,
                        'description' => $setting->description,
                        'sort_order' => $setting->sort_order
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $settings,
                'message' => 'Group settings retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Group settings error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve group settings'
            ], 500);
        }
    }
}
