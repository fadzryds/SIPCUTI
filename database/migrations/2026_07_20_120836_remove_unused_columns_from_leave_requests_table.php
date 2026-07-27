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
        Schema::table('leave_requests', function (Blueprint $table) {

            // Hapus kolom yang tidak diperlukan
            $table->dropColumn([
                'manager_note',
                'hr_note',
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {

            $table->text('manager_note')->nullable();

            $table->text('hr_note')->nullable();

        });
    }
};