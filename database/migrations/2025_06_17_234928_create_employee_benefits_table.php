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
        Schema::create('employee_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->string('name'); // Nama benefit, misal: BPJS Kesehatan
            $table->enum('type', ['allowance', 'deduction']); // Jenis: tunjangan/potongan
            $table->float('amount'); // Nilai fixed atau persen
            $table->enum('amount_type', ['fixed', 'percentage']); // Jenis nominal

            $table->boolean('is_taxable')->default(false); // Apakah dikenakan pajak
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_benefits');
    }
};
