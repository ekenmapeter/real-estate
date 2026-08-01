<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'amount',
        'expected_roi_amount',
        'roi_earned',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expected_roi_amount' => 'decimal:2',
        'roi_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
