<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GanttAndResourceIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table){
            $table->increments('id');
            $table->string('text');
            $table->integer('duration');
            $table->float('progress');
            $table->dateTime('start_date');
            $table->integer('parent');
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('source');
            $table->integer('target');
            $table->timestamps();
        });

        Schema::table('tasks', function (Blueprint $table){
            $table->string('resources')->nullable();
            $table->string('priority')->nullable();
            $table->dateTime('end_date')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table){
            $table->string('type')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table){
            $table->integer('user_id')->nullable();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('groupName')->nullable();
            $table->timestamps();
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                 ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
