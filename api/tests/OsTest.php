<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class OsTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * Test os list
	 *
	 * @return void
	 */
	public function testListOs() {
		$this->json('GET', '/os')
			->seeJson([
				'success' => true,
			]);
	}
}
