<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemembermeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rememberme', function (Blueprint $table) {
            $table->increments('rememberme_id');
            $table->string('username');
            $table->string('password');
            $table->string('rememberme_token');
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
        Schema::dropIfExists('rememberme');
    }
}
