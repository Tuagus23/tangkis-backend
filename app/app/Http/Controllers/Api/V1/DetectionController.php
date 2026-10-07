<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDetectionRequest;
use App\Models\Detection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->integer('limit', 50), 1), 100);
        $category = strtoupper((string) $request->query('category', ''));

        if ($category !== '' && !in_array($category, ['NORMAL', 'PENIPUAN', 'PROMO'], true)) {
            return response()->json([
                'message' => 'category harus NORMAL, PENIPUAN, atau PROMO.',
            ], 422);
        }

        $detections = Detection::query()
            ->category($category !== '' ? $category : null)
            ->latest('detected_at')
            ->latest('id')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => $detections->map->toApiArray()->values(),
            'meta' => [
                'count' => $detections->count(),
                'limit' => $limit,
            ],
        ]);
    }

    public function store(StoreDetectionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $detection = Detection::create([
            'message' => $validated['pesan'],
            'category' => $validated['kategori'],
            'confidence' => $validated['keyakinan'],
            'probabilities' => $validated['probabilitas'],
            'danger_signs' => $validated['tanda_bahaya'] ?? [],
            'detected_at' => $validated['waktu'] ?? now(),
        ]);

        return response()->json([
            'data' => $detection->toApiArray(),
        ], 201);
    }

    public function stats(): JsonResponse
    {
        $counts = Detection::query()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return response()->json([
            'data' => [
                'NORMAL' => (int) ($counts['NORMAL'] ?? 0),
                'PENIPUAN' => (int) ($counts['PENIPUAN'] ?? 0),
                'PROMO' => (int) ($counts['PROMO'] ?? 0),
                'total' => (int) $counts->sum(),
            ],
        ]);
    }
}
