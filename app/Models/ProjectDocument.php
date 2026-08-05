<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'document_type',
        'file_path',
        'is_restricted',
    ];

    protected $casts = [
        'is_restricted' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
