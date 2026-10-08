<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'manufacturer',
        'brand',
        'model',
        'device',
        'android_version',
        'sdk_int',
        'architecture',
        'is_emulator',
        'app_name',
        'package_name',
        'app_version',
        'app_version_code',
        'build_type',
    ];

    protected function casts(): array
    {
        return [
            'sdk_int' => 'integer',
            'is_emulator' => 'boolean',
            'app_version_code' => 'integer',
        ];
    }

    public function detections(): HasMany
    {
        return $this->hasMany(Detection::class);
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'installation_id' => $this->installation_id,
            'manufacturer' => $this->manufacturer,
            'brand' => $this->brand,
            'model' => $this->model,
            'device' => $this->device,
            'android_version' => $this->android_version,
            'sdk_int' => $this->sdk_int,
            'architecture' => $this->architecture,
            'is_emulator' => (bool) $this->is_emulator,
        ];
    }

    public function appToApiArray(): array
    {
        return [
            'app_name' => $this->app_name,
            'package_name' => $this->package_name,
            'version_name' => $this->app_version,
            'version_code' => $this->app_version_code,
            'build_type' => $this->build_type,
        ];
    }
}
