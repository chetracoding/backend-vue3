<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoneyReportController extends Controller
{
    /**
     * Get money reports by year.
     */
    public function moneyReport(int $year)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        $storeId = Auth::user()->store->id;
        // Reference: https://poe.com/s/gWJaWopDGVeEqnJNd4BI
        $totalMoneyReports = DB::table('orders')
            ->join('stores', 'orders.store_id', '=', 'stores.id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('product_customizes', 'order_details.product_customize_id', '=', 'product_customizes.id')
            ->select(DB::raw('MONTH(orders.datetime) as month'), DB::raw('YEAR(orders.datetime) as year'), DB::raw('SUM(order_details.price) as total_money'))
            // Check store id from the user
            ->where('orders.store_id', $storeId)
            // Check order already paid
            ->where('orders.is_paid', true)
            // Check year
            ->whereYear('orders.datetime', '=', $year)
            ->groupBy(DB::raw('MONTH(orders.datetime)'), DB::raw('YEAR(orders.datetime)'))
            ->get();
        return response()->json(["success" => true, "data" => $totalMoneyReports, "message" => "Get money reports is successfully."], 200);
    }
}
