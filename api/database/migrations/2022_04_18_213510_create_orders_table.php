<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('client_id');
			$table->unsignedBigInteger('paymethod_id');
			$table->unsignedBigInteger('status_id');

			$table->double('total');
			$table->timestamps();

			$table->foreign('client_id')
			->references('id')
			->on('clients')
			->cascadeOnDelete()
			->cascadeOnUpdate();

			$table->foreign('paymethod_id')
			->references('id')
			->on('paymethods')
			->restrictOnDelete()
			->cascadeOnUpdate();

			$table->foreign('status_id')
			->references('id')
			->on('order_status')
			->restrictOnDelete()
			->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('orders');
	}
}
