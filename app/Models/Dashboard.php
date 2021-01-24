<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    public static function findOrderForChart()
    {
        $rawSelect = "DATE_FORMAT(orders.created_at, '%Y-%m-%d') as date, SUM(order_items.quantity*order_items.price) as sum";
        if (\DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $rawSelect = "strftime('%Y %m %d', orders.created_at) as date, SUM(order_items.quantity*order_items.price) as sum";
        }

        $orders = Order::query()
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->selectRaw($rawSelect)
                    ->groupBy('date')
                    ->get();

        return $orders;
    }
}
