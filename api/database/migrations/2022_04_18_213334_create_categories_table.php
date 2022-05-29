<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categories', function (Blueprint $table) {
			$table->id();
			$table->string('name');
		});

		Category::insert([
			['name' => 'Comunicação'],
			['name' => 'Design'],
			['name' => 'Entretenimento'],
			['name' => 'Ferramentas'],
			['name' => 'Finanças'],
			['name' => 'Produtividade'],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('categories');
	}
}
