<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('patient_id')->unique(); // Unique identifier for patients
            $table->string('name');
            $table->date('birthdate');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('address');
            $table->string('ailment');
            $table->date('date_registered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
}
