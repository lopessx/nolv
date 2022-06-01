<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class CategoryTest extends TestCase {
	use DatabaseMigrations;

	/**
	 * Test categories list
	 *
	 * @return void
	 */
	public function testListCategories() {
		$this->json('GET', '/categories')
			->seeJson([
				'success' => true,
			]);
	}
}
