<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function syncCity(Request $request)
    {
        $data = $request->validate([
            'province_code' => 'required|string',
            'province_name' => 'required|string',
            'city_code' => 'required|string',
            'city_name' => 'required|string',
        ]);

        $province = Province::firstOrCreate(
            ['code' => $data['province_code']],
            ['name' => $data['province_name']]
        );

        $city = City::firstOrCreate(
            [
                'name' => $data['city_name'],
                'province_id' => $province->id,
            ],
            []
        );

        return response()->json([
            'city_id' => $city->id,
        ]);
    }
}

