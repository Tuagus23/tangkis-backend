<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Detection;
use Illuminate\Database\Seeder;

class DetectionSeeder extends Seeder
{
    public function run(): void
    {
        Detection::create([
            'message' => 'Selamat! Nomor Anda menang undian Rp50.000.000. Segera kirim kode OTP.',
            'category' => 'PENIPUAN',
            'confidence' => 0.92,
            'probabilities' => [
                'PENIPUAN' => 0.92,
                'PROMO' => 0.06,
                'NORMAL' => 0.02,
            ],
            'danger_signs' => ['Minta kode OTP/PIN', 'Iming-iming hadiah', 'Desakan waktu'],
            'detected_at' => now()->subMinutes(10),
        ]);

        Detection::create([
            'message' => 'Diskon 50% semua menu kopi hari ini.',
            'category' => 'PROMO',
            'confidence' => 0.81,
            'probabilities' => [
                'PENIPUAN' => 0.08,
                'PROMO' => 0.81,
                'NORMAL' => 0.11,
            ],
            'danger_signs' => [],
            'detected_at' => now()->subHours(2),
        ]);
    }
}
