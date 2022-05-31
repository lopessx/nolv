<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory {
	protected $model = Rating::class;

	public function definition(): array {
		return [
			'client_id' => Client::factory()->create()->getAttribute('id'),
			'product_id' => Product::factory()->create()->getAttribute('id'),
			'comment' => $this->faker->safeColorName(),
			'rating' => $this->faker->numberBetween(1, 5),
		];
	}
}
