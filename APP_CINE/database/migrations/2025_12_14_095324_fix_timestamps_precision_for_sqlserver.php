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
        // Cambiar el tipo de columna de timestamp a datetime2 para SQL Server
        if (Schema::getConnection()->getDriverName() === 'sqlsrv') {
            Schema::table('users', function (Blueprint $table) {
                $table->dateTime('email_verified_at')->nullable()->change();
                $table->dateTime('created_at')->nullable()->change();
                $table->dateTime('updated_at')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlsrv') {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->change();
                $table->timestamp('created_at')->nullable()->change();
                $table->timestamp('updated_at')->nullable()->change();
            });
        }
    }
};
