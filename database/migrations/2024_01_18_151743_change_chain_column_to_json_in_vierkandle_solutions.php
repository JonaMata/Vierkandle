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
        \App\Models\VierkandleSolution::all()->each(function (\App\Models\VierkandleSolution $solution) {
            $solution->chain = array_map(fn($x) => intval($x), explode(',', $solution->getRawOriginal('chain')));
            $solution->save();
        });
        Schema::table('vierkandle_solutions', function (Blueprint $table) {
            $table->json('chain')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vierkandle_solutions', function (Blueprint $table) {
            $table->string('chain')->change();
        });
        \App\Models\VierkandleSolution::all()->each(function (\App\Models\VierkandleSolution $solution) {
            $solution->setRawAttributes(['chain' => implode(',', $solution->chain)]);
            $solution->save();
        });
    }
};
