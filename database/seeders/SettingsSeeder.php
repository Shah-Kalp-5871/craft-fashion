<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\Admin\SettingService;

class SettingsSeeder extends Seeder
{
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->settingService->initializeSettings();

        $this->command->info('Settings initialized successfully.');
    }
}
