<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory {
	protected $model = Store::class;

	public function definition(): array {
		return [
			'client_id' => Client::factory()->create()->getAttribute('id'),
			'name' => $this->faker->firstName,
			'balance' => $this->faker->numberBetween(0, 300),
		];
	}
}
