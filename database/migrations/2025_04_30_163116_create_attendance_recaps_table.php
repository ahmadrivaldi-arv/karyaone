<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_recaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('present_days')->default(0);
            $table->integer('late_days')->default(0);
            $table->integer('sick_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('total_work_days')->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'period_start', 'period_end'], 'unique_recap_per_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_recaps');
    }
};
