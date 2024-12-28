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
        Schema::table('vierkandle_solves', function (Blueprint $table) {
            $table->enum('solve_state', ['unsolved', 'solved', 'perfect'])->after('mistakes')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vierkandle_solves', function (Blueprint $table) {
            $table->dropColumn('perfect');
        });
    }
};
