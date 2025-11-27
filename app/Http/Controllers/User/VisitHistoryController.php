<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use Illuminate\Support\Facades\Auth;

class VisitHistoryController extends Controller
{
    /**
     * Tampilkan riwayat kunjungan
     */
    public function index(Request $request)
    {
        $query = VisitHistory::with(['destination.mitra'])
            ->forUser(Auth::id())
            ->latest();

        // Filter berdasarkan tahun jika ada
        if ($request->has('year')) {
            $query->inYear($request->year);
        }

        $visitHistories = $query->paginate(15);

        // Ambil daftar tahun untuk filter
        $years = VisitHistory::forUser(Auth::id())
            ->selectRaw('YEAR(visit_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('user.visit-histories.index', compact('visitHistories', 'years'));
    }

    /**
     * Form tambah riwayat kunjungan
     */
    public function create()
    {
        $destinations = Destination::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.visit-histories.create', compact('destinations'));
    }

    /**
     * Simpan riwayat kunjungan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ]);

        $visitHistory = VisitHistory::create([
            'user_id' => Auth::id(),
            'destination_id' => $validated['destination_id'],
            'visit_date' => $validated['visit_date'],
            'notes' => $validated['notes'] ?? null
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat kunjungan berhasil ditambahkan',
                'data' => $visitHistory->load('destination')
            ]);
        }

        return redirect()->route('user.visit-histories.index')
            ->with('success', 'Riwayat kunjungan berhasil ditambahkan');
    }

    /**
     * Form edit riwayat kunjungan
     */
    public function edit($id)
    {
        $visitHistory = VisitHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $destinations = Destination::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.visit-histories.edit', compact('visitHistory', 'destinations'));
    }

    /**
     * Update riwayat kunjungan
     */
    public function update(Request $request, $id)
    {
        $visitHistory = VisitHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ]);

        $visitHistory->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat kunjungan berhasil diperbarui',
                'data' => $visitHistory->load('destination')
            ]);
        }

        return redirect()->route('user.visit-histories.index')
            ->with('success', 'Riwayat kunjungan berhasil diperbarui');
    }

    /**
     * Hapus riwayat kunjungan
     */
    public function destroy($id)
    {
        $visitHistory = VisitHistory::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $visitHistory->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat kunjungan berhasil dihapus'
            ]);
        }

        return redirect()->route('user.visit-histories.index')
            ->with('success', 'Riwayat kunjungan berhasil dihapus');
    }

    /**
     * Statistik kunjungan user
     */
    public function statistics()
    {
        $userId = Auth::id();

        $stats = [
            'total_visits' => VisitHistory::forUser($userId)->count(),
            'unique_destinations' => VisitHistory::forUser($userId)
                ->distinct('destination_id')
                ->count('destination_id'),
            'this_year' => VisitHistory::forUser($userId)
                ->inYear(now()->year)
                ->count(),
            'last_visit' => VisitHistory::forUser($userId)
                ->latest()
                ->first()
        ];

        return response()->json($stats);
    }
}
