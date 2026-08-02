<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): string
    {
        return Str::startsWith($this->image_path, ['http://', 'https://'])
            ? $this->image_path
            : Storage::disk('public')->url($this->image_path);
    }
}
