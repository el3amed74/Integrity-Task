<?php

namespace App\Services\Impl;
namespace App\Services\Impl;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Exceptions\OutOfStockException;
use App\Exceptions\AlreadyPaidException;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderService implements OrderServiceInterface
{
    protected OrderRepositoryInterface $orderRepo;
    protected ProductRepositoryInterface $productRepo;

    public function __construct(OrderRepositoryInterface $orderRepo, ProductRepositoryInterface $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function placeOrder(string $customerEmail, array $items): Order
    {
        return DB::transaction(function () use ($customerEmail, $items) {
            $ids = array_column($items, 'product_id');
            $products = $this->productRepo->findForUpdateMany($ids)->keyBy('id');

            $total = 0;
            $itemsData = [];

            foreach ($items as $reqItem) {
                $product = $products->get($reqItem['product_id']);

                if (!$product) {
                    abort(404, "Product {$reqItem['product_id']} not found");
                }

                if ($reqItem['quantity'] > $product->stock) {
                    throw new OutOfStockException("Insufficient stock for product id {$product->id}");
                }

                $subtotal = $product->price * $reqItem['quantity'];

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $reqItem['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                $total += $subtotal;

                // decrement stock (using model)
                $product->decrement('stock', $reqItem['quantity']);
            }

            $orderData = [
                'customer_email' => $customerEmail,
                'status' => 'pending',
                'total' => $total,
            ];

            return $this->orderRepo->create($orderData, $itemsData);
        });
    }

    public function payOrder(int $orderId): Order
    {
        $order = $this->orderRepo->findWithItems($orderId);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->status === 'paid') {
            throw new AlreadyPaidException("Order {$orderId} is already paid.");
        }

        return $this->orderRepo->updateStatus($orderId, 'paid');
    }
}
