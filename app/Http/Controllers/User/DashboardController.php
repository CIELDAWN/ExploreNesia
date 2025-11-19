<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;

class DashboardController extends Controller
{
    public function index()
    {
        // Get recent approved destinations
        $recentDestinations = Destination::where('status', 'approved')
            ->where('is_active', true)
            ->with(['city', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('user.dashboard', compact('recentDestinations'));
    }
}
