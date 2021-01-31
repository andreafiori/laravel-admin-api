<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * @OA\Get(path="/orders",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     *   @OA\Response(response="200",
     *     description="Order Collection",
     *   )
     * )
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        \Gate::authorize('view', 'orders');

        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    /**
     * @OA\Get(path="/orders/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     *   @OA\Parameter(
     *     name="id",
     *     description="Order ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   ),
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     * )
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        \Gate::authorize('view', 'orders');

        return new OrderResource(Order::findOrFail($id));
    }

    /**
     * @OA\Get(path="/export",
     *   security={{"bearerAuth":{}}},
     *   tags={"Orders"},
     *   @OA\Response(response="200",
     *     description="Order Export",
     *   )
     * )
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export()
    {
        \Gate::authorize('view', 'orders');

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $orders = Order::all();
            $file = fopen('php://output', 'w');

            // Header Row
            fputcsv($file, ['ID', 'Name', 'Email', 'Order Title', 'Price', 'Quantity']);

            // Body
            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);

                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }
}
