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
        Schema::table('leave_approvals', function (Blueprint $table) {
    
            $table->string('signature_path')->nullable()->after('approved_at');
    
            $table->string('status',20)->default('Waiting')->change();

            $table->string('current_approval', 20)
                ->default('Manager')
                ->after('status');

            $table->string('final_status', 20)
                ->default('Pending')
                ->after('current_approval');
    
        });
    }
    
    public function down(): void
    {
        Schema::table('leave_approvals', function (Blueprint $table) {
    
            $table->dropColumn(
                'signature_path', 
                'current_approval',
                'final_status',
            );

    
        });
    }
};
