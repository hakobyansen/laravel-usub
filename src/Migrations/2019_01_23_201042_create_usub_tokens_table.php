<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsubTokensTable extends Migration
{
    public function up()
    {
        Schema::create('usub_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user1')->unsigned();
            $table->bigInteger('user2')->unsigned();
            $table->string('token', 5000);
            $table->string('redirect_to', 5000);
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists( 'usub_tokens' );
    }
}
