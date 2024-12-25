<?php

use App\Models\Client;
use App\Models\Order;
use App\Models\Paymethod;
use App\Models\Product;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class OrderTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * List orders
	 *
	 * @return void
	 */
	public function testListOrders() {
		$this->json('GET', '/order')
		->seeJson([
			'success' => true,
		]);
	}

	/**
	 * Get client orders
	 *
	 * @return void
	 */
	public function testListClientOrders() {
		$clientId = Order::factory()->create()->getAttribute('client_id');


		$this->json('GET', '/orders/client/' . $clientId)
		->seeJson([
			'success' => true,
		]);
	}

	/**
	 * Save new order
	 *
	 * @return void
	 */
	public function testSaveOrder() {
		$products = ['id' => Product::factory()->create()->getAttribute('id')];

		$this->json('POST', '/order', ['total' => 100, 'paymethodId' => Paymethod::factory()->create()->getAttribute('id'), 'clientId' => Client::factory()->create()->getAttribute('id'), 'products' => [$products]])
		->seeJson([
			'success' => true,
		]);
	}

	/**
	 * Update order
	 *
	 * @return void
	 */
	public function testUpdateOrder() {
		$orderId = Order::factory()->create()->getAttribute('id');

		$this->json('PUT', '/order/' . $orderId, ['total' => 200])
		->seeJson([
			'success' => true,
		]);
	}

	/**
	 * Delete order
	 *
	 * @return void
	 */
	public function testDeleteOrder() {
		$orderId = Order::factory()->create()->getAttribute('id');


		$this->json('DELETE', '/order/' . $orderId)
		->seeJson([
			'success' => true,
		]);
	}
}
