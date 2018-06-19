<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('contents_id');
            $table->integer('user_id')->unsigned();
            $table->string('title', 200);
            $table->text('writing1')->nullable();
            $table->text('writing2')->nullable();
            $table->text('writing3')->nullable();
            $table->text('writing4')->nullable();
            $table->text('writing5')->nullable();
            $table->text('writing6')->nullable();
            $table->text('writing7')->nullable();
            $table->text('writing8')->nullable();
            $table->text('writing9')->nullable();
            $table->text('writing10')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('image6')->nullable();
            $table->string('image7')->nullable();
            $table->string('image8')->nullable();
            $table->string('image9')->nullable();
            $table->string('image10')->nullable();
            $table->integer('like')->default(0);
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
