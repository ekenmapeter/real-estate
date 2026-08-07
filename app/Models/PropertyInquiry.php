<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_number',
        'property_id',
        'user_id',
        'type',
        'full_name',
        'email',
        'phone',
        'preferred_date',
        'preferred_time',
        'viewing_type',
        'attendees',
        'message',
        'preferred_channel',
        'status',
        'admin_note',
        'logs',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'attendees' => 'integer',
        'logs' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($inquiry) {
            if (empty($inquiry->inquiry_number)) {
                $inquiry->inquiry_number = 'AVI-' . strtoupper((string) Str::random(6));
            }
        });
    }

    public function appendLog(string $action, ?string $actor = null): void
    {
        $logs = $this->logs ?? [];
        $logs[] = [
            'at' => now()->toDateTimeString(),
            'actor' => $actor ?? 'System',
            'action' => $action,
        ];
        $this->logs = $logs;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'purchase' => 'Purchase Interest',
            'rental' => 'Rental Application',
            'viewing' => 'Viewing Request',
            default => 'General Inquiry',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'awaiting_admin_review' => 'Awaiting Admin Review',
            'representative_verification' => 'Representative Verification',
            'viewing_scheduled' => 'Viewing Scheduled',
            'purchase_discussion' => 'Purchase Discussion',
            'rental_review' => 'Rental Review',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(PropertyConversation::class, 'inquiry_id');
    }
}
