<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;

class LanguageTest extends TestCase {
	use DatabaseMigrations;
	use WithoutMiddleware;

	/**
	 * Test languages list
	 *
	 * @return void
	 */
	public function testListLanguages() {
		$this->json('GET', '/languages')
			->seeJson([
				'success' => true,
			]);
	}
}
