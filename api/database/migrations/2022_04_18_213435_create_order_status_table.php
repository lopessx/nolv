<?php

use App\Models\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('order_status', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->onUpdate('cascade');
			$table->onDelete('cascade');
		});

		OrderStatus::insert([
			['id' => '1', 'name' => 'Pendente'],
			['id' => '2', 'name' => 'Cancelado'],
			['id' => '3', 'name' => 'Abandonado'],
			['id' => '4', 'name' => 'Concluído'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('order_status');
	}
}
