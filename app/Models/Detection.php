<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'message',
        'category',
        'confidence',
        'probabilities',
        'danger_signs',
        'detected_at',
        'server_ip',
        'local_ip',
        'network_type',
        'latitude',
        'longitude',
        'location_accuracy',
        'location_permission_granted',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'float',
            'probabilities' => 'array',
            'danger_signs' => 'array',
            'detected_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
            'location_accuracy' => 'float',
            'location_permission_granted' => 'boolean',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function toApiArray(): array
    {
        $device = $this->device;

        return [
            'id' => $this->id,
            'pesan' => $this->message,
            'hasil_deteksi' => [
                'kategori' => $this->category,
                'keyakinan' => (float) $this->confidence,
                'probabilitas' => $this->probabilities ?? [],
                'tanda_bahaya' => $this->danger_signs ?? [],
            ],
            'waktu_deteksi' => $this->detected_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'perangkat' => $device?->toApiArray(),
            'aplikasi' => $device?->appToApiArray(),
            'jaringan' => [
                'connection_type' => $this->network_type,
                'local_ip' => $this->local_ip,
                'server_observed_ip' => $this->server_ip,
            ],
            'lokasi' => [
                'permission_granted' => (bool) $this->location_permission_granted,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy_m' => $this->location_accuracy,
            ],

            // Field lama dipertahankan agar client versi sebelumnya tetap kompatibel.
            'kategori' => $this->category,
            'keyakinan' => (float) $this->confidence,
            'probabilitas' => $this->probabilities ?? [],
            'tanda_bahaya' => $this->danger_signs ?? [],
            'waktu' => $this->detected_at?->toIso8601String(),
        ];
    }
}
