<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuVisibility extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model
     */
    protected $table = 'menu_visibility';

    protected $fillable = [
        'user_id',
        'menu_key',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Toggle visibility menu
     */
    public function toggle()
    {
        $this->is_hidden = !$this->is_hidden;
        $this->save();
        return $this;
    }
}
