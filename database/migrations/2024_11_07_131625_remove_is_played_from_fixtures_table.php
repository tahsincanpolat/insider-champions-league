<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropColumn('is_played');
        });
    }

    public function down()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->boolean('is_played')->default(false);
        });
    }
};
