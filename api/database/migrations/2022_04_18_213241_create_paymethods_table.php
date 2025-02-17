<?php

use App\Models\Paymethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymethodsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('paymethods', function (Blueprint $table) {
			$table->id();
			$table->tinyInteger('active');
			$table->string('name');
			$table->string('type');
		});

		Paymethod::insert([
			['active' => 1, 'name' => 'Cartão de crédito - Cielo', 'type' => 'card'],
			['active' => 1, 'name' => 'Boleto bancário', 'type' => 'boleto'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('paymethods');
	}
}
