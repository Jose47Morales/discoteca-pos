@extends('layouts.app')

@section('title', 'Crear Venta - POS')

@push('styles')
<style>
    .pos-container {
        display: flex;
        height: 100%;
        width: 100%;
    }

    .pos-header {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
    }

    .products-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1rem;
        overflow: hidden;
    }

    .search-bar {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 12px 20px;
        color: white;
        margin-bottom: 1rem;
        width: 100%;
        font-size: 1rem;
    }

    .search-bar::placeholder{
        color: rgba(255, 255, 255, 0.7);
    }

    .search-bar:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #9b59b6;
        box-shadow: 0 0 15px rgba(155, 89, 182, 0.4);
        outline: none;
        color: white;
    }

    .category-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .category-tab {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        color: white;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        white-space: nowrap;
    }

    .category-tab.active {
        background: #9b59b6;
        box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
        transform: translateY(-2px);
    }

    .category-tab:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        overflow-y: auto;
        flex: 1;
        padding: 0.5rem;
    }

    .products-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 1.2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 180px;
    }

    .products-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        border-color: #9b59b6;
    }

    .product-image {
        width: 40px;
        height: 40px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .product-name {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: #2c3e50;
        line-height: 1.3;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: bold;
        color: #27ae60;
        margin-bottom: 0.5rem;
    }

    .product-stock {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.8rem;
    }

    .product-stock.low-stock {
        color: #e74c3c;
        font-weight: 600;
    }

    .add-btn {
        background: #9b59b6;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .add-btn:hover:not(:disabled) {
        background: #8e44ad;
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(139, 69, 173, 0.4);
    }

    .add-btn:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
        opacity: 0.6;
    }

    .pos-sidebar {
        width: 380px;
        background: #2c3e50;
        color: white;
        display: flex;
        flex-direction: column;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .sidebar-section {
        padding: 1.2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-section h3 {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #ecf0f1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 15px;
        margin-bottom: 1rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: #9b59b6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .user-info h4 {
        margin: 0;
        font-size: 0.9rem;
        color: white;
    }

    .user-role {
        background: #27ae60;
        color: white;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.65 rem;
        display: inline-block;
        margin-top: 2px;
    }

    .session-info {
        font-size: 0.75rem;
        color: #bdc3c7;
        margin-top: 0.4rem;
    }

    .table-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.4rem;
        margin-bottom: 1rem;
    }

    .table-btn {
        aspect-ratio: 1;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: #ffffff1a;
        color: white;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3 ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        padding: 0.4rem;
    }

    .table-btn.occupied {
        background: #e74c3c;
        border-color: #c0392b;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .table-btn.selected {
        background: #9b59b6;
        border-color: #8e44ad;
        box-shadow: 0 0 15px rgba(155, 89, 182, 0.5);
        transform: scale(1.05);
    }

    .table-btn:hover:not(.occupied):not(:disabled) {
        transform: scale(1.05);
        background: rgba(255, 255, 255, 0.2);
    }

    .table-btn i {
        font-size: 1.2rem;
        margin-bottom: 0.2rem;
    }

    .cart-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .cart-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 1rem;
        max-height: 250px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.8rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        margin-bottom: 0.4rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        margin-bottom: 0.2rem;
        font-size: 0.85rem;
    }

    .item-price {
        font-size: 0.75rem;
        color: #bdc3c7;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 0.2rem;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border: none;
        background: #9b59b6;
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.7rem;
    }

    .qty-btn:hover {
        background: #8e44ad;
        transform: scale(1.1);
    }

    .qty-display {
        min-width: 26px;
        text-align: center;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .cart-empty {
        text-align: center;
        color: #7f8c8d;
        padding: 2rem 1rem;
    }

    .cart-empty i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .checkout-summary {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.4rem;
        font-size: 0.85rem;
    }

    .summary-line.total {
        font-size: 1rem;
        font-weight: bold;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 0.5rem;
        margin-top: 0.5rem
    }

    .checkout-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #9b59b6;
        border: none;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3 ease;
        flex: 1;
        font-size: 0.85rem;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-primary:hover:not(:disabled) {
        background: #8e44ad;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(139, 69, 173, 0.4);
    }

    .btn-primary:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        opacity: 0.6;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: none;
        color: white;
        backdrop-filter: blur(5px);
    }

    .loading-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .category-emoji {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        main.container-fluid {
            margin-left: 0 !important;
            width: 100vw !important;
            max-width: 100% !important;
        }
        
        .pos-container {
            flex-direction: column;
        }
        
        .pos-sidebar {
            width: 100%;
            height: 50vh;
            order: -1;
        }
        
        .products-section {
            height: 50vh;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        }

        .sidebar {
            display: none !important;
        }
    }

    .empty-products {
        grid-column: 1 / -1;
        text-align: center;
        color: white;
        padding: 3rem 1rem;
    }

</style>
@endpush

@section('content')
<div class="pos-container">
    <div class="products-section">
        <div class="pos-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0" style="background: linear-gradient(135deg, #9b59b6, #667eea); background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">
                        <i class="fas fa-music me-2"></i> Discoteca POS - Crear Venta
                    </h2>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Mesa seleccionada: <span id="selectedTableName">Ninguna</span></span>
                </div>
            </div>
        </div>

        <input type="text" id="searchInput" class="search-bar" placeholder="Buscar productos">
        
        <div class="category-tabs">
            <button class="category-tab active" data-category="all">
                <i class="fas fa-th me-2"></i> Todo
            </button>
            @foreach($productsByCategory as $category => $categoryProducts)
                <button class="category-tab" data-category="{{ $category }}">
                    @switch($category)
                        @case('bebidas')
                            <i class="fas fa-beer me-2"></i> Bebidas
                            @break
                        @case('cocteles')
                            <i class="fas fa-cocktail me-2"></i> Cocteles
                            @break
                        @case('snacks')
                            <i class="fas fa-cookie-bite me-2"></i> Snacks
                            @break
                        @case('entrada')
                            <i class="fas fa-ticket-alt me-2"></i> Entradas
                            @break
                        @default
                            <i class="fas fa-box me-2"></i> {{ ucfirst($category) }}
                    @endswitch
                </button>
            @endforeach
        </div>

        <div class="products-grid" id="productsGrid">
            @forelse($products as $product)
            <div class="product-card" data-category="{{ $product->category }}"
                    data-product-id="{{ $product->id }}"
                    data-product='{{ json_encode([
                        "id" => $product->id,
                        "name" => $product->name,
                        "price" => $product->price,
                        "category" => $product->category,
                        "stock" => $product->stock,
                    ]) }}'>
                    <div class="product-image">
                        @switch($product->category)
                            @case('cocteles')
                                <span class="category-emoji">🍸</span>
                                @break
                            @case('bebidas')
                                <span class="category-emoji">🍻</span>
                                @break
                            @case('snacks')
                                <span class="category-emoji">🍟</span>
                                @break
                            @case('entrada')
                                <span class="category-emoji">🎫</span>
                                @break
                            @default
                                <span class="category-emoji">📦</span>
                        @endswitch
                    </div>
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-price">${{ number_format($product->price, 2) }}</div>

                    <div class="product-stock {{ $product->stock <= $product->min_stock ? 'low-stock' : '' }}">
                        Stock: {{ $product->stock }} unidades
                        @if($product->stock <= $product->min_stock)
                            <br><small class="text-danger">¡Stock bajo!</small>
                        @endif
                    </div>

                    <button class="add-btn {{ $product->stock <= 0 ? 'disabled' : '' }}" onclick="addToCart('{{ $product->id }}')">
                        @if($product->stock <= 0)
                            <i class="fas fa-ban"></i>
                        @else
                            <i class="fas fa-plus"></i>
                        @endif
                    </button>
                </div>
            @empty
                <div class="empty-products">
                    <div class="text-center text-white py-5">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                        <h4>No hay productos disponibles</h4>
                        <p>Agrega poductos desde el panel de administración.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Agregar Producto
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="pos-sidebar">
        <div class="sidebar-section">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <h4>{{ Auth::user()->name }}</h4>
                    <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    <div class="session-info">
                        <i class="fas fa-clock me-1"></i>
                        {{ now()->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            @if(in_array(Auth::user()->role, ['admin', 'vendedor']))
                <h3>
                    <i class="fas fa-cash-register me-2"></i> Seleccionar Caja
                </h3>
                <select name="cash_register_id" id="cash_register_id" class="form-control mb-3" required>
                    @foreach($cashRegisters as $register)
                            <option value="{{ $register->id }}">
                                Caja #{{ $register->id }} - {{ $register->user->name }} (Apertura: {{ $register->opened_at->format('d/m/Y H:i') }})
                            </option>
                    @endforeach
                </select>
            @endif
            <h3>
                <i class="fas fa-chair me-2"></i> Seleccionar Mesa
            </h3>
            <div class="table-selection">
                <div class="table-grid" id="tableGrid">
                    @forelse($tables as $table)
                        <button class="table-btn {{ in_array($table->id, $occupiedTables) ? 'disabled' : '' }} {{ in_array($table->id, $occupiedTables) ? 'occupied' : '' }}"
                                data-table-id="{{ $table->id }}"
                                data-table-name="{{ $table->name }}">
                            <i class="fas fa-chair"></i>
                            <span>{{ $table->name }}</span>
                            @if(in_array($table->id, $occupiedTables))
                                <small style="color: #fff; opacity: 0.8">Ocupada</small>
                            @endif
                        </button>
                    @empty
                        <div class="col-span-3 text-center text-white-50">
                            <p>No hay mesas configuradas</p>
                            <a href="{{ route('tables.create') }}" class="btn btn-sm btn-outline-light">Crear Mesa</a>
                        </div>
                    @endforelse
                </div>
                <p class="text-light small mb-0">
                    <span style="color: #ffffff1a">●</span> Disponible
                    <span style="color: #e74c3c">●</span> Ocupada
                    <span style="color: #9b59b6">●</span> Seleccionada
                </p>
            </div>
        </div>

        <div class="sidebar-section cart-section">
            <div class="cart-header">
                <h3>
                    <i class="fas fa-shopping-cart me-2"></i>Carrito (<span id="cartCount">0</span>)
                </h3>
            </div>
            <div class="cart-items" id="cartItems">
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Tu carrito está vacío</p>
                    <small class="text-muted">Agrega productos para comenzar</small>
                </div>
            </div>
            <div class="checkout-summary" id="checkoutSummary" style="display: none;">
                <div class="summary-line">
                    <span>Subtotal:</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="summary-line total">
                    <span>Total:</span>
                    <span id="total">$0.00</span>
                </div>
            </div>
            <div class="checkout-actions">
                <button class="btn btn-secondary" id="clearCart" style="flex: 0 0 auto;">
                    <i class="fas fa-trash me-2"></i> Limpiar
                </button>
                <button class="btn btn-primary" id="checkout" disabled>
                    <i class="fas fa-credit-card me-2"></i>Procesar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-border text-primary mb-3"></div>
        <h4>Procesando...</h4>
        <p>Por favor espera un momento</p>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let cart = [];
        let selectedTable = null;
        
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const checkoutSummary = document.getElementById('checkoutSummary');
        const chekcoutBtn = document.getElementById('checkout');
        const clearCartBtn = document.getElementById('clearCart');
        const searchInput = document.getElementById('searchInput');
        const selectedTableName = document.getElementById('selectedTableName');
        const loadingOverlay = document.getElementById('loadingOverlay');

        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;
                filterProducts(category);
            });
        });

        function filterProducts(category) {
            document.querySelectorAll('.product-card').forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                
                if(productName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.table-btn').forEach(btn => {
            if(!btn.disabled) {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.table-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedTable = {
                        id: this.dataset.tableId,
                        name: this.dataset.tableName
                    };
                    selectedTableName.textContent = selectedTable.name;
                    updateCheckoutButton();
                });
            }
        });

        window.addToCart = function(productId) {
            const productCard = document.querySelector(`[data-product-id="${productId}"`);
            if (!productCard) return;

            const productData = JSON.parse(productCard.dataset.product);

            if (productData.stock <= 0) {
                alert('Producto sin stock disponible');
                return;
            }

            const existingItem = cart.find(item => item.id === productData.id);

            if (existingItem) {
                if (existingItem.quantity >= productData.stock) {
                    alert(`Solo hay ${productData.stock} unidades disponibles`);
                    return;
                }
                existingItem.quantity++;
            } else {
                cart.push({
                    ...productData,
                    quantity: 1
                });
            }

            updateCartDisplay();
            updateCheckoutButton();
        };

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartDisplay();
            updateCheckoutButton();
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                const newQuantity = item.quantity + change;

                if (newQuantity <= 0){
                    removeFromCart(productId);
                } else {
                    if (newQuantity > item.stock) {
                        alert(`Solo hay ${item.stock} unidades disponibles`);
                        return;
                    }

                    item.quantity = newQuantity;
                    updateCartDisplay();
                    updateCheckoutButton();
                }
            }
        }

        function updateCartDisplay() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.textContent = totalItems;

            if (cart.length === 0){
                cartItemsContainer.innerHTML = `
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Tu carrito está vacío</p>
                        <small class="text-muted">Agrega productos para comenzar</small>
                    </div>
                `;
                checkoutSummary.style.display = 'none';
            } else {
                cartItemsContainer.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-price">${parseFloat(item.price).toFixed(2)} c/u</div>
                    </div>
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="qty-display">${item.quantity}</span>
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                `).join('');

                updateSummary();
                checkoutSummary.style.display = 'block';
            }
        }

        function updateSummary(){
            const subtotal = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
            const total = subtotal;

            document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `${total.toFixed(2)}`;
        }

        function updateCheckoutButton() {
            const hasItems = cart.length > 0;
            const hasTable = selectedTable !== null;

            chekcoutBtn.disabled = !(hasItems && hasTable);

            if (!hasTable && hasItems){
                chekcoutBtn.innerHTML = '<i class="fas fa-chair me-2"></i> Seleccionar Mesa';
            } else if (!hasItems) {
                chekcoutBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Agregar Items';
            } else {
                chekcoutBtn.innerHTML = '<i class="fas fa-credit-card me-2">Procesar</i>';
            }
        }

        clearCartBtn.addEventListener('click', function() {
            if (cart.length === 0) return;

            if (confirm('¿Limpiar todos los productos del carrito?')) {
                cart = [];
                updateCartDisplay();
                updateCheckoutButton();
            }
        });

        chekcoutBtn.addEventListener('click', function() {
            if (cart.length === 0 || !selectedTable) return;

            const subtotal = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
            const total = subtotal;
            const cashRegisterSelect = document.getElementById('cash_register_id');
            let cashRegisterId = null;
            
            if (cashRegisterSelect) {
                cashRegisterId = cashRegisterSelect.value;
            }

            const orderData = {
                table_id: selectedTable.id,
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: parseFloat(item.price)
                })),
                subtotal: subtotal,
                total: total,
                @if(in_array(Auth::user()->role, ['admin', 'vendedor']))
                cash_register_id: cashRegisterId,
                @endif
                _token: document.querySelector('meta[name="csrf-token"').getAttribute('content')
            };

            console.log('Enviando:', orderData);

            const confirmMessage = `¿Procesar venta para ${selectedTable.name}?\n\nProductos: ${cart.length}\nTotal: ${total.toFixed(2)}`;

            if (!confirm(confirmMessage)) return;

            loadingOverlay.style.display = 'block';
            chekcoutBtn.disabled = true;

            fetch('{{ route("sales.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': orderData._token
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.style.display = 'none';

                if (data.success) {
                    alert(`¡Venta procesada exitosamente!\nNúmero de venta: #${data.sale_id}`);

                    cart = [];
                    selectedTable = null;
                    document.querySelectorAll('.table-btn').forEach(btn => btn.classList.remove('selected'));
                    selectedTableName.textContent = 'Ninguna';
                    updateCartDisplay();
                    updateCheckoutButton();

                    if (confirm('¿Deseas ver el detalle de la venta?')) {
                        window.open(`{{ url('/sales') }}/${data.sale_id}`, '_blank');
                    }
                } else {
                    alert('Error al procesar la venta: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                loadingOverlay.style.display = 'none';
                console.error('Error:', error);
                alert('Error de conexión al procesar la venta. Inténtalo de nuevo.');
            }) 
            .finally(() => {
                chekcoutBtn.disabled = false;
            });
        });

        window.updateQuantity = updateQuantity;
        window.removeFromCart = removeFromCart;

        // Atajos de teclado
        updateCheckoutButton();

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            }

            if (e.key === 'Enter' && e.target !== searchInput) {
                e.preventDefault();
                searchInput.focus();
            }

            if (e.key === 'F1') {
                e.preventDefault();
                const firstTable = document.querySelector('.table-btn:not(.occupied)');
                if (firstTable) firstTable.click();
            }

            if (e.key === 'F2') {
                e.preventDefault();
                if (!chekcoutBtn.disabled) chekcoutBtn.click();
            }
        });

        console.log('Atajos de teclado disponibles:');
        console.log('ESC - Limpiar búsqueda');
        console.log('Enter - Enfocar búsqueda');
        console.log('F1 - Seleccionar primera mesa disponible');
        console.log('F2 - Procesar venta (si está lista)');
    });
</script>
@endpush