<?php

use App\Models\Client;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class ClientTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testRegister() {
		$this->json('POST', '/client/register', ['name' => 'John Doe', 'email' => 'testmail@gmail.com', 'phone' => '(81) 99343 1392'])
		->seeJson([
			'success' => true,
		]);
	}

	public function testLogin() {
		$clientEmail = Client::factory()->create()->getAttribute('email');

		$this->json('POST', '/client/login', ['email' => $clientEmail, 'code' => '123'])
		->seeJson([
			'success' => true,
		]);
	}

	public function testLogout() {
		$clientEmail = Client::factory()->create()->getAttribute('email');

		$this->json('POST', '/logout', ['email' => $clientEmail])
			->seeJson([
				'success' => true,
			]);
	}

	public function testUpdate() {
		$clientId = Client::factory()->create()->getAttribute('id');


		$this->json('PUT', '/client/update/' . $clientId, ['name' => 'Susan Doe'])
			->seeJson([
				'success' => true,
			]);
	}

	public function testDelete() {
		$clientId = Client::factory()->create()->getAttribute('id');


		$this->json('DELETE', '/client/delete/' . $clientId)
			->seeJson([
				'success' => true,
			]);
	}
}
