<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\Province;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\MitraBusinessSynchronizer;

class DashboardController extends Controller
{
    /**
     * Generate province code from province name
     */
    private function getProvinceCode($provinceName)
    {
        $codes = [
            'Banten' => 'BT',
            'DKI Jakarta' => 'JK',
            'Jawa Barat' => 'JB',
            'Jawa Tengah' => 'JT',
            'DI Yogyakarta' => 'YK',
            'Jawa Timur' => 'JI',
        ];
        
        return $codes[$provinceName] ?? strtoupper(substr($provinceName, 0, 2));
    }
    public function index()
    {
        $user = auth()->user();
        
        // Pastikan user adalah mitra
        if ($user->role !== 'mitra') {
            abort(403, 'Akses ditolak. Anda bukan mitra.');
        }
        
        $mitra = $user->mitra()
            ->with(['city', 'province'])
            ->first();

        // Tampilkan dashboard dengan atau tanpa data mitra
        return view('mitra.dashboard', compact('mitra'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Jika sudah ada mitra, redirect ke dashboard
        if ($user->mitra) {
            return redirect()->route('mitra.dashboard')
                ->with('info', 'Data bisnis Anda sudah ada.');
        }

        $tags = Tag::orderBy('name')->get();
        return view('mitra.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Cek apakah sudah ada mitra
        if ($user->mitra) {
            return redirect()->route('mitra.dashboard')
                ->with('error', 'Data bisnis Anda sudah ada.');
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:hotel,restoran,wisata',
            'business_description' => 'required|string',
            'business_address' => 'required|string',
            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find or create province
        $provinceCode = $this->getProvinceCode($request->province_name);
        $province = Province::firstOrCreate(
            ['name' => $request->province_name],
            ['code' => $provinceCode]
        );
        
        // Find or create city
        $city = City::firstOrCreate(
            ['name' => $request->city_name, 'province_id' => $province->id],
            []
        );

        $data = [
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'business_description' => $request->business_description,
            'business_address' => $request->business_address,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'website' => $request->website,
            'status' => 'pending'
        ];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('mitra/thumbnails', 'public');
        }

        $mitra = Mitra::create($data);

        $tagIds = $request->input('tags', []);
        MitraBusinessSynchronizer::sync($mitra, $tagIds);

        return redirect()->route('mitra.dashboard')
            ->with('success', 'Data bisnis berhasil disimpan! Menunggu persetujuan admin.');
    }

    public function edit()
    {
        $user = auth()->user();
        $mitra = $user->mitra;

        if (!$mitra) {
            return redirect()->route('mitra.create')
                ->with('error', 'Data bisnis tidak ditemukan.');
        }

        $tags = Tag::orderBy('name')->get();
        
        // Get current tags from related business (destination/hotel/restaurant)
        $businessTags = [];
        if ($mitra->business_type === 'wisata') {
            $destination = \App\Models\Destination::where('user_id', $user->id)->first();
            if ($destination) {
                $businessTags = $destination->tags->pluck('id')->toArray();
            }
        } elseif ($mitra->business_type === 'hotel') {
            $hotel = \App\Models\Hotel::where('user_id', $user->id)->first();
            if ($hotel) {
                $businessTags = $hotel->tags->pluck('id')->toArray();
            }
        } elseif ($mitra->business_type === 'restoran') {
            $restaurant = \App\Models\Restaurant::where('user_id', $user->id)->first();
            if ($restaurant) {
                $businessTags = $restaurant->tags->pluck('id')->toArray();
            }
        }

        return view('mitra.edit', compact('mitra', 'tags', 'businessTags'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $mitra = $user->mitra;

        if (!$mitra) {
            return redirect()->route('mitra.create')
                ->with('error', 'Data bisnis tidak ditemukan.');
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:hotel,restoran,wisata',
            'business_description' => 'required|string',
            'business_address' => 'required|string',
            'province_name' => 'required|string',
            'city_name' => 'required|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'website' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find or create province
        $provinceCode = $this->getProvinceCode($request->province_name);
        $province = Province::firstOrCreate(
            ['name' => $request->province_name],
            ['code' => $provinceCode]
        );
        
        // Find or create city
        $city = City::firstOrCreate(
            ['name' => $request->city_name, 'province_id' => $province->id],
            []
        );

        $data = [
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'business_description' => $request->business_description,
            'business_address' => $request->business_address,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'website' => $request->website,
        ];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($mitra->thumbnail) {
                Storage::disk('public')->delete($mitra->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('mitra/thumbnails', 'public');
        }

        $mitra->update($data);

        $tagIds = $request->input('tags', []);
        MitraBusinessSynchronizer::sync($mitra, $tagIds);

        return redirect()->route('mitra.dashboard')
            ->with('success', 'Data bisnis berhasil diperbarui!');
    }
}