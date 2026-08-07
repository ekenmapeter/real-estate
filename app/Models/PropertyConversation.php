<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'property_id',
        'channel',
        'external_link',
        'participants',
        'status',
    ];

    protected $casts = [
        'participants' => 'array',
    ];

    public function channelLabel(): string
    {
        return match ($this->channel) {
            'whatsapp_group' => 'WhatsApp Group',
            'telegram_group' => 'Telegram Group',
            'call' => 'Scheduled Phone Call',
            'meeting' => 'Video Meeting',
            default => ucfirst((string) $this->channel),
        };
    }

    public function inquiry()
    {
        return $this->belongsTo(PropertyInquiry::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
