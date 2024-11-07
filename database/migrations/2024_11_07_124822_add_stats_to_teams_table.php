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
        Schema::table('teams', function (Blueprint $table) {
            $table->integer('points')->default(0);
            $table->integer('goals_scored')->default(0);
            $table->integer('goals_conceded')->default(0);
            $table->integer('goal_difference')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['points', 'goals_scored', 'goals_conceded', 'goal_difference']);
        });
    }
};
