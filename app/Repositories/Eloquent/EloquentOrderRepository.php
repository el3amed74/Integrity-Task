<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function create(array $orderData, array $itemsData): Order
    {
        $order = Order::create($orderData);
        foreach ($itemsData as $item) {
            $order->items()->create($item);
        }
        return $order->fresh('items');
    }

    public function findWithItems(int $id): ?Order
    {
        return Order::with('items.product')->find($id);
    }

    public function updateStatus(int $id, string $status): Order
    {
        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();
        return $order;
    }
}
