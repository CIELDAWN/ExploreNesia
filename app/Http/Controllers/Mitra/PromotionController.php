<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Promotion;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::whereHasMorph(
            'promotionable',
            [Destination::class, Hotel::class, Restaurant::class],
            function ($query) {
                $query->where('user_id', auth()->id());
            }
        )->latest()->paginate(10);

        return view('mitra.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $data = $this->promotionOptions();
        return view('mitra.promotions.create', $data);
    }

    public function store(Request $request)
    {
        $data = $this->validatePromotion($request);
        Promotion::create($data);

        return redirect()->route('mitra.promotions.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    public function edit(Promotion $promotion)
    {
        $this->authorizePromotion($promotion);
        $data = $this->promotionOptions();

        return view('mitra.promotions.edit', array_merge($data, ['promotion' => $promotion]));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $this->authorizePromotion($promotion);
        $data = $this->validatePromotion($request);

        $promotion->update($data);

        return redirect()->route('mitra.promotions.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $this->authorizePromotion($promotion);
        $promotion->delete();

        return redirect()->route('mitra.promotions.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    protected function validatePromotion(Request $request): array
    {
        $data = $request->validate([
            'promotionable_type' => 'required|in:destination,hotel,restaurant',
            'promotionable_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_usage' => 'nullable|integer|min:1',
            'min_transaction' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        [$typeClass, $ownerId] = $this->resolvePromotionable($data['promotionable_type'], $data['promotionable_id']);

        if ($ownerId !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $data['promotionable_type'] = $typeClass;
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    protected function resolvePromotionable(string $type, int $id): array
    {
        return match ($type) {
            'destination' => [Destination::class, Destination::findOrFail($id)->user_id ?? null],
            'hotel' => [Hotel::class, Hotel::findOrFail($id)->user_id ?? null],
            'restaurant' => [Restaurant::class, Restaurant::findOrFail($id)->user_id ?? null],
            default => [null, null],
        };
    }

    protected function promotionOptions(): array
    {
        $userId = auth()->id();

        return [
            'destinations' => Destination::where('user_id', $userId)->orderBy('name')->get(),
            'hotels' => Hotel::where('user_id', $userId)->orderBy('name')->get(),
            'restaurants' => Restaurant::where('user_id', $userId)->orderBy('name')->get(),
        ];
    }

    protected function authorizePromotion(Promotion $promotion): void
    {
        $ownerId = optional($promotion->promotionable)->user_id;

        if ($ownerId !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke promo ini.');
        }
    }
}






