<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('patients_id'); // Foreign Key: References `id` in `patients` table
            $table->unsignedBigInteger('med_id'); // Foreign Key: References `id` in `medicines` table
            $table->date('prescription_date');
            $table->integer('qty_taken'); // Quantity of medicines taken
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patients_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('med_id')->references('id')->on('medicines')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
}
