<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartResource;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    /**
     * @OA\Get(path="/chart",
     *   security={{"bearerAuth":{}}},
     *   tags={"Dashboard"},
     *   @OA\Response(response="200",
     *     description="Dashboard chart graph",
     *   )
     * )
     */
    public function chart()
    {
        \Gate::authorize('view', 'orders');

        $orders = Dashboard::findOrderForChart();

        return ChartResource::collection($orders);
    }
}
