<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tickets', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('client_id');
			$table->unsignedBigInteger('store_id');
			$table->unsignedBigInteger('status_ticket_id');
			$table->foreign('client_id')->references('id')->on('clients');
			$table->foreign('store_id')->references('id')->on('stores');
			$table->foreign('status_ticket_id')->references('id')->on('status_tickets');
			$table->string('message');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tickets');
	}
}
