@php($title = 'Productos')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center">
        <h1 class="h3 m-0">{{ $title }}</h1>
        @if(isset($lowStockFilter) && $lowStockFilter)
          <span class="badge bg-warning text-dark ms-3">Mostrando productos con stock bajo</span>
        @endif
      </div>
      <a href="{{ route('products.create') }}" class="btn btn-primary">Nuevo producto</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if($lowStockCount > 0)
      <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Tienes <strong>{{ $lowStockCount }}</strong> {{ $lowStockCount === 1 ? 'producto' : 'productos' }} con stock bajo o al límite.
        <a href="{{ route('products.index', ['low_stock' => 1]) }}" class="alert-link ms-2">Ver productos con stock bajo</a>
      </div>
    @endif

    <form method="get" class="row g-2 mb-3">
      <div class="col-md-4">
        <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Buscar por nombre o código">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
      </div>
    </form>

    <div class="table-responsive bg-white shadow-sm rounded">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Proveedor</th>
            <th>Precio Venta</th>
            <th>Stock</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            <tr @class(['table-warning'=> $product->stock <= $product->min_stock])>
              <td>{{ $product->code }}</td>
              <td>{{ $product->name }}</td>
              <td>{{ $product->category?->name }}</td>
              <td>{{ $product->supplier?->name }}</td>
              <td>S/ {{ number_format($product->sale_price, 2) }}</td>
              <td>{{ $product->stock }}</td>
              <td>
                <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                  {{ $product->status ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="text-end">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                <form action="{{ route('products.destroy', $product) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Sin productos por mostrar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $products->links() }}
    </div>
  </div>
</body>
</html>
