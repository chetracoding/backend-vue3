<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    protected $fillable = [
        'store_id',
        'table_id',
        'datetime',
        'is_completed',
        'is_paid',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function storeOrder($request, $id = null)
    {
        $order = ($id) ? $request->only(['is_completed', 'is_paid']) : $request->only(['table_id', 'datetime', 'is_completed', 'is_paid']);
        $order['store_id'] = Auth::user()->store->id;
        $order = self::updateOrCreate(['id' => $id], $order);
        return $order;
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
