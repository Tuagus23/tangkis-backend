<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detections', function (Blueprint $table): void {
            $table->boolean('location_permission_granted')
                ->default(false)
                ->after('location_accuracy');
        });
    }

    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table): void {
            $table->dropColumn('location_permission_granted');
        });
    }
};
