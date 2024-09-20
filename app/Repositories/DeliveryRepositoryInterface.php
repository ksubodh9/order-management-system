<?php

namespace App\Repositories;

interface DeliveryRepositoryInterface
{
    public function assignOrderToDeliveryBoy($orderId);
    public function getAvailableDeliveryBoy();
    public function isDeliveryBoyFree($deliveryBoyId);
}
