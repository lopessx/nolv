<?php

use App\Models\Category;
use App\Models\Language;
use App\Models\Os;
use App\Models\Product;
use App\Models\Store;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class ProductTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testListProduct() {
		$this->json('GET', '/products')
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testShowProduct() {
		$productId = Product::factory()->create()->getAttribute('id');

		$this->json('GET', '/product/' . $productId)
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testStoreProduct() {
		$this->json('POST', '/product', [
			'storeId' => Store::factory()->create()->getAttribute('id'),
			'categoryId' => Category::factory()->create()->getAttribute('id'),
			'name' => 'Example product',
			'description' => 'Example description',
			'version' => 1,
			'price' => 15,
			'file_path' => '',
			'osId' => Os::factory()->create()->getAttribute('id'),
			'languageId' => Language::factory()->create()->getAttribute('id'),
			'mainImage_path' => '',
		])
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testUpdateProduct() {
		$productId = Product::factory()->create()->getAttribute('id');

		$this->json('PUT', '/product/' . $productId, ['name' => 'Another thing'])
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testDeleteProduct() {
		$productId = Product::factory()->create()->getAttribute('id');

		$this->json('DELETE', '/product/' . $productId)
			->seeJson([
				'success' => true,
			]);
	}
}
