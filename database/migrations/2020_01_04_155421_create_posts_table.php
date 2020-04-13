<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('title')->nullable();
            $table->longText('content');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('post_name')->nullable();
            $table->unsignedBigInteger('banner')->nullable();
            $table->foreign('banner')->references('id')
                ->on('uploads')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('post_status');

            $table->unsignedBigInteger('visit');
            $table->timestamps();
            $table->softDeletes();
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
