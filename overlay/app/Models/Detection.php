<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Detection extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'category',
        'confidence',
        'probabilities',
        'danger_signs',
        'detected_at',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'float',
            'probabilities' => 'array',
            'danger_signs' => 'array',
            'detected_at' => 'datetime',
        ];
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'pesan' => $this->message,
            'kategori' => $this->category,
            'keyakinan' => (float) $this->confidence,
            'probabilitas' => $this->probabilities ?? [],
            'tanda_bahaya' => $this->danger_signs ?? [],
            'waktu' => $this->detected_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
