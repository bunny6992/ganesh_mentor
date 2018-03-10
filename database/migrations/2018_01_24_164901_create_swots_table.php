<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('swots', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->text('title');
        //     $table->text('strength');
        //     $table->text('weakness');
        //     $table->text('opportunities');
        //     $table->text('threats');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('swots');
    }
}
