<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_user_id');
            $table->integer('receiver_user_id');
            $table->integer('model_item_id');
            $table->integer('project_id');
            $table->boolean('receiver_registered');
            $table->boolean('can_write');
            $table->string('receiver_email');
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
        Schema::dropIfExists('share_items');
    }
}
