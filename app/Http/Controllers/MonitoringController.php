<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use App\Models\Device;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $kategori = trim((string) $request->query('kategori', ''));
        $deviceId = trim((string) $request->query('device_id', ''));

        $query = Detection::with('device')->orderByDesc('id');

        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        if ($deviceId !== '') {
            $query->where('device_id', $deviceId);
        }

        $paginator = $query->paginate(20)->withQueryString();

        $detections = $paginator->through(function (Detection $detection) {
            return $this->toJsonShape($detection);
        });

        $stats = [
            'total' => Detection::count(),
            'normal' => Detection::where('kategori', 'NORMAL')->count(),
            'promo' => Detection::where('kategori', 'PROMO')->count(),
            'penipuan' => Detection::where('kategori', 'PENIPUAN')->count(),
            'devices' => Device::count(),
        ];

        $devices = Device::query()
            ->orderBy('manufacturer')
            ->orderBy('model')
            ->get(['id', 'installation_id', 'manufacturer', 'brand', 'model']);

        $categories = ['NORMAL', 'PROMO', 'PENIPUAN'];

        $meta = [
            'count' => $paginator->total(),
            'limit' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
            'server_time' => now()->toIso8601String(),
        ];

        return view('monitoring.index', compact(
            'detections',
            'stats',
            'devices',
            'categories',
            'kategori',
            'deviceId',
            'meta',
        ));
    }

    private function toJsonShape(Detection $detection): array
    {
        $device = $detection->device;

        return [
            'id' => $detection->id,
            'pesan' => $detection->pesan,
            'hasil_deteksi' => [
                'kategori' => $detection->kategori,
                'keyakinan' => (float) $detection->keyakinan,
                'probabilitas' => $detection->probabilitas ?? [],
                'tanda_bahaya' => $detection->tanda_bahaya ?? [],
            ],
            'waktu_deteksi' => $detection->waktu,
            'created_at' => $detection->created_at?->toIso8601String(),
            'perangkat' => $device ? [
                'id' => $device->id,
                'installation_id' => $device->installation_id,
                'manufacturer' => $device->manufacturer,
                'brand' => $device->brand,
                'model' => $device->model,
                'device' => $device->device,
                'android_version' => $device->android_version,
                'sdk_int' => $device->sdk_int,
                'architecture' => $device->architecture,
                'is_emulator' => (bool) $device->is_emulator,
            ] : null,
            'aplikasi' => $device ? [
                'app_name' => $device->app_name,
                'package_name' => $device->package_name,
                'version_name' => $device->version_name,
                'version_code' => $device->version_code,
                'build_type' => $device->build_type,
            ] : null,
            'jaringan' => [
                'connection_type' => $detection->network_type,
                'local_ip' => $detection->local_ip,
                'server_observed_ip' => $detection->server_ip,
            ],
            'lokasi' => [
                'permission_granted' => $detection->location_permission_granted === null
                    ? null
                    : (bool) $detection->location_permission_granted,
                'latitude' => $detection->latitude,
                'longitude' => $detection->longitude,
                'accuracy_m' => $detection->location_accuracy,
            ],
        ];
    }
}
