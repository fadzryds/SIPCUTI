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
        
            $table->unsignedBigInteger('current_approver_id')
                  ->nullable()
                  ->after('status');
        
            $table->text('manager_note')->nullable();
        
            $table->text('hr_note')->nullable();
        
            $table->timestamp('approved_at')->nullable();
        
            $table->timestamp('cancelled_at')->nullable();
        
            $table->text('cancel_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            //
        });
    }
};
