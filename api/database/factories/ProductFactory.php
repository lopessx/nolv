<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Language;
use App\Models\Os;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory {
	protected $model = Product::class;

	public function definition(): array {
		return [
			'store_id' => Store::factory()->create()->getAttribute('id'),
			'category_id' => Category::factory()->create()->getAttribute('id'),
			'name' => $this->faker->firstName,
			'description' => 'Example description',
			'version' => $this->faker->numberBetween(1, 3),
			'price' => $this->faker->numberBetween(0, 300),
			'file_path' => '',
			'operational_system_id' => Os::factory()->create()->getAttribute('id'),
			'language_id' => Language::factory()->create()->getAttribute('id'),
			'main_image_path' => '',
		];
	}
}
