<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "OrderItem",
    title: "OrderItem",
    description: "Item de una orden",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "order_id", type: "integer", example: 1),
        new OA\Property(property: "product_name", type: "string", example: "Producto A"),
        new OA\Property(property: "quantity", type: "integer", example: 2),
        new OA\Property(property: "unit_price", type: "string", example: "50.00"),
        new OA\Property(property: "subtotal", type: "string", example: "100.00"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
