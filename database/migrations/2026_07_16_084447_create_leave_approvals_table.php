<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_approvals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('leave_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('approver_id')
                ->nullable()
                ->constrained('users')
                ->noActionOnDelete();

            $table->string('approval_level',20);

            $table->string('status',20);

            $table->text('notes')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};