<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Nomor Induk Karyawan
            $table->string('nik', 30)
                ->unique()
                ->nullable();

            // Master Data
            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             |------------------------------------------------------------
             | Manager
             |------------------------------------------------------------
             | Tidak menggunakan foreign key agar tidak terjadi
             | Multiple Cascade Paths pada SQL Server.
             */
            $table->unsignedBigInteger('manager_id')->nullable();

            // Informasi Karyawan
            $table->date('join_date');

            $table->date('birth_date')->nullable();

            $table->string('gender', 10);

            $table->text('address')->nullable();

            $table->string('status', 20)
                ->default('Active');

            $table->timestamps();

            // Index
            $table->index('department_id');
            $table->index('position_id');
            $table->index('manager_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};