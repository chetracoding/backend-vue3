<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'product_code',
        'description',
        'image',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Store or Update product
    public static function storeProduct($request, $id = null)
    {
        $product = $request->only(['category_id', 'name', 'product_code', 'description', 'image', 'is_active']);
        $product['store_id'] = Auth::user()->store->id;
        $product = self::updateOrCreate(['id' => $id], $product);
        return $product;
    }

    // Reference: https://poe.com/s/9ulpWtTxukJrKzS6YVZ5
    // Reference: https://poe.com/s/Z1U4pn0rFeXBj2Vcle53
    // Get popular products in store 
    public static function popularProducts()
    {   
        $storeId = Auth::user()->store->id;
        $popularProducts = self::select(
            'products.id',
            'products.store_id',
            'products.name',
            'products.description',
            'products.product_code',
            'products.category_id',
            'products.image',
            'products.is_active',
            DB::raw('SUM(order_details.quantity) as order_count'))
            ->join('product_customizes', 'products.id', '=', 'product_customizes.product_id')
            ->join('order_details', 'product_customizes.id', '=', 'order_details.product_customize_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('products.id', 'products.store_id', 'products.name', 'products.description', 'products.product_code', 'products.category_id', 'products.image', 'products.is_active')
            ->where('products.store_id', $storeId)
            ->where('products.is_active', true)
            ->orderByDesc('order_count')
            ->limit(10)
            ->with('productCustomizes:id as product_customize_id,product_id,size,price')
            ->get();
        return $popularProducts;
    }

    // Check product exists in store 
    public static function contains($field, $value)
    {
        return Auth::user()->store->products->contains($field, $value);
    }

    public function productCustomizes(): HasMany
    {
        return $this->hasMany(ProductCustomize::class);
    }

    public function orderDetails(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
