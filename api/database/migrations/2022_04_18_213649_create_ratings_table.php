<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ratings', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('client_id');
			$table->unsignedBigInteger('product_id');
			$table->string('comment');
			$table->integer('rating');

			$table->timestamps();

			$table->foreign('client_id')
			->references('id')
			->on('clients')
			->cascadeOnDelete()
			->cascadeOnUpdate();

			$table->foreign('product_id')
			->references('id')
			->on('products')
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
		Schema::dropIfExists('ratings');
	}
}
