<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWritingInPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('writing',400)->change();
        });
    }
    
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('writing')->change();
        });
    } 
}
