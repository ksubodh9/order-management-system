<?php

namespace App\Repositories\Eloquent;

use App\Models\DeliveryBoy;
use App\Models\Order;
use App\Repositories\DeliveryRepositoryInterface;
use Carbon\Carbon;

class DeliveryRepository implements DeliveryRepositoryInterface
{
    protected $deliveryBoy;

    /**
     * Create a new DeliveryRepository instance.
     *
     * @param DeliveryBoy $deliveryBoy
     */
    public function __construct(DeliveryBoy $deliveryBoy)
    {
        $this->deliveryBoy = $deliveryBoy;
    }

    /**
     * Assign an order to an available delivery boy.
     *
     * @param int $orderId ID of the order to assign.
     * @return array An array with 'status' and 'message'.
     * @throws \Exception
     */
    public function assignOrderToDeliveryBoy($orderId)
    {
        // Validate the order exists in the 'orders' table
        $order = Order::find($orderId);
        if (!$order) {
            return [
                'status' => false,
                'message' => "The order with this ID $orderId does not exist."
            ];
        }

        // Get available delivery boy
        $deliveryBoyResponse = $this->getAvailableDeliveryBoy();

        if ($deliveryBoyResponse['status'] === true) {
            $deliveryBoy = $deliveryBoyResponse['data'];

            // Check if the order has already been assigned to avoid duplicates
            if (!$deliveryBoy->orders()->where('order_id', $orderId)->exists()) {
                try {
                    // Attach the order with timestamps
                    $deliveryBoy->orders()->attach($orderId, ['created_at' => now(), 'updated_at' => now()]);

                    // Return success message
                    return [
                        'status' => true,
                        'message' => 'This order has been successfully assigned to ' . $deliveryBoy->name
                    ];

                } catch (\Illuminate\Database\QueryException $e) {
                    // Handle foreign key constraint violation or other DB errors
                    return [
                        'status' => false,
                        'message' => "Failed to assign order ID $orderId: " . $e->getMessage()
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'This order has already been assigned to ' . $deliveryBoy->name
                ];
            }
        } else {
            return $deliveryBoyResponse; // Return the failure message for unavailable delivery boys
        }
    }

    /**
     * Get an available delivery boy.
     *
     * @return array Status and message, or delivery boy data.
     */
    public function getAvailableDeliveryBoy()
    {
        $deliveryBoys = $this->deliveryBoy->all();

        foreach ($deliveryBoys as $boy) {
            $isFreeResponse = $this->isDeliveryBoyFree($boy->id);

            if ($isFreeResponse['status'] === true) {
                return [
                    'status' => true,
                    'message' => 'Delivery boy is available.',
                    'data' => $boy
                ];
            }
        }

        return [
            'status' => false,
            'message' => 'There are no available delivery boys. Please try after some time.'
        ];
    }

    /**
     * Check if a delivery boy is free to take an order.
     *
     * @param int $deliveryBoyId ID of the delivery boy to check.
     * @return array Status and message.
     * @throws \Exception
     */
    public function isDeliveryBoyFree($deliveryBoyId)
    {
        $boy = $this->deliveryBoy->find($deliveryBoyId);

        if (!$boy) {
            return [
                'status' => false,
                'message' => 'Delivery boy does not exist.'
            ];
        }

        // Get the count of orders assigned to this delivery boy within the last 30 minutes
        $activeOrdersCount = $boy->orders()
            ->wherePivot('created_at', '>=', Carbon::now()->subMinutes(30)) // Check assignment time in the pivot table
            ->count();

        // The delivery boy is free if the number of active orders is less than their capacity
        if ($activeOrdersCount < $boy->capacity) {
            return [
                'status' => true,
                'message' => 'The delivery boy is available.'
            ];
        } else {
            return [
                'status' => false,
                'message' => 'The delivery boy is not available.'
            ];
        }
    }
}
