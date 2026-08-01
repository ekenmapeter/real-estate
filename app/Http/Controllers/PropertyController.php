<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\SavedProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $savedPropertyIds = Auth::check()
            ? SavedProperty::where('user_id', Auth::id())->pluck('property_id')->all()
            : [];

        return view('properties', compact('properties', 'savedPropertyIds'));
    }

    public function show(Property $property)
    {
        $price = $property->purchasePrice();
        $isSaved = Auth::check() && SavedProperty::where('user_id', Auth::id())->where('property_id', $property->id)->exists();

        return view('property-detail', compact('property', 'price', 'isSaved'));
    }

    public function toggleSave(Property $property)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $existing = SavedProperty::where('user_id', $user->id)->where('property_id', $property->id)->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            SavedProperty::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            $saved = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return redirect()->back()->with('success', $saved
            ? 'Property "' . $property->title . '" saved to your list!'
            : 'Property removed from your saved list.');
    }
}
