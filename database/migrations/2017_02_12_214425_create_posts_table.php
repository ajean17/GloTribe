<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            //$table->float('long');
            //$table->float('lat');
            $table->string('artGroup1')->default('Other');
            $table->string('artGroup2')->nullable();
            $table->string('title');
            $table->string('picture')->nullable();
            $table->text('description');
            $table->string('address');
            $table->dateTime('eventDate');
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
        Schema::dropIfExists('posts');
    }
}
