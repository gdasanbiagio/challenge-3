<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Order",
    title: "Order",
    description: "Modelo de orden",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "customer_name", type: "string", example: "Juan Pérez"),
        new OA\Property(property: "customer_email", type: "string", format: "email", example: "juan@email.com"),
        new OA\Property(property: "total", type: "string", example: "150.00"),
        new OA\Property(property: "status", type: "string", enum: ["pending", "processing", "completed", "cancelled"], example: "pending"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "items",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/OrderItem")
        )
    ]
)]
class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_email',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
