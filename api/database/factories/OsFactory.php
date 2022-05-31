<?php

namespace Database\Factories;

use App\Models\Os;
use Illuminate\Database\Eloquent\Factories\Factory;

class OsFactory extends Factory {
	protected $model = Os::class;

	public function definition(): array {
		return [
			'name' => $this->faker->firstName,
		];
	}
}
