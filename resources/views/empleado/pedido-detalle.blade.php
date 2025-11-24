@extends('layouts.app-empleado')

@section('title', 'Detalle del Pedido - Empleado')
@section('page-title', 'Detalle del Pedido #' . $pedido->id)
@section('page-subtitle', 'Información completa del pedido')

@section('content')
<div class="pedido-detalle-container">

    <!-- HEADER CON ESTADO -->
    <div class="pedido-header mb-6">
        <div class="pedido-info">
            <h3 class="pedido-numero">Pedido #{{ $pedido->id }}</h3>
            <p class="pedido-fecha">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="pedido-estado-badge">
            @if($pedido->estado === 'pendiente')
                <span class="badge badge-warning">
                    <i class="fas fa-clock mr-1"></i>
                    Pendiente
                </span>
            @elseif($pedido->estado === 'en_preparacion')
                <span class="badge badge-info">
                    <i class="fas fa-fire mr-1"></i>
                    En Preparación
                </span>
            @elseif($pedido->estado === 'listo')
                <span class="badge badge-success">
                    <i class="fas fa-check-circle mr-1"></i>
                    Listo
                </span>
            @elseif($pedido->estado === 'entregado')
                <span class="badge badge-completed">
                    <i class="fas fa-check-double mr-1"></i>
                    Entregado
                </span>
            @else
                <span class="badge badge-danger">
                    <i class="fas fa-times-circle mr-1"></i>
                    Cancelado
                </span>
            @endif
        </div>
    </div>

    <div class="grid-container">
        <!-- INFORMACIÓN DEL CLIENTE -->
        <div class="info-card">
            <h4 class="card-title">
                <i class="fas fa-user mr-2"></i>
                Información del Cliente
            </h4>
            <div class="info-content">
                <div class="info-item">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ $pedido->user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $pedido->user->email }}</span>
                </div>
                @if($pedido->user->telefono)
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value">{{ $pedido->user->telefono }}</span>
                </div>
                @endif
                @if($pedido->user->direccion)
                <div class="info-item">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value">{{ $pedido->user->direccion }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- PRODUCTOS DEL PEDIDO -->
        <div class="productos-card">
            <h4 class="card-title">
                <i class="fas fa-hamburger mr-2"></i>
                Productos del Pedido
            </h4>
            <div class="productos-list">
                @foreach($pedido->detalles as $detalle)
                <div class="producto-item">
                    <div class="producto-info">
                        <span class="producto-nombre">{{ $detalle->producto->nombre }}</span>
                        <span class="producto-cantidad">x{{ $detalle->cantidad }}</span>
                    </div>
                    <div class="producto-precios">
                        <span class="producto-precio-unit">${{ number_format($detalle->precio_unitario, 2) }}</span>
                        <span class="producto-subtotal">${{ number_format($detalle->subtotal, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- TOTALES -->
            <div class="totales-section">
                <div class="total-item">
                    <span>Subtotal:</span>
                    <span>${{ number_format($pedido->total / 1.1, 2) }}</span>
                </div>
                <div class="total-item">
                    <span>Impuesto (10%):</span>
                    <span>${{ number_format($pedido->total * 0.10 / 1.1, 2) }}</span>
                </div>
                <div class="total-item total-final">
                    <span>TOTAL:</span>
                    <span>${{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- INFORMACIÓN DE PAGO -->
    @if($pedido->pago)
    <div class="pago-card">
        <h4 class="card-title">
            <i class="fas fa-credit-card mr-2"></i>
            Información de Pago
        </h4>
        <div class="pago-content">
            <div class="pago-item">
                <span class="pago-label">Método de Pago:</span>
                <span class="pago-badge">
                    @if($pedido->pago->metodo_pago === 'efectivo')
                        <i class="fas fa-money-bill-wave mr-1"></i> Efectivo
                    @elseif($pedido->pago->metodo_pago === 'tarjeta')
                        <i class="fas fa-credit-card mr-1"></i> Tarjeta
                    @else
                        <i class="fas fa-exchange-alt mr-1"></i> Transferencia
                    @endif
                </span>
            </div>
            <div class="pago-item">
                <span class="pago-label">Estado del Pago:</span>
                <span class="pago-estado {{ $pedido->pago->estado_pago }}">
                    {{ ucfirst($pedido->pago->estado_pago) }}
                </span>
            </div>
            <div class="pago-item">
                <span class="pago-label">Monto:</span>
                <span class="pago-monto">${{ number_format($pedido->pago->monto, 2) }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- NOTAS -->
    @if($pedido->notas)
    <div class="notas-card">
        <h4 class="card-title">
            <i class="fas fa-sticky-note mr-2"></i>
            Notas Especiales
        </h4>
        <p class="notas-content">{{ $pedido->notas }}</p>
    </div>
    @endif

    <!-- CAMBIAR ESTADO -->
    @if(!in_array($pedido->estado, ['entregado', 'cancelado']))
    <div class="acciones-card">
        <h4 class="card-title">
            <i class="fas fa-tasks mr-2"></i>
            Gestionar Estado del Pedido
        </h4>
        <form action="{{ route('empleado.pedido.estado', $pedido) }}" method="POST" class="estado-form">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label class="form-label">Cambiar Estado:</label>
                <select name="estado" class="form-select" required>
                    <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>
                        🕐 Pendiente
                    </option>
                    <option value="en_preparacion" {{ $pedido->estado === 'en_preparacion' ? 'selected' : '' }}>
                        🔥 En Preparación
                    </option>
                    <option value="listo" {{ $pedido->estado === 'listo' ? 'selected' : '' }}>
                        ✅ Listo para Entregar
                    </option>
                    <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>
                        ✔️✔️ Entregado
                    </option>
                    <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>
                        ❌ Cancelado
                    </option>
                </select>
            </div>
            <div class="form-actions">
                <a href="{{ route('empleado.pedidos') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Estado
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="acciones-card" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 2px solid {{ $pedido->estado === 'entregado' ? '#059669' : '#dc2626' }};">
        <h4 class="card-title">
            <i class="fas fa-info-circle mr-2"></i>
            Estado del Pedido
        </h4>
        <div class="p-4 text-center">
            <div class="mb-4">
                <span class="text-6xl">{{ $pedido->estado === 'entregado' ? '✅' : '❌' }}</span>
            </div>
            <h3 class="text-2xl font-bold mb-2" style="color: {{ $pedido->estado === 'entregado' ? '#10b981' : '#ef4444' }};">
                Pedido {{ $pedido->estado === 'entregado' ? 'Entregado' : 'Cancelado' }}
            </h3>
            <p class="text-gray-400 mb-6">
                Este pedido ya fue {{ $pedido->estado === 'entregado' ? 'entregado' : 'cancelado' }} y no se puede modificar su estado.
            </p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('empleado.pedidos') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Pedidos Activos
                </a>
                <a href="{{ route('empleado.pedidos.completados') }}" class="btn-primary">
                    <i class="fas fa-check-double mr-2"></i>
                    Ver Pedidos Completados
                </a>
            </div>
        </div>
    </div>
    @endif

</div>

@push('css')
<style>
    .pedido-detalle-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .pedido-header {
        background: var(--color-secondary);
        padding: 1.25rem;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 2px solid rgba(248, 250, 252, 0.1);
    }

    .pedido-numero {
        font-size: 1.375rem;
        font-weight: 800;
        color: var(--color-text);
        margin-bottom: 0.25rem;
    }

    .pedido-fecha {
        color: var(--color-text-muted);
        font-size: 0.8125rem;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-accent);
        border: 2px solid var(--color-accent);
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        border: 2px solid #3b82f6;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: var(--color-success);
        border: 2px solid var(--color-success);
    }

    .badge-completed {
        background: rgba(139, 92, 246, 0.15);
        color: #8b5cf6;
        border: 2px solid #8b5cf6;
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.15);
        color: var(--color-danger);
        border: 2px solid var(--color-danger);
    }

    .grid-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card, .productos-card, .pago-card, .notas-card, .acciones-card {
        background: var(--color-secondary);
        padding: 1.25rem;
        border-radius: 12px;
        border: 2px solid rgba(248, 250, 252, 0.1);
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(248, 250, 252, 0.1);
        display: flex;
        align-items: center;
    }

    .card-title i {
        color: var(--color-accent);
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .info-label {
        color: var(--color-text-muted);
        font-size: 0.8125rem;
    }

    .info-value {
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.8125rem;
    }

    .productos-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .producto-item {
        padding: 0.875rem;
        background: var(--color-primary);
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .producto-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .producto-nombre {
        font-weight: 600;
        color: var(--color-text);
        font-size: 0.875rem;
    }

    .producto-cantidad {
        color: var(--color-accent);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .producto-precios {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
    }

    .producto-precio-unit {
        color: var(--color-text-muted);
        font-size: 0.75rem;
    }

    .producto-subtotal {
        color: var(--color-accent);
        font-weight: 700;
        font-size: 0.9375rem;
    }

    .totales-section {
        border-top: 2px solid rgba(248, 250, 252, 0.1);
        padding-top: 1rem;
    }

    .total-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        color: var(--color-text);
        font-size: 0.875rem;
    }

    .total-final {
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--color-accent);
        border-top: 2px solid rgba(248, 250, 252, 0.1);
        margin-top: 0.5rem;
        padding-top: 0.75rem;
    }

    .pago-card, .notas-card, .acciones-card {
        grid-column: 1 / -1;
        margin-bottom: 1.5rem;
    }

    .pago-content {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .pago-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .pago-label {
        color: var(--color-text-muted);
        font-size: 0.75rem;
    }

    .pago-badge {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-accent);
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        width: fit-content;
    }

    .pago-estado {
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        width: fit-content;
    }

    .pago-estado.completado {
        background: rgba(16, 185, 129, 0.15);
        color: var(--color-success);
    }

    .pago-estado.pendiente {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-accent);
    }

    .pago-monto {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--color-accent);
    }

    .notas-content {
        color: var(--color-text);
        line-height: 1.6;
        font-size: 0.875rem;
        padding: 1rem;
        background: var(--color-primary);
        border-radius: 8px;
        border-left: 4px solid var(--color-accent);
    }

    .estado-form {
        display: flex;
        gap: 1.5rem;
        align-items: flex-end;
    }

    .form-group {
        flex: 1;
    }

    .form-label {
        color: var(--color-text);
        font-weight: 600;
        font-size: 0.8125rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        background: var(--color-primary);
        border: 2px solid rgba(248, 250, 252, 0.1);
        border-radius: 10px;
        color: var(--color-text);
        font-size: 0.875rem;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--color-accent);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-primary, .btn-secondary {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8125rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--color-accent), var(--color-accent-light));
        color: var(--color-primary);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: transparent;
        border: 2px solid rgba(248, 250, 252, 0.2);
        color: var(--color-text);
    }

    .btn-secondary:hover {
        background: rgba(248, 250, 252, 0.05);
    }

    @media (max-width: 768px) {
        .grid-container {
            grid-template-columns: 1fr;
        }

        .pago-content {
            grid-template-columns: 1fr;
        }

        .estado-form {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush
@endsection

