<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = [
        'store_id',
        'name'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function storeCategory($request, $id = null)
    {
        $category = $request->only(['name']);
        $category['store_id'] = Auth::user()->store->id;
        $category = self::updateOrCreate(['id' => $id], $category);
        return $category;
    }

    // Check category exists in store 
    public static function contains($field, $value)
    {
        return Auth::user()->store->categories->contains($field, $value);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
