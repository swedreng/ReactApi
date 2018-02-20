<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_confirmation', function (Blueprint $table) {
            $table->increments('postconfirmation_id');
            $table->integer('post_id');
            $table->integer('confirmation_count')->default(0);
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
        Schema::dropIfExists('post_confirmation');
    }
}
