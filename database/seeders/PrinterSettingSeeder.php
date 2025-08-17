<?php

namespace Database\Seeders;

use App\Models\PrinterSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrinterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default printer settings
        PrinterSetting::create([
            'name' => 'Default Printer',
            'printer_name' => 'Auto-detect',
            'printer_port' => null,
            'printer_config' => [
                'paper_size' => 'A4',
                'orientation' => 'portrait',
                'auto_print' => false
            ],
            'description' => 'Default printer setting for the system',
            'is_default' => true,
            'is_active' => true
        ]);

        PrinterSetting::create([
            'name' => 'Thermal Printer',
            'printer_name' => 'Thermal Printer 80mm',
            'printer_port' => 'USB',
            'printer_config' => [
                'paper_size' => '80mm',
                'orientation' => 'portrait',
                'auto_print' => true
            ],
            'description' => 'Thermal printer for receipt printing',
            'is_default' => false,
            'is_active' => true
        ]);

        PrinterSetting::create([
            'name' => 'A4 Printer',
            'printer_name' => 'HP LaserJet',
            'printer_port' => 'USB',
            'printer_config' => [
                'paper_size' => 'A4',
                'orientation' => 'portrait',
                'auto_print' => false
            ],
            'description' => 'A4 printer for invoice printing',
            'is_default' => false,
            'is_active' => true
        ]);
    }
}
