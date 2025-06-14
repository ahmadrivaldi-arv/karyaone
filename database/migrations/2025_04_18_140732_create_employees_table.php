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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('code')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->foreignId('position_id')
                ->nullable()->constrained('positions')->nullOnDelete();
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'resigned', 'terminated'])->default('active');
            $table->integer('base_salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
