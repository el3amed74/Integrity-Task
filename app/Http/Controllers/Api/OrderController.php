<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\Contracts\OrderServiceInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderServiceInterface $orderService;
    protected OrderRepositoryInterface $orderRepo;

    public function __construct(OrderServiceInterface $orderService, OrderRepositoryInterface $orderRepo)
    {
        $this->orderService = $orderService;
        $this->orderRepo = $orderRepo;
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->placeOrder($request->customer_email, $request->items);
        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $order = $this->orderRepo->findWithItems($id);
        if (!$order) return response()->json(['message' => 'Not found'], 404);
        return new OrderResource($order);
    }

    public function pay($id)
    {
        $order = $this->orderService->payOrder($id);
        return new OrderResource($order);
    }
}
