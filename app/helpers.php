<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('site_name')) {
    function site_name(): string
    {
        return Setting::get('site_name', 'radiantdreamrealty');
    }
}

if (! function_exists('logo_url')) {
    function logo_url(): string
    {
        $path = Setting::get('logo_path', '');

        if (! $path) {
            return asset('frontend/images/logo/radiantblue.png');
        }

        $version = 'v=1';
        if (Storage::disk('public')->exists($path)) {
            $version = 'v=' . Storage::disk('public')->lastModified($path);
        }

        return asset('storage/' . $path) . '?' . $version;
    }
}
