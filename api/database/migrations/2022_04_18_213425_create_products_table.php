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
			$table->unsignedBigInteger('category_id');
			$table->unsignedBigInteger('language_id');
			$table->unsignedBigInteger('operational_system_id');
			$table->unsignedBigInteger('store_id');
			$table->foreign('category_id')->references('id')->on('categories');
			$table->foreign('language_id')->references('id')->on('languages');
			$table->foreign('operational_system_id')->references('id')->on('operational_systems');
			$table->foreign('store_id')->references('id')->on('stores');
			$table->string('name');
			$table->string('main_image_path');
			$table->string('file_path');
			$table->text('description');
			$table->string('version');
			$table->double('price');
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
