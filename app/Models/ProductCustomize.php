<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCustomize extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'price'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Store or Update product customize
    public static function store($request, $id = null)
    {
        $productCustomize = [
            'product_id' => $request['product_id'],
            'size' => $request['size'],
            'price' => $request['price']
        ];
        $productCustomize = self::updateOrCreate(['id' => $id], $productCustomize);
        return $productCustomize;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
