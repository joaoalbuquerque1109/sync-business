<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%' . $request->string('search') . '%'))
            ->paginate((int) $request->integer('per_page', 15));

        return $this->success(ProductResource::collection($products), meta: [
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->success($request->all(), 'Endpoint de criação de produto pronto para implementação completa');
    }

    public function show(Product $product)
    {
        return $this->success(new ProductResource($product));
    }

    public function update(Request $request, Product $product)
    {
        return $this->success(['id' => $product->id], 'Endpoint de edição de produto pronto para implementação completa');
    }

    public function destroy(Product $product)
    {
        return $this->success(['id' => $product->id], 'Endpoint de exclusão de produto pronto para implementação completa');
    }
}
