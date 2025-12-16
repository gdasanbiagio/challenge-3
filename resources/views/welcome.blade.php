<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de órdenes para Urbano Express - API REST con Laravel">
    <title>Urbano Express - Gestión de Órdenes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--gray-800);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.25rem 1.5rem;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .items-section {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .items-section h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            align-items: end;
        }

        @media (max-width: 600px) {
            .item-row {
                grid-template-columns: 1fr;
            }
        }

        .item-row .form-control {
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .orders-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .order-card {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .order-card:hover {
            background: var(--gray-100);
            transform: translateX(4px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .order-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 1rem;
        }

        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-processing {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .order-customer {
            color: var(--gray-700);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .order-email {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .order-total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .order-date {
            font-size: 0.75rem;
            color: var(--gray-400);
            margin-top: 0.5rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #047857;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .loading {
            display: flex;
            justify-content: center;
            padding: 2rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: white;
            border-radius: 16px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal {
            transform: scale(1);
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: var(--gray-700);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .detail-label {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .detail-value {
            font-weight: 500;
            color: var(--gray-900);
        }

        .items-table {
            width: 100%;
            margin-top: 1rem;
            border-collapse: collapse;
        }

        .items-table th {
            text-align: left;
            padding: 0.75rem;
            background: var(--gray-50);
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--gray-500);
            font-weight: 600;
        }

        .items-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.875rem;
        }

        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🚚 Urbano Express</h1>
            <p>Sistema de Gestión de Órdenes</p>
        </header>

        <div class="main-grid">
            <!-- Create Order Form -->
            <div class="card">
                <div class="card-header">
                    <h2>📝 Nueva Orden</h2>
                </div>
                <div class="card-body">
                    <div id="alertContainer"></div>
                    
                    <form id="orderForm">
                        <div class="form-group">
                            <label for="customerName">Nombre del Cliente</label>
                            <input type="text" id="customerName" class="form-control" placeholder="Ej: Juan Pérez" required>
                        </div>

                        <div class="form-group">
                            <label for="customerEmail">Email del Cliente</label>
                            <input type="email" id="customerEmail" class="form-control" placeholder="Ej: juan@email.com" required>
                        </div>

                        <div class="items-section">
                            <h3>📦 Productos</h3>
                            <div id="itemsContainer">
                                <div class="item-row">
                                    <div class="form-group" style="margin-bottom:0">
                                        <label>Producto</label>
                                        <input type="text" class="form-control item-name" placeholder="Nombre del producto" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom:0">
                                        <label>Cantidad</label>
                                        <input type="number" class="form-control item-quantity" placeholder="1" min="1" value="1" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom:0">
                                        <label>Precio Unit.</label>
                                        <input type="number" class="form-control item-price" placeholder="0.00" min="0" step="0.01" required>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-item" style="margin-bottom:0" onclick="removeItem(this)">✕</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="addItem()" style="margin-top:0.5rem">
                                + Agregar Producto
                            </button>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%">
                            <span id="submitText">Crear Orden</span>
                            <div id="submitSpinner" class="spinner hidden" style="width:20px;height:20px;border-width:2px"></div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Órdenes Recientes</h2>
                </div>
                <div class="card-body">
                    <div id="ordersLoading" class="loading">
                        <div class="spinner"></div>
                    </div>
                    <div id="ordersEmpty" class="empty-state hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>No hay órdenes aún</p>
                        <p style="font-size:0.875rem">Crea tu primera orden usando el formulario</p>
                    </div>
                    <div id="ordersList" class="orders-list"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3>Detalle de Orden</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalContent"></div>
        </div>
    </div>

    <script>
        const API_BASE = '/api/orders';

        // Load orders on page load
        document.addEventListener('DOMContentLoaded', loadOrders);

        // Form submission
        document.getElementById('orderForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await createOrder();
        });

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const itemHtml = `
                <div class="item-row">
                    <div class="form-group" style="margin-bottom:0">
                        <input type="text" class="form-control item-name" placeholder="Nombre del producto" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <input type="number" class="form-control item-quantity" placeholder="1" min="1" value="1" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <input type="number" class="form-control item-price" placeholder="0.00" min="0" step="0.01" required>
                    </div>
                    <button type="button" class="btn btn-danger remove-item" style="margin-bottom:0" onclick="removeItem(this)">✕</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHtml);
        }

        function removeItem(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btn.closest('.item-row').remove();
            }
        }

        async function createOrder() {
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            
            submitText.textContent = 'Creando...';
            submitSpinner.classList.remove('hidden');

            const items = [];
            document.querySelectorAll('.item-row').forEach(row => {
                items.push({
                    product_name: row.querySelector('.item-name').value,
                    quantity: parseInt(row.querySelector('.item-quantity').value),
                    unit_price: parseFloat(row.querySelector('.item-price').value)
                });
            });

            const orderData = {
                customer_name: document.getElementById('customerName').value,
                customer_email: document.getElementById('customerEmail').value,
                items: items
            };

            try {
                const response = await fetch(API_BASE, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert('✅ Orden creada exitosamente!', 'success');
                    document.getElementById('orderForm').reset();
                    // Reset items to just one row
                    document.getElementById('itemsContainer').innerHTML = `
                        <div class="item-row">
                            <div class="form-group" style="margin-bottom:0">
                                <label>Producto</label>
                                <input type="text" class="form-control item-name" placeholder="Nombre del producto" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label>Cantidad</label>
                                <input type="number" class="form-control item-quantity" placeholder="1" min="1" value="1" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label>Precio Unit.</label>
                                <input type="number" class="form-control item-price" placeholder="0.00" min="0" step="0.01" required>
                            </div>
                            <button type="button" class="btn btn-danger remove-item" style="margin-bottom:0" onclick="removeItem(this)">✕</button>
                        </div>
                    `;
                    loadOrders();
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                    showAlert('❌ Error: ' + errors, 'error');
                }
            } catch (error) {
                showAlert('❌ Error de conexión: ' + error.message, 'error');
            }

            submitText.textContent = 'Crear Orden';
            submitSpinner.classList.add('hidden');
        }

        async function loadOrders() {
            const loading = document.getElementById('ordersLoading');
            const empty = document.getElementById('ordersEmpty');
            const list = document.getElementById('ordersList');

            loading.classList.remove('hidden');
            empty.classList.add('hidden');
            list.innerHTML = '';

            try {
                const response = await fetch(API_BASE, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                loading.classList.add('hidden');

                if (data.data && data.data.length > 0) {
                    data.data.forEach(order => {
                        list.innerHTML += renderOrderCard(order);
                    });
                } else {
                    empty.classList.remove('hidden');
                }
            } catch (error) {
                loading.classList.add('hidden');
                list.innerHTML = `<div class="alert alert-error">Error al cargar órdenes: ${error.message}</div>`;
            }
        }

        function renderOrderCard(order) {
            const statusClass = {
                'pending': 'status-pending',
                'processing': 'status-processing',
                'completed': 'status-completed'
            }[order.status] || 'status-pending';

            const statusText = {
                'pending': 'Pendiente',
                'processing': 'Procesando',
                'completed': 'Completado'
            }[order.status] || order.status;

            const date = new Date(order.created_at).toLocaleDateString('es-ES', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            return `
                <div class="order-card" onclick="showOrderDetail(${order.id})">
                    <div class="order-header">
                        <span class="order-id">Orden #${order.id}</span>
                        <span class="order-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="order-customer">${order.customer_name}</div>
                    <div class="order-email">${order.customer_email}</div>
                    <div class="order-total">$${parseFloat(order.total).toFixed(2)}</div>
                    <div class="order-date">${date}</div>
                </div>
            `;
        }

        async function showOrderDetail(id) {
            const modal = document.getElementById('orderModal');
            const content = document.getElementById('modalContent');
            
            content.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
            modal.classList.add('active');

            try {
                const response = await fetch(`${API_BASE}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (response.ok) {
                    const order = data.data;
                    const date = new Date(order.created_at).toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    content.innerHTML = `
                        <div class="detail-row">
                            <span class="detail-label">ID de Orden</span>
                            <span class="detail-value">#${order.id}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Cliente</span>
                            <span class="detail-value">${order.customer_name}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">${order.customer_email}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado</span>
                            <span class="detail-value">${order.status}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Fecha</span>
                            <span class="detail-value">${date}</span>
                        </div>

                        <h4 style="margin-top:1.5rem;margin-bottom:0.5rem;font-size:0.875rem;color:var(--gray-500)">PRODUCTOS</h4>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${order.items.map(item => `
                                    <tr>
                                        <td>${item.product_name}</td>
                                        <td>${item.quantity}</td>
                                        <td>$${parseFloat(item.unit_price).toFixed(2)}</td>
                                        <td>$${parseFloat(item.subtotal).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>

                        <div class="detail-row" style="margin-top:1rem;padding-top:1rem;border-top:2px solid var(--gray-200)">
                            <span class="detail-label" style="font-size:1rem;font-weight:600">Total</span>
                            <span class="detail-value" style="font-size:1.25rem;color:var(--primary)">$${parseFloat(order.total).toFixed(2)}</span>
                        </div>
                    `;
                } else {
                    content.innerHTML = `<div class="alert alert-error">Orden no encontrada</div>`;
                }
            } catch (error) {
                content.innerHTML = `<div class="alert alert-error">Error: ${error.message}</div>`;
            }
        }

        function closeModal() {
            document.getElementById('orderModal').classList.remove('active');
        }

        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Close modal on overlay click
        document.getElementById('orderModal').addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal();
            }
        });
    </script>
</body>
</html>
