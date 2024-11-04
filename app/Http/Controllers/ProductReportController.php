<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    /**
     * Get product reports by month and year.
     */
    public function productReport(int $month, int $year)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        // Reference: https://poe.com/s/RowjYps9KwNltWSLMGlD
        $storeId = Auth::user()->store->id;
        $totalProductReports = DB::table('orders')
            ->join('stores', 'orders.store_id', '=', 'stores.id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('product_customizes', 'order_details.product_customize_id', '=', 'product_customizes.id')
            ->join('products', 'product_customizes.product_id', '=', 'products.id')
            // Select products and sum total orders for each product
            ->select('products.name as product_name', DB::raw('SUM(order_details.quantity) as total_orders'))
            // Check store id from the user
            ->where('orders.store_id', $storeId)
            // Check order already paid
            ->where('orders.is_paid', true)
            // Check year
            ->whereYear('datetime', '=', $year)
            // Check month
            ->whereMonth('datetime', '=', $month)
            ->groupBy('products.name')
            ->orderBy('total_orders', 'desc')
            ->get();
        return response()->json(["success" => true, "data" => $totalProductReports, "message" => "Get product reports is successfully."], 200);
    }
}
