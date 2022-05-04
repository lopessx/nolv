<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('cards', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('client_id');
			$table->string('token');

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
		Schema::dropIfExists('cards');
	}
}
