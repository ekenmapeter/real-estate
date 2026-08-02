<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image_path',
        'sort_order',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function url(): string
    {
        return Str::startsWith($this->image_path, ['http://', 'https://'])
            ? $this->image_path
            : Storage::disk('public')->url($this->image_path);
    }
}
