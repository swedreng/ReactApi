<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postspicture', function (Blueprint $table) {
            $table->increments('postpicture_id');
            $table->integer('id')->nullable()->unsigned();
            $table->string('writing');
            $table->string('image')->nullable();
            $table->string('kind');
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
        Schema::dropIfExists('postspicture');
    }
}
