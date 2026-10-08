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
            $table->foreignId('device_id')
                ->nullable()
                ->after('id')
                ->constrained('devices')
                ->nullOnDelete();

            $table->string('server_ip', 45)->nullable()->after('device_id');
            $table->string('local_ip', 45)->nullable()->after('server_ip');
            $table->string('network_type', 30)->nullable()->after('local_ip');
            $table->decimal('latitude', 10, 7)->nullable()->after('network_type');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->decimal('location_accuracy', 8, 2)->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table): void {
            $table->dropForeign(['device_id']);
            $table->dropColumn([
                'device_id',
                'server_ip',
                'local_ip',
                'network_type',
                'latitude',
                'longitude',
                'location_accuracy',
            ]);
        });
    }
};
