<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsubTokensTable extends Migration
{
    public function up()
    {
        Schema::create('usub_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user1')->unsigned();
            $table->integer('user2')->unsigned();
            $table->string('token');
            $table->string('redirect_to');
            $table->dateTime('expires_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists( 'usub_tokens' );
    }
}