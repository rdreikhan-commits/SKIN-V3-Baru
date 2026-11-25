<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HimaOrmawaFiturSetting extends Model
{
    use HasFactory;

    protected $table = 'hima_ormawa_fitur_settings';

    protected $fillable = [
        'user_id',
        'fitur_key',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
