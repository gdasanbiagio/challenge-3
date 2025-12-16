<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Urbano Express Orders API",
    description: "API REST para gestión de órdenes de e-commerce"
)]
#[OA\Server(url: "http://localhost:8888", description: "Servidor local")]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Ingrese el token obtenido del endpoint /api/auth/login"
)]
#[OA\Tag(name: "Orders", description: "Endpoints para gestión de órdenes")]
class OrderController extends Controller
{
    /**
     * Lista todas las órdenes con sus items.
     */
    #[OA\Get(
        path: "/api/orders",
        summary: "Listar todas las órdenes",
        description: "Obtiene una lista de todas las órdenes con sus items asociados",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de órdenes obtenida exitosamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(ref: "#/components/schemas/Order")
                        ),
                        new OA\Property(property: "message", type: "string", example: "Órdenes obtenidas exitosamente")
                    ]
                )
            )
        ]
    )]
    
    public function index(): JsonResponse
    {
        $orders = Order::with('items')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Órdenes obtenidas exitosamente'
        ]);
    }

    /**
     * Crea una nueva orden con sus items.
     */
    #[OA\Post(
        path: "/api/orders",
        summary: "Crear una nueva orden",
        description: "Crea una orden con los datos del cliente y los productos",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["customer_name", "customer_email", "items"],
                properties: [
                    new OA\Property(property: "customer_name", type: "string", example: "Juan Pérez"),
                    new OA\Property(property: "customer_email", type: "string", format: "email", example: "juan@email.com"),
                    new OA\Property(
                        property: "items",
                        type: "array",
                        items: new OA\Items(
                            required: ["product_name", "quantity", "unit_price"],
                            properties: [
                                new OA\Property(property: "product_name", type: "string", example: "Producto A"),
                                new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 2),
                                new OA\Property(property: "unit_price", type: "number", format: "float", minimum: 0, example: 50.00)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Orden creada exitosamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", ref: "#/components/schemas/Order"),
                        new OA\Property(property: "message", type: "string", example: "Orden creada exitosamente")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Error de validación",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Error de validación"),
                        new OA\Property(
                            property: "errors",
                            type: "object",
                            example: ["customer_name" => ["El nombre del cliente es obligatorio."]]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Calcular el total de la orden
        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }

        // Crear la orden
        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'total' => $total,
            'status' => 'pending',
        ]);

        // Crear los items de la orden
        foreach ($validated['items'] as $item) {
            $order->items()->create([
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        // Cargar la relación items para la respuesta
        $order->load('items');

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Orden creada exitosamente'
        ], 201);
    }

    /**
     * Muestra una orden específica con sus items.
     */
    #[OA\Get(
        path: "/api/orders/{id}",
        summary: "Obtener una orden por ID",
        description: "Obtiene los detalles de una orden específica con sus items",
        tags: ["Orders"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID de la orden",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Orden obtenida exitosamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", ref: "#/components/schemas/Order"),
                        new OA\Property(property: "message", type: "string", example: "Orden obtenida exitosamente")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Orden no encontrada",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Orden no encontrada")
                    ]
                )
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Orden obtenida exitosamente'
        ]);
    }
}
