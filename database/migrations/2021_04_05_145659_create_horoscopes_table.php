<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoroscopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscopes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('overall_score', 10);
            $table->text('overall', 10);
            $table->string('love_score', 10);
            $table->text('love', 10);
            $table->string('career_score', 10);
            $table->text('career', 10);
            $table->string('wealth_score', 10);
            $table->text('wealth', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horoscopes');
    }
}
