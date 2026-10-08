<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table): void {
            $table->id();
            $table->string('installation_id', 100)->unique();
            $table->string('manufacturer', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('model', 150)->nullable();
            $table->string('device', 150)->nullable();
            $table->string('android_version', 50)->nullable();
            $table->unsignedInteger('sdk_int')->nullable();
            $table->string('architecture', 100)->nullable();
            $table->boolean('is_emulator')->default(false);
            $table->string('app_name', 150)->nullable();
            $table->string('package_name', 200)->nullable();
            $table->string('app_version', 50)->nullable();
            $table->unsignedInteger('app_version_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
