<?php

namespace Database\Factories;

use App\Models\Paymethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymethodFactory extends Factory {
	protected $model = Paymethod::class;

	public function definition(): array {
		return [
			'name' => $this->faker->firstName,
			'type' => 'card',
			'active' => 1,
		];
	}
}
