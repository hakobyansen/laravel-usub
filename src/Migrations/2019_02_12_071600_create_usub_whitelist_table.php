<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsubWhitelistTable extends Migration
{
	public function up()
	{
		Schema::create('usub_whitelist', function (Blueprint $table) {
			$table->increments('id');
			$table->string('ip_address');
			$table->dateTime('created_at');
		});
	}

	public function down()
	{
		Schema::dropIfExists( 'usub_whitelist' );
	}
}
