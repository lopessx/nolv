<?php

use App\Models\Os;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationalSystemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('operational_systems', function (Blueprint $table) {
			$table->id();
			$table->string('name');
		});

		Os::insert([
			['name' => 'Linux'],
			['name' => 'MacOs'],
			['name' => 'Windows'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('operational_systems');
	}
}
