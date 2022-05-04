<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('category_id')->nullable();
			$table->unsignedBigInteger('language_id')->nullable();
			$table->unsignedBigInteger('operational_system_id')->nullable();
			$table->unsignedBigInteger('store_id')->nullable();
			$table->string('name');
			$table->string('main_image_path')->nullable();
			$table->string('file_path')->nullable();
			$table->text('description');
			$table->string('version');
			$table->double('price');

			$table->foreign('category_id')
			->references('id')
			->on('categories')
			->nullOnDelete()
			->cascadeOnUpdate();

			$table->foreign('language_id')
			->references('id')
			->on('languages')
			->nullOnDelete()
			->cascadeOnUpdate();

			$table->foreign('operational_system_id')
			->references('id')
			->on('operational_systems')
			->nullOnDelete()
			->cascadeOnUpdate();

			$table->foreign('store_id')
			->references('id')
			->on('stores')
			->nullOnDelete()
			->cascadeOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('products');
	}
}
