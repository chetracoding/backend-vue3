<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Onesignal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductCustomize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Auth::user()->store->orders;
        return response()->json(["success" => true, "data" => OrderResource::collection($orders), "message" => "Get all orders successfully."], 200);
    }

    /**
     * Search for orders.
     */
    public function search(string $keyword)
    {
        // Check the user permission
        if (!User::roleRequired('cashier')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        $storeId = Auth::user()->store->id;
        $orders = Order::join('tables', 'orders.table_id', '=', 'tables.id')
            ->select('orders.*')
            ->where('orders.store_id', $storeId)
            ->where('orders.is_paid', false)
            ->where('table_number', 'like', '%' . $keyword . '%')->get();
        if (count($orders) > 0) {
            return response()->json(["success" => true, "data" => OrderResource::collection($orders), "message" => "Search orders is successfully."], 200);
        } else {
            return response()->json(["success" => false, "data" => OrderResource::collection($orders), "message" => "Don't have any order."], 404);
        }
    }

    // Get orders by checking completed to the customer
    public function getByCompelted(bool $is_complete)
    {
        $orders = Auth::user()->store->orders->where('is_completed', '=', $is_complete)->sortByDesc('id');
        $message = 'Get orders not completed are successfully.';
        if ($is_complete) $message = 'Get orders completed are successfully.';
        return Response()->json(['success' => true, 'message' => $message, 'data' => OrderResource::collection($orders)], 200);
    }

    // Get orders by checking paid to the chasier
    public function getByPaid(bool $is_paid)
    {
        $orders = Auth::user()->store->orders->where('is_paid', '=', $is_paid)->sortByDesc('id');
        $message = 'Get orders not paid are successfully.';
        if ($is_paid) $message = 'Get orders paid are successfully.';
        return Response()->json(['success' => true, 'message' => $message, 'data' => OrderResource::collection($orders)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $request['is_completed'] = false;
        $request['is_paid'] = false;
        $newOrder = Order::storeOrder($request);
        foreach ($request->product_customizes as $productCustomize) {
            $proCustomId = $productCustomize['product_customize_id'];
            $quantity = $productCustomize['quantity'];
            $price = ProductCustomize::find($proCustomId)->price;
            // Store order details
            OrderDetail::storeOrderDetail([
                'product_customize_id' => $proCustomId,
                'order_id' => $newOrder->id,
                'quantity' => $quantity,
                'price' => $quantity * $price
            ]);
        }
        // Send notification to OneSignal app
        Onesignal::sendNotifications();
        return response()->json(["success" => true, "data" => new OrderResource($newOrder), "message" => "Create a new order is successfully."], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $checkOrder = Auth::user()->store->orders->contains($id);
        if (!$checkOrder) return Response()->json(['success' => false, 'message' => ['order' => 'Order id is not found.']], 404);
        return Response()->json(['success' => true, 'message' => 'Show order is successfully.', 'data' => new OrderResource(Order::find($id))], 200);
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        $checkOrder = Auth::user()->store->orders->contains($id);
        if (!$checkOrder) return Response()->json(['success' => false, 'message' => ['order' => 'Order id is not found.']], 404);
        return Response()->json(['success' => true, 'message' => 'Update order is successfully.', 'data' => new OrderResource(Order::storeOrder($request, $id))], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
