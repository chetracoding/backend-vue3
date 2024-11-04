<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_detail_id'=>$this->id,
            'quantity'=>$this->quantity,
            'price'=>$this->price,
            'product_customize'=>new ShowProductCustomizeResource($this->productCustomize),
        ];
    }
}
