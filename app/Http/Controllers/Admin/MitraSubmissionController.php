<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Services\MitraBusinessSynchronizer;

class MitraSubmissionController extends Controller
{
    /**
     * Display all mitra submissions (destinations, hotels, restaurants)
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');   // all, wisata, hotel, restoran (berbasis mitra)
        $status = $request->get('status', 'all'); // all, pending, approved, rejected

        // Query hanya dari tabel mitras sebagai sumber utama pengajuan
        $mitrasQuery = Mitra::with(['user', 'city']);

        if ($status !== 'all') {
            $mitrasQuery->where('status', $status);
        }

        // Filter jenis bisnis jika diperlukan
        if (in_array($type, ['wisata', 'hotel', 'restoran'])) {
            $map = [
                'wisata' => 'wisata',
                'hotel' => 'hotel',
                'restoran' => 'restoran',
            ];
            $mitrasQuery->where('business_type', $map[$type]);
        }

        $submissions = $mitrasQuery
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->submission_type = 'mitra';
                $item->type_label = match ($item->business_type) {
                    'hotel' => 'Hotel',
                    'restoran' => 'Restoran',
                    'wisata' => 'Destinasi Wisata',
                    default => 'Bisnis Mitra',
                };
                $item->type_icon = match ($item->business_type) {
                    'hotel' => 'fa-hotel',
                    'restoran' => 'fa-utensils',
                    'wisata' => 'fa-map-marked-alt',
                    default => 'fa-briefcase',
                };
                $item->type_color = match ($item->business_type) {
                    'hotel' => 'forest',
                    'restoran' => 'earth',
                    'wisata' => 'ocean',
                    default => 'blue',
                };
                $item->name = $item->business_name;
                $item->description = $item->business_description;
                return $item;
            });

        // Statistik berdasarkan mitra saja (menghindari duplikasi dari tabel publik)
        $stats = [
            'total'     => Mitra::count(),
            'pending'   => Mitra::where('status', 'pending')->count(),
            'approved'  => Mitra::where('status', 'approved')->count(),
            'rejected'  => Mitra::where('status', 'rejected')->count(),
            'destinations' => Mitra::where('business_type', 'wisata')->count(),
            'hotels'       => Mitra::where('business_type', 'hotel')->count(),
            'restaurants'  => Mitra::where('business_type', 'restoran')->count(),
            'mitras'       => Mitra::count(),
        ];

        return view('admin.mitra-submissions.index', compact('submissions', 'stats', 'type', 'status'));
    }

    /**
     * Approve submission
     */
    public function approve(Request $request)
    {
        $type = $request->type; // destination, hotel, restaurant
        $id = $request->id;

        $model = $this->getModel($type);
        $submission = $model::findOrFail($id);
        
        $submission->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        if ($type === 'mitra') {
            MitraBusinessSynchronizer::sync($submission);
        }

        return back()->with('success', ucfirst($type) . ' berhasil disetujui!');
    }

    /**
     * Reject submission
     */
    public function reject(Request $request)
    {
        $request->validate([
            'type' => 'required|in:destination,hotel,restaurant,mitra',
            'id' => 'required|integer',
            'reason' => 'required|string|min:10',
        ]);

        $model = $this->getModel($request->type);
        $submission = $model::findOrFail($request->id);
        
        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        if ($request->type === 'mitra') {
            MitraBusinessSynchronizer::sync($submission);
        }

        return back()->with('success', ucfirst($request->type) . ' berhasil ditolak!');
    }

    /**
     * Delete submission
     */
    public function destroy(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        $model = $this->getModel($type);
        $submission = $model::findOrFail($id);
        if ($type === 'mitra') {
            MitraBusinessSynchronizer::deleteLinkedListing($submission);
        }

        $submission->delete();

        return back()->with('success', 'Submission berhasil dihapus!');
    }

    /**
     * Get model class based on type
     */
    private function getModel($type)
    {
        return match($type) {
            'destination' => Destination::class,
            'hotel' => Hotel::class,
            'restaurant' => Restaurant::class,
            'mitra' => Mitra::class,
            default => throw new \Exception('Invalid submission type'),
        };
    }
}