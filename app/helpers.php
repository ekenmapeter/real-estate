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

if (! function_exists('avc_rates')) {
    /**
     * Indicative fiat conversion rates for Aurevia Credits (AVC).
     * AVC is an internal platform credit: 1 AVC = 1 USD base value.
     *
     * @return array<string, float>
     */
    function avc_rates(): array
    {
        return [
            'USD' => 1.00,
            'EUR' => 0.92,
            'GBP' => 0.78,
            'PHP' => 56.50,
            'NGN' => 1450.00,
            'AED' => 3.67,
            'SGD' => 1.34,
            'CAD' => 1.36,
            'AUD' => 1.52,
        ];
    }
}

if (! function_exists('avc_to_fiat')) {
    function avc_to_fiat(float $avc, string $currency = 'USD'): float
    {
        $currency = strtoupper($currency);
        $rate = avc_rates()[$currency] ?? 1.00;

        return $avc * $rate;
    }
}

if (! function_exists('format_avc')) {
    /**
     * Format a wallet balance as AVC (Aurevia Credits).
     */
    function format_avc(float $avc): string
    {
        return number_format($avc, ($avc == floor($avc)) ? 0 : 2) . ' AVC';
    }
}

if (! function_exists('avc_equivalent')) {
    /**
     * Fiat equivalent label shown alongside an AVC balance, e.g. "≈ $12,500.00 USD".
     */
    function avc_equivalent(float $avc, string $currency = 'USD'): string
    {
        $currency = strtoupper($currency);

        return '≈ ' . number_format(avc_to_fiat($avc, $currency), 2) . ' ' . $currency;
    }
}

if (! function_exists('telegram_handle')) {
    /**
     * The official Finance Team Telegram handle configured in the admin settings.
     */
    function telegram_handle(): string
    {
        $handle = Setting::get('telegram_handle', '');

        return ltrim(trim($handle), '@');
    }
}

if (! function_exists('telegram_url')) {
    /**
     * Build a t.me share link with a pre-filled message.
     */
    function telegram_url(string $message): string
    {
        if (! telegram_handle()) {
            return '#';
        }

        return 'https://t.me/' . telegram_handle() . '?text=' . rawurlencode($message);
    }
}

if (! function_exists('masked_name')) {
    /**
     * Mask a user's name for public marketplace listings, e.g. "John D.**".
     */
    function masked_name(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return 'Verified User';
        }

        $parts = preg_split('/\s+/', $name);
        $first = $parts[0] ?? '';
        $last = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '';

        return ucwords($first) . ($last !== '' ? ' ' . strtoupper($last) . '.**' : '');
    }
}
