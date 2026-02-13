<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BingoCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'grid_size',
        'cells',
        'is_template',
        'thumbnail_url',
        'category',
        'source',
        'user_id',
    ];

    protected $casts = [
        'cells' => 'array',
        'is_template' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (BingoCard $card) {
            if (empty($card->uuid)) {
                $card->uuid = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

