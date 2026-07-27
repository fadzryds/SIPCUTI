<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {

            $table->id();

            $table->string('request_number',30)->unique();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('leave_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('start_date');

            $table->date('end_date');

            $table->unsignedSmallInteger('total_days');

            $table->text('reason');

            $table->string('attachment')->nullable();

            $table->enum('status',[
                'Pending',
                'Approved',
                'Rejected',
                'Completed'
            ])->default('Pending');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};