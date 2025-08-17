<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'printer_name',
        'printer_port',
        'printer_config',
        'is_default',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'printer_config' => 'array'
    ];

    /**
     * Get the default printer setting
     */
    public static function getDefault()
    {
        return static::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get active printer settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }

    /**
     * Set this setting as default
     */
    public function setAsDefault()
    {
        // Remove default from other settings
        static::where('is_default', true)->update(['is_default' => false]);

        // Set this as default
        $this->update(['is_default' => true]);
    }

    /**
     * Get printer configuration as array
     */
    public function getPrinterConfigAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    /**
     * Set printer configuration as JSON
     */
    public function setPrinterConfigAttribute($value)
    {
        $this->attributes['printer_config'] = json_encode($value);
    }
}
