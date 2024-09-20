<?php

namespace Database\Factories;
use App\Models\Order;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_ref_id' => $this->faker->unique()->numerify('ORDER-#############'),      
            'order_details' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ];
    }
}
