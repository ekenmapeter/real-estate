<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'reporter_id',
        'report_type',
        'reason',
        'status',
    ];

    public function reportTypeLabel(): string
    {
        return $this->report_type === 'fraud' ? 'Fraud Report' : 'Reported Listing';
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
