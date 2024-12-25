<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Paymethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory {
	protected $model = Order::class;

	public function definition(): array {
		return [
			'client_id' => Client::factory()->create()->getAttribute('id'),
			'paymethod_id' => Paymethod::factory()->create()->getAttribute('id'),
			'status_id' => 1,
			'total' => $this->faker->numberBetween(0, 300),
		];
	}
}
