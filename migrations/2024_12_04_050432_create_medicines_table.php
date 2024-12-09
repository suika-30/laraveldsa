<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinesTable extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name');
            $table->enum('category', ['Anti-hypertensive', 'Antithrombotic', 'Lipid Modifying Agent', 'Oral Hypoglycemic Agent']);
            $table->string('dosage'); // Example: "5mg tablet"
            $table->integer('qty'); // Quantity
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
}
