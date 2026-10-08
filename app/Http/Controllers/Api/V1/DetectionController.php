<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDetectionRequest;
use App\Models\Detection;
use App\Models\Device;
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
            ->with('device')
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
                'server_time' => now()->toIso8601String(),
            ],
        ]);
    }

    public function store(StoreDetectionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $deviceData = $validated['perangkat'];
        $appData = $validated['aplikasi'] ?? [];
        $networkData = $validated['jaringan'] ?? [];
        $locationData = $validated['lokasi'] ?? [];

        $device = Device::updateOrCreate(
            ['installation_id' => $deviceData['installation_id']],
            [
                'manufacturer' => $deviceData['manufacturer'] ?? null,
                'brand' => $deviceData['brand'] ?? null,
                'model' => $deviceData['model'] ?? null,
                'device' => $deviceData['device'] ?? null,
                'android_version' => $deviceData['android_version'] ?? null,
                'sdk_int' => $deviceData['sdk_int'] ?? null,
                'architecture' => $deviceData['architecture'] ?? null,
                'is_emulator' => $deviceData['is_emulator'] ?? false,
                'app_name' => $appData['app_name'] ?? null,
                'package_name' => $appData['package_name'] ?? null,
                'app_version' => $appData['version_name'] ?? null,
                'app_version_code' => $appData['version_code'] ?? null,
                'build_type' => $appData['build_type'] ?? null,
            ],
        );

        $detection = Detection::create([
            'device_id' => $device->id,
            'message' => $validated['pesan'],
            'category' => $validated['kategori'],
            'confidence' => $validated['keyakinan'],
            'probabilities' => $validated['probabilitas'],
            'danger_signs' => $validated['tanda_bahaya'] ?? [],
            'detected_at' => $validated['waktu'] ?? now(),
            'server_ip' => $request->ip(),
            'local_ip' => $networkData['local_ip'] ?? null,
            'network_type' => $networkData['connection_type'] ?? null,
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
            'location_accuracy' => $locationData['accuracy_m'] ?? null,
            'location_permission_granted' => $locationData['permission_granted'] ?? false,
        ]);

        $detection->load('device');

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
                'total_devices' => Device::query()->count(),
            ],
        ]);
    }
}
