<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detections', function (Blueprint $table): void {
            $table->id();
            $table->text('message');
            $table->string('category', 20)->index();
            $table->decimal('confidence', 5, 4);
            $table->json('probabilities');
            $table->json('danger_signs')->nullable();
            $table->timestamp('detected_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detections');
    }
};
