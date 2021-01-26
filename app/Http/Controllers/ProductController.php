<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * @OA\Get(path="/products",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="200",
     *     description="Product Collection",
     *   )
     * )
     */
    public function index()
    {
        \Gate::authorize('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    /**
     * @OA\Get(path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function show($id)
    {
        \Gate::authorize('view', 'products');

        return new ProductResource(Product::findOrFail($id));
    }

    /**
     * @OA\Post(
     *   path="/products",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="201",
     *     description="Product Create",
     *   )
     * )
     */
    public function store(ProductCreateRequest $request)
    {
        \Gate::authorize('edit', 'products');

        $product = Product::create($request->only('image', 'title', 'description', 'price'));

        return response($product, Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *   path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="202",
     *     description="Product Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        \Gate::authorize('edit', 'products');

        $product = Product::find($id);

        $product->update($request->only('image', 'title', 'description', 'price'));

        return response($product, Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(path="/products/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Products"},
     *   @OA\Response(response="204",
     *     description="Product Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Product ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function destroy($id)
    {
        \Gate::authorize('edit', 'products');

        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
