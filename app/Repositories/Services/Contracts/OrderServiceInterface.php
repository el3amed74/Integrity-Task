<?php

namespace App\Services\Contracts;

use App\Models\Order;

interface OrderServiceInterface
{
    public function placeOrder(string $customerEmail, array $items): Order;
    public function payOrder(int $orderId): Order;
}
