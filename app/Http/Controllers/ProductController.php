<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        return new ProductResource(Product::find($id));
    }

    public function store(ProductCreateRequest $request)
    {
        $product = Product::create($request->only('image', 'title', 'description', 'price'));

        return response($product, Response::HTTP_CREATED);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::find($id);

        $product->update($request->only('image', 'title', 'description', 'price'));

        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
