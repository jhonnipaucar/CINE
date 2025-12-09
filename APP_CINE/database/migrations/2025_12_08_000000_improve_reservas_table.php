<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Agregar campos si no existen
            if (!Schema::hasColumn('reservas', 'numero_asiento')) {
                $table->string('numero_asiento')->nullable()->after('funcion_id');
            }
            
            if (!Schema::hasColumn('reservas', 'precio')) {
                $table->decimal('precio', 10, 2)->nullable()->after('numero_asiento');
            }

            // Cambiar asientos a JSON
            $table->json('asientos')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['numero_asiento', 'precio']);
        });
    }
};
