<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(StoreProductRequest $request)
    {
        $product = Product::query()->create([
            ...$request->validated(),
            'source' => 'manual',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return $this->success(new ProductResource($product), 'Produto criado com sucesso');
    }

    public function show(Product $product)
    {
        return $this->success(new ProductResource($product));
    }

    public function update(Request $request, Product $product)
    {
        return $this->success(['id' => $product->id], 'Edicao de produto ainda nao implementada');
    }

    public function destroy(Product $product)
    {
        return $this->success(['id' => $product->id], 'Exclusao de produto ainda nao implementada');
    }
}
