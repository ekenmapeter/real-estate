<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDurationTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'duration_key',
        'duration_label',
        'duration_days',
        'required_shares',
        'min_avc_value',
        'target_earnings_pct',
        'is_popular',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'required_shares' => 'integer',
        'min_avc_value' => 'decimal:2',
        'target_earnings_pct' => 'decimal:2',
        'is_popular' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
