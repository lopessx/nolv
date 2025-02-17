<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Store;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory {
	protected $model = Ticket::class;

	public function definition(): array {
		return [
			'client_id' => Client::factory()->create()->getAttribute('id'),
			'store_id' => Store::factory()->create()->getAttribute('id'),
			'status_ticket_id' => 1,
			'message' => $this->faker->safeColorName(),
		];
	}
}
