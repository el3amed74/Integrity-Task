<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function create(array $orderData, array $itemsData): Order;
    public function findWithItems(int $id): ?Order;
    public function updateStatus(int $id, string $status): Order;
}
