<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $filters = $request->only('name');
        $products = $this->products->paginate($filters, $perPage);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $p = $this->products->create($request->validated());
        return new ProductResource($p);
    }

    public function show($id)
    {
        $p = $this->products->find($id);
        if (!$p) return response()->json(['message' => 'Not found'], 404);
        return new ProductResource($p);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $p = $this->products->update($id, $request->validated());
        return new ProductResource($p);
    }

    public function destroy($id)
    {
        $this->products->delete($id);
        return response()->noContent();
    }
}
