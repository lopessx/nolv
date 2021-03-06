<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('stores', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('client_id');
			$table->double('balance');
			$table->string('name');


			$table->foreign('client_id')
			->references('id')
			->on('clients')
			->cascadeOnDelete()
			->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('stores');
	}
}
