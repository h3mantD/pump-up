<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection() instanceof PostgresConnection) {
            Schema::ensureVectorExtensionExists();

            Schema::table('products', function (Blueprint $table): void {
                $table->vector('embedding', dimensions: 1536)->nullable()->index();
            });
        } else {
            Schema::table('products', function (Blueprint $table): void {
                $table->text('embedding')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('embedding');
        });
    }
};
