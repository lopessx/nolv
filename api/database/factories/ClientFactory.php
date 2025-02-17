<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ClientFactory extends Factory {
	protected $model = Client::class;

	public function definition(): array {
		return [
			'name' => $this->faker->name,
			'email' => $this->faker->unique()->safeEmail,
			'auth_key' => Hash::make('123'),
			'phone' => $this->faker->phoneNumber,
		];
	}
}
