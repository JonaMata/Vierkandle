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
        Schema::create('vierkandles', function (Blueprint $table) {
            $table->id();
            $table->string('letters');
            $table->date('date');
            $table->timestamps();
        });

        Schema::create('vierkandle_solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vierkandle_id')->constrained()->onDelete('cascade');
            $table->string('word');
            $table->string('chain');
            $table->boolean('bonus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vierkandle_solutions');
        Schema::dropIfExists('vierkandles');
    }
};
