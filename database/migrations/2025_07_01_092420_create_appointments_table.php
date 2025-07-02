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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->nullable();
            $table->string('task_name')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('department')->nullable();
            $table->string('supervisor')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('time')->nullable();
            $table->string('status')->nullable();
            $table->text('details')->nullable();
            $table->string('name')->nullable(); // ชื่อผู้จอง
            $table->string('location')->nullable(); // สถานที่
            $table->timestamps(); // created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};