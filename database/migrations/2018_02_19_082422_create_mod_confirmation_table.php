<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_confirmation', function (Blueprint $table) {
            $table->increments('modconfirmation_id');
            $table->integer('post_id');
            $table->integer('moderator_id');
            $table->boolean('confirmation')->default(false);
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
        Schema::dropIfExists('mod_confirmation');
    }
}
