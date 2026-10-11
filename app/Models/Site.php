<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Site extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'url',
        'api_key_hash',
        'api_key_prefix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public static function hashKey(string $plain): string
    {
        return hash('sha256', $plain);
    }

    public static function makePlainKey(): string
    {
        return 'site_'.bin2hex(random_bytes(24));
    }

    public function assignKey(?string $plain = null): string
    {
        $plain ??= static::makePlainKey();
        $this->api_key_hash = static::hashKey($plain);
        $this->api_key_prefix = Str::substr($plain, 0, 12);
        return $plain;
    }
}
