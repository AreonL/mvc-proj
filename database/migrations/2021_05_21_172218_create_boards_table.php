<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('select1');
            $table->string('select2');
            $table->string('select3');
            $table->string('select4');
            $table->string('select5');
            $table->string('select6');
            $table->string('pair');
            $table->string('twopair');
            $table->string('three');
            $table->string('four');
            $table->string('five');
            $table->string('stairLow');
            $table->string('stairHigh');
            $table->string('house');
            $table->string('chance');
            $table->string('summa');
            $table->string('bonus');
            $table->string('total');
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
        Schema::dropIfExists('boards');
    }
}
