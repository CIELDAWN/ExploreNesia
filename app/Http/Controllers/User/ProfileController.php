<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $provinces = \App\Models\Province::orderBy('name')->get();
        $cities = \App\Models\City::orderBy('name')->get();

        return view('user.profile', compact('user', 'provinces', 'cities'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
        ]);

        $user->update($validated);

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
