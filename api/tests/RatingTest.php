<?php

use App\Models\Client;
use App\Models\Product;
use App\Models\Rating;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class RatingTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testListRatings() {
		$productId = Product::factory()->create()->getAttribute('id');

		$this->json('GET', '/ratings/product/' . $productId)
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testShowRating() {
		$rating = Rating::factory()->create();
		$productId = $rating->getAttribute('product_id');
		$clientId = $rating->getAttribute('client_id');

		$this->json('GET', '/rating/product/' . $productId . '/client/' . $clientId . '')
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testStoreRating() {
		$this->json('POST', '/rating', [
			'clientId' => Client::factory()->create()->getAttribute('id'),
			'productId' => Product::factory()->create()->getAttribute('id'),
			'comment' => 'Comment example',
			'rating' => 5,
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
	public function testUpdateRating() {
		$ratingId = Rating::factory()->create()->getAttribute('id');

		$this->json('PUT', '/rating/' . $ratingId . '', ['comment' => 'Another comment'])
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testDeleteRating() {
		$ratingId = Rating::factory()->create()->getAttribute('id');


		$this->json('DELETE', '/rating/' . $ratingId . '')
			->seeJson([
				'success' => true,
			]);
	}
}
