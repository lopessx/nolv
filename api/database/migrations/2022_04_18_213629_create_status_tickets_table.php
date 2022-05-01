<?php

use App\Models\StatusTicket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTicketsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('status_tickets', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->onUpdate('cascade');
			$table->onDelete('cascade');
		});

		StatusTicket::insert([
			['id' => '1', 'name' => 'Aberto'],
			['id' => '2', 'name' => 'Respondido'],
			['id' => '3', 'name' => 'Concluído'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('status_tickets');
	}
}
