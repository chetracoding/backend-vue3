<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ShowProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCustomize;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // Check the user permission
    if (
      !User::roleRequired('restaurant_owner') &&
      !User::roleRequired('waiter')
    ) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    if (Auth::user()->role->name === 'waiter') {
      $products = Auth::user()->store->products->where('is_active', true)->sortByDesc('id');
    } else {
      $products = Auth::user()->store->products->sortByDesc('id');
    }
    return response()->json(["success" => true, "data" => ShowProductResource::collection($products), "message" => "Get all products is successfully."], 200);
  }

  /**
   * Search for product.
   */
  public function search(string $keyword)
  {
    // Check the user permission
    if (
      !User::roleRequired('restaurant_owner') &&
      !User::roleRequired('waiter')
    ) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    $storeId = Auth::user()->store->id;
    // Search products by restaurant owner
    if (Auth::user()->role->name === 'restaurant_owner') {
      $products = Product::where('store_id', $storeId)
        ->where('name', 'like', '%' . $keyword . '%')
        ->orWhere('product_code', 'like', '%' . $keyword . '%')->get();
    } else {
      // Search products by waiter
      $products = Product::where('store_id', $storeId)
        ->where(function ($query) use ($keyword) {
          $query->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('product_code', 'like', '%' . $keyword . '%')->get();
        })
        ->where('is_active', true)
        ->get();
    }
    return response()->json(["success" => true, "data" => ShowProductResource::collection($products), "message" => "Search products is successfully."], 200);
  }

  /**
   * Filter products by category id.
   */
  public function filter(string $category_id)
  {
    // Check the user permission
    if (
      !User::roleRequired('restaurant_owner') &&
      !User::roleRequired('waiter')
    ) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    $storeId = Auth::user()->store->id;
    // Filter products by restaurant owner
    if (Auth::user()->role->name === 'restaurant_owner') {
      $products = Product::where('store_id', $storeId)
        ->where('category_id', '=', $category_id)->get();
    } else {
      // Filter products by waiter
      $products = Product::where('store_id', $storeId)
        ->where('is_active', true)
        ->where('category_id', '=', $category_id)->get();
    }
    if (count($products) > 0) {
      return response()->json(["success" => true, "data" => ShowProductResource::collection($products), "message" => "Filter products is successfully."], 200);
    } else {
      return response()->json(["success" => false, "data" => ShowProductResource::collection($products), "message" => "Don't have any product."], 404);
    }
  }

  /**
   * Get popular products in store.
   */
  public function popular()
  {
    // Check the user permission
    if (
      !User::roleRequired('restaurant_owner') &&
      !User::roleRequired('waiter')
    ) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    return response()->json(["success" => true, "data" => Product::popularProducts(), "message" => "Get popular products is successfully."], 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateProductRequest $request)
  {
    // Check the user permission
    if (!User::roleRequired('restaurant_owner')) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    if (!Category::contains('id', $request->category_id)) {
      return response()->json(['success' => false, 'message' => "The category id is not found."], 404);
    } else {
      // Check product code in store
      if (Product::contains('product_code', $request->product_code)) {
        return response()->json(['success' => false, 'message' => ['product_code' => "The product's product code already exists."]], 409);
      } else {
        // Create new product -------------------
        $product = Product::storeProduct($request);

        // Create customizes for a product ---------
        foreach ($request->product_customizes as $customize) {
          $customize['product_id'] = $product['id'];
          ProductCustomize::store($customize);
        }
        return response()->json(['success' => true, 'data' => new ShowProductResource($product), 'message' => "Created a new product is sucessfully."], 200);
      }
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(CreateProductRequest $request, $id)
  {
    // Check the user permission
    if (!User::roleRequired('restaurant_owner')) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    if (!Product::contains('id', $id)) {
      return response()->json(["success" => false, "message" => ["product" => "The product id is not found."]], 404);
    } elseif (!Category::contains('id', $request->category_id)) {
      return response()->json(['success' => false, 'message' => ["category" => "The category id is not found."]], 404);
    } else {
      $productsInCategory = Category::find($request->input('category_id'))->products->whereNotIn('id', [$id]);
      foreach ($productsInCategory as $productInCategory) {
        if (strtoupper($productInCategory->product_code) === strtoupper($request->input('product_code'))) {
          return response()->json(['success' => false, 'message' => ['product_code' => "Code already exists."]], 409);
        }
      }
      // Update the product attributes
      $product = Product::storeProduct($request, $id);
      // Update the product_customize relationship
      foreach ($request->product_customizes as $customize) {
        if (isset($customize['product_customize_id'])) {
          $customizeId = $customize['product_customize_id'];
          $customizesFromDB = $product->productCustomizes;
          if ($customizesFromDB->where('id', $customizeId)->first()) {
            $customize['product_id'] = $id;
            ProductCustomize::store($customize, $customizeId);
          } else {
            return response()->json(['success' => false, 'message' => ["product_customize" => "The product_customize id " . $id . " does not exist in product id " . $id . '.']], 409);
          }
        } else {
          $customize['product_id'] = $id;
          ProductCustomize::store($customize);
        }
      }
      return response()->json(['success' => true, 'data' => new ShowProductResource($product), 'message' => ["product" => "Updated the product is successfully."]], 200);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(int $id)
  {
    // Check the user permission
    if (!User::roleRequired('restaurant_owner')) {
      return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
    }
    if (Product::contains('id', $id)) {
      Product::find($id)->delete();
      return Response()->json(['success' => true, 'message' => 'Delete the product is successfully.'], 200);
    } else {
      return Response()->json(['success' => false, 'message' => ['product' => 'The product id is not found.']], 404);
    }
  }
}
