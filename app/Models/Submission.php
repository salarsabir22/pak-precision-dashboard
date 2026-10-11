<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Submission extends Model
{
    public const TYPES = ['contact', 'sourcing', 'inquiry'];
    public const STATUSES = ['new', 'in_progress', 'done', 'spam'];

    protected $fillable = [
        'site_id',
        'type',
        'reference',
        'status',
        'name',
        'company',
        'email',
        'phone',
        'country',
        'subject',
        'message',
        'payload',
        'notes',
        'ip',
        'user_agent',
        'read_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'read_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(SubmissionAttachment::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at')->where('status', 'new');
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public static function makeReference(string $type): string
    {
        $prefix = match ($type) {
            'sourcing' => 'SRC',
            'inquiry' => 'RFQ',
            default => 'MSG',
        };

        return $prefix.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'sourcing' => 'Sourcing',
            'inquiry' => 'Quote',
            default => 'Message',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'in_progress' => 'In progress',
            'done' => 'Done',
            'spam' => 'Spam',
            default => 'New',
        };
    }
}
