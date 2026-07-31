<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', 'like', "%{$category}%");
            }
        }

        $properties = $query->orderBy('id', 'desc')->get();

        return view('properties', compact('properties'));
    }

    public function show(Property $property)
    {
        $fundedPercent = $property->total_shares > 0
            ? round((($property->total_shares - $property->available_shares) / $property->total_shares) * 100)
            : 0;

        $raisedAmount = ($property->total_shares - $property->available_shares) * $property->price_per_share;
        $totalValuation = $property->total_shares * $property->price_per_share;

        return view('property-detail', compact('property', 'fundedPercent', 'raisedAmount', 'totalValuation'));
    }
}
