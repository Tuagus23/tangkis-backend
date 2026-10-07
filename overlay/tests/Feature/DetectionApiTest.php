<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Detection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetectionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_works(): void
    {
        $this->getJson('/api/v1/health')
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('service', 'tangkis-api');
    }

    public function test_detection_can_be_created(): void
    {
        $payload = [
            'pesan' => 'Segera kirim OTP untuk klaim hadiah.',
            'kategori' => 'PENIPUAN',
            'keyakinan' => 0.92,
            'probabilitas' => [
                'PENIPUAN' => 0.92,
                'PROMO' => 0.06,
                'NORMAL' => 0.02,
            ],
            'tanda_bahaya' => ['Minta kode OTP/PIN', 'Desakan waktu'],
            'waktu' => '2026-10-07T11:00:00+00:00',
        ];

        $this->postJson('/api/v1/detections', $payload)
            ->assertCreated()
            ->assertJsonPath('data.kategori', 'PENIPUAN')
            ->assertJsonPath('data.keyakinan', 0.92);

        $this->assertDatabaseHas('detections', [
            'category' => 'PENIPUAN',
            'message' => 'Segera kirim OTP untuk klaim hadiah.',
        ]);
    }

    public function test_detection_list_can_be_filtered(): void
    {
        Detection::create([
            'message' => 'Promo kopi',
            'category' => 'PROMO',
            'confidence' => 0.8,
            'probabilities' => ['PENIPUAN' => 0.1, 'PROMO' => 0.8, 'NORMAL' => 0.1],
            'danger_signs' => [],
            'detected_at' => now(),
        ]);

        $this->getJson('/api/v1/detections?category=PENIPUAN')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }
}
