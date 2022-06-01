<?php

use App\Models\Client;
use App\Models\Store;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class StoreTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testListStore() {
		$clientId = Store::factory()->create()->getAttribute('client_id');

		$this->json('GET', '/store/client/' . $clientId)
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testStoreStore() {
		$this->json('POST', '/store', [
			'clientId' => Client::factory()->create()->getAttribute('id'),
			'name' => 'Example co.',
			'balance' => 10,
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
	public function testUpdateStore() {
		$storeId = Store::factory()->create()->getAttribute('id');


		$this->json('PUT', '/store/' . $storeId)
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testDeleteStore() {
		$storeId = Store::factory()->create()->getAttribute('id');

		$this->json('DELETE', '/store/' . $storeId)
			->seeJson([
				'success' => true,
			]);
	}
}
