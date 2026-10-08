<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table): void {
            $table->string('build_type', 30)->nullable()->after('app_version_code');
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table): void {
            $table->dropColumn('build_type');
        });
    }
};
