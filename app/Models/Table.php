<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Table extends Model
{
    protected $fillable = [
        'store_id',
        'table_number'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Store or Update table
    public static function storeTable($request, $id = null)
    {
        $table = $request->only(['table_number']);
        $table['store_id'] = Auth::user()->store->id;
        $table = self::updateOrCreate(['id' => $id], $table);
        return $table;
    }

    // Check table exists in store 
    public static function contains($field, $value)
    {
        return Auth::user()->store->tables->contains($field, $value);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
