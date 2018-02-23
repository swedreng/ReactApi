<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModBlockPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_block_post', function (Blueprint $table) {
            $table->increments('mod_block_id');
            $table->integer('post_id');
            $table->integer('block_count')->default(0);
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
        Schema::dropIfExists('mod_block_post');
    }
}
