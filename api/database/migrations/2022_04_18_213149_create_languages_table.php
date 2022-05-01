<?php

use App\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('languages', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->onUpdate('cascade');
			$table->onDelete('cascade');
		});

		Language::insert([
			['name' => 'Espanhol'],
			['name' => 'Inglês'],
			['name' => 'Português'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('languages');
	}
}
