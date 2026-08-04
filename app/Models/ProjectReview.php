<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'rating',
        'review',
        'reviewer_name',
        'is_admin',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_admin' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the display name for the reviewer.
     */
    public function displayName(): string
    {
        if ($this->is_admin && $this->reviewer_name) {
            return $this->reviewer_name;
        }

        if ($this->user) {
            return $this->user->name;
        }

        return $this->reviewer_name ?? 'Anonymous';
    }

    /**
     * Get the initials for avatar display.
     */
    public function initials(): string
    {
        $name = $this->displayName();
        $parts = explode(' ', $name);
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return $initials ?: '?';
    }
}
