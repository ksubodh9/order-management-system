<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryBoy;

class DeliveryBoySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryBoys = [
            ['name' => 'Delivery Boy A', 'capacity' => 2],
            ['name' => 'Delivery Boy B', 'capacity' => 4],
            ['name' => 'Delivery Boy C', 'capacity' => 5],
            ['name' => 'Delivery Boy D', 'capacity' => 3],
        ];

        foreach ($deliveryBoys as $boy) {
            DeliveryBoy::create($boy);
        }
    }
}
