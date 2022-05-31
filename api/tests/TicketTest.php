<?php

use App\Models\Client;
use App\Models\Store;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class TicketTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testStoreTicket() {
		$this->json('POST', '/ticket', [
			'clientId' => Client::factory()->create()->getAttribute('id'),
			'storeId' => Store::factory()->create()->getAttribute('id'),
			'message' => 'Any comment support',
		])
			->seeJson([
				'success' => true,
			]);
	}
}
