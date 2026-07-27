<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {

            $table->unsignedSmallInteger('carry_forward')
                ->default(0)
                ->after('quota');

            $table->boolean('is_active')
                ->default(true)
                ->after('remaining');

        });
    }

    public function down(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {

            $table->dropColumn([
                'carry_forward',
                'is_active',
            ]);

        });
    }
};