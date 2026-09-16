<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indus_gas_customers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('phone', 13);
            $table->string('type', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indus_gas_customers');
    }
};
