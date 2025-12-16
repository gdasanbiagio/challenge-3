# Urbano Express - Orders API

![CI](https://github.com/gdasanbiagio/challenge-3/actions/workflows/ci.yml/badge.svg)

## 📦 Descripción del Proyecto

Este proyecto es una **API REST para gestión de órdenes de e-commerce** desarrollada como parte del desafío técnico para PHP Full Stack Developer.

### ¿Qué hace?

El sistema permite gestionar el ciclo completo de órdenes de un e-commerce:
- **Crear órdenes** con datos del cliente y múltiples productos
- **Consultar órdenes** existentes (listado completo o por ID)
- **Calcular automáticamente** el total basado en los productos

### Tecnologías utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | 8.2 | Lenguaje principal |
| **Laravel** | 11 | Framework backend |
| **MySQL** | 8.0 | Base de datos |
| **Docker** | - | Containerización |
| **Swagger/OpenAPI** | 3.0 | Documentación API |

### Arquitectura

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend (HTML/JS)                       │
│                   http://localhost:8888                     │
└─────────────────────────┬───────────────────────────────────┘
                          │ HTTP Requests
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                     Laravel API                             │
│  ┌─────────────┐  ┌──────────────┐  ┌─────────────────┐    │
│  │   Routes    │→ │  Controller  │→ │     Models      │    │
│  │  (api.php)  │  │(OrderController)│ │ (Order/Item)   │    │
│  └─────────────┘  └──────────────┘  └────────┬────────┘    │
└──────────────────────────────────────────────┼──────────────┘
                                               │
                          ┌────────────────────▼────────────────┐
                          │           MySQL Database            │
                          │  ┌─────────┐    ┌──────────────┐   │
                          │  │ orders  │───→│ order_items  │   │
                          │  └─────────┘    └──────────────┘   │
                          └────────────────────────────────────┘
```

## 🚀 Características

- **API REST** para gestión de órdenes con endpoints CRUD
- **Autenticación** con tokens (Laravel Sanctum)
- **Documentación Swagger** interactiva en `/api/documentation`
- **Frontend moderno** con interfaz responsive
- **Docker** para fácil despliegue
- **Validación** robusta de datos
- **Tests automatizados** para garantizar calidad

## 📋 Requisitos

- Docker y Docker Compose
- Git

## 🛠️ Instalación

1. **Clonar el repositorio:**
```bash
git clone <repository-url>
cd challenge-3
```

2. **Iniciar los contenedores:**
```bash
docker-compose up -d --build
```

3. **Instalar dependencias y configurar:**
```bash
docker-compose exec app composer install
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

4. **Generar documentación Swagger:**
```bash
docker-compose exec app php artisan l5-swagger:generate
```

5. **Acceder a la aplicación:**
- Frontend: http://localhost:8888
- API: http://localhost:8888/api/orders
- Swagger: http://localhost:8888/api/documentation

## 🔐 Autenticación

La API utiliza **Laravel Sanctum** para autenticación con tokens Bearer.

### Endpoints de autenticación:

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/auth/register` | Registrar nuevo usuario |
| POST | `/api/auth/login` | Iniciar sesión (obtener token) |
| POST | `/api/auth/logout` | Cerrar sesión (requiere token) |
| GET | `/api/auth/me` | Obtener usuario actual (requiere token) |

### Flujo de autenticación:

1. **Registrar o iniciar sesión** para obtener un token:
```bash
curl -X POST http://localhost:8888/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@email.com","password":"password123"}'
```

2. **Usar el token** en las peticiones:
```bash
curl http://localhost:8888/api/orders \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### Respuesta de login:
```json
{
    "success": true,
    "message": "Login exitoso",
    "data": {
        "user": {...},
        "token": "1|abc123xyz..."
    }
}
```

## 📡 API Endpoints

### Listar todas las órdenes
```http
GET /api/orders
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "customer_name": "Juan Pérez",
            "customer_email": "juan@email.com",
            "total": "150.00",
            "status": "pending",
            "created_at": "2024-12-16T10:00:00.000000Z",
            "items": [
                {
                    "id": 1,
                    "product_name": "Producto A",
                    "quantity": 2,
                    "unit_price": "50.00",
                    "subtotal": "100.00"
                }
            ]
        }
    ],
    "message": "Órdenes obtenidas exitosamente"
}
```

---

### Crear una orden
```http
POST /api/orders
Content-Type: application/json
```

**Request Body:**
```json
{
    "customer_name": "Juan Pérez",
    "customer_email": "juan@email.com",
    "items": [
        {
            "product_name": "Producto A",
            "quantity": 2,
            "unit_price": 50.00
        },
        {
            "product_name": "Producto B",
            "quantity": 1,
            "unit_price": 50.00
        }
    ]
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "customer_name": "Juan Pérez",
        "customer_email": "juan@email.com",
        "total": "150.00",
        "status": "pending",
        "created_at": "2024-12-16T10:00:00.000000Z",
        "items": [...]
    },
    "message": "Orden creada exitosamente"
}
```

**Error de validación (422):**
```json
{
    "message": "Error de validación",
    "errors": {
        "customer_name": ["El nombre del cliente es obligatorio."],
        "items": ["Debe incluir al menos un producto."]
    }
}
```

---

### Obtener una orden por ID
```http
GET /api/orders/{id}
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "customer_name": "Juan Pérez",
        "customer_email": "juan@email.com",
        "total": "150.00",
        "status": "pending",
        "items": [...]
    },
    "message": "Orden obtenida exitosamente"
}
```

**Orden no encontrada (404):**
```json
{
    "success": false,
    "message": "Orden no encontrada"
}
```

## 📚 Documentación Swagger

La API cuenta con documentación interactiva generada con Swagger/OpenAPI.

### Acceder a la documentación:

**URL**: http://localhost:8888/api/documentation

### Regenerar documentación:

Si realizas cambios en los endpoints, regenera la documentación:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

### Características de Swagger UI:

- 📖 **Documentación completa** de todos los endpoints
- 🔍 **Esquemas detallados** de request y response
- ▶️ **Try it out** - Probar endpoints directamente desde el navegador
- 📋 **Ejemplos** de payloads JSON

## 🧪 Testing

El proyecto incluye tests automatizados para validar el correcto funcionamiento de la API.

### Ejecutar todos los tests:

```bash
docker-compose exec app php artisan test
```

### Ejecutar solo tests de la API de órdenes:

```bash
docker-compose exec app php artisan test --filter=OrderApiTest
```

### Tests incluidos:

| Test | Descripción |
|------|-------------|
| `test_can_list_all_orders` | Verifica que se pueden listar todas las órdenes |
| `test_can_create_order` | Verifica la creación exitosa de una orden |
| `test_create_order_validation_errors` | Verifica errores de validación con datos vacíos |
| `test_create_order_invalid_email` | Verifica validación de email inválido |
| `test_create_order_empty_items` | Verifica validación de orden sin productos |
| `test_can_get_single_order` | Verifica obtener una orden por ID |
| `test_get_nonexistent_order_returns_404` | Verifica respuesta 404 para orden inexistente |
| `test_order_total_calculated_correctly` | Verifica cálculo correcto del total |

### Resultado esperado:

```
   PASS  Tests\Feature\OrderApiTest
  ✓ can list all orders
  ✓ can create order
  ✓ create order validation errors
  ✓ create order invalid email
  ✓ create order empty items
  ✓ can get single order
  ✓ get nonexistent order returns 404
  ✓ order total calculated correctly

  Tests:    8 passed (47 assertions)
```

### Estructura de tests:

```
tests/
├── Feature/
│   ├── ExampleTest.php
│   └── OrderApiTest.php    # Tests de la API de órdenes
└── Unit/
    └── ExampleTest.php
```

## 📁 Estructura del Proyecto

```
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   └── OrderController.php
│   │   └── Requests/
│   │       └── StoreOrderRequest.php
│   └── Models/
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   └── migrations/
│       ├── 2024_12_16_000001_create_orders_table.php
│       └── 2024_12_16_000002_create_order_items_table.php
├── routes/
│   └── api.php
├── resources/views/
│   └── welcome.blade.php
├── tests/Feature/
│   └── OrderApiTest.php
├── Dockerfile
├── docker-compose.yml
└── README.md
```

## 🔧 Comandos Docker Útiles

```bash
# Ver logs
docker-compose logs -f app

# Acceder al contenedor
docker-compose exec app bash

# Reiniciar contenedores
docker-compose restart

# Detener y eliminar contenedores
docker-compose down

# Reconstruir contenedores
docker-compose up -d --build
```

## 📝 Licencia

MIT License
