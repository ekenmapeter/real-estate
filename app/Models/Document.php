<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'category',
        'document_type',
        'title',
        'reference',
        'related_type',
        'related_id',
        'file_path',
        'status',
        'issued_at',
        'metadata',
        'share_token',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($document) {
            if (empty($document->uuid)) {
                $document->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'project_investment' => 'Project Investment',
            'property' => 'Property',
            'finance' => 'Finance',
            'marketplace' => 'AVC Marketplace',
            'verification' => 'Verification & Legal',
            'statement' => 'Statements & Reports',
            default => ucfirst((string) $this->category),
        };
    }

    public function statusLabel(): string
    {
        return ucfirst((string) $this->status);
    }

    public function statusBadge(): array
    {
        return match ($this->status) {
            'active', 'completed' => ['#f0fdf4', '#16a34a'],
            'new' => ['#eff6ff', '#2563eb'],
            'verified' => ['#f0fdfa', '#0d9488'],
            'pending' => ['#fffbeb', '#d97706'],
            'archived' => ['#f1f5f9', '#64748b'],
            'rejected' => ['#fef2f2', '#dc2626'],
            default => ['#f8fafc', '#64748b'],
        };
    }

    public function categoryBadge(): array
    {
        return match ($this->category) {
            'project_investment' => ['#eff6ff', '#2563eb'],
            'property' => ['#f5f3ff', '#7c3aed'],
            'finance' => ['#fff7ed', '#ea580c'],
            'marketplace' => ['#fdf2f8', '#db2777'],
            'verification' => ['#f0fdfa', '#0d9488'],
            'statement' => ['#f8fafc', '#475569'],
            default => ['#f8fafc', '#64748b'],
        };
    }

    public function relatedLabel(): string
    {
        if ($this->metadata && isset($this->metadata['related_label'])) {
            return $this->metadata['related_label'];
        }

        return $this->related?->title ?? $this->related?->listing_number ?? ($this->related?->deposit_code ?? ($this->related?->reference ?? '—'));
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfCategory($query, ?string $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }

        return $query;
    }

    public function scopeOfStatus($query, ?string $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhere('metadata', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function scopeBetweenDates($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('issued_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('issued_at', '<=', $to);
        }

        return $query;
    }
}
