<?php

use App\Models\Order;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class PaymethodTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * List paymethods
	 *
	 * @return void
	 */
	public function testListPaymethod() {
		$this->json('GET', '/payment/list')
			->seeJson([
				'success' => true,
			]);
	}

	/**
	 * Capture payment
	 *
	 * @return void
	 */
	public function testCapturePayment() {
		$orderId = Order::factory()->create()->getAttribute('id');

		$this->json('POST', '/payment/capture/' . $orderId, ['paymentData' => ['card' => ['name' => 'Joh Doe', 'number' => '5173 8090 4187 2031', 'cvv' => '123', 'expDate' => '10/24']]])
			->seeJson([
				'success' => true,
			]);
	}
}
