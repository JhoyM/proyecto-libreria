@php($title = 'Editar producto')
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
      <h1 class="h4 m-0">{{ $title }}</h1>
      <a href="{{ route('products.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <strong>Revise los errores:</strong>
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('products.update', $product) }}" class="bg-white p-3 shadow-sm rounded">
      @method('PUT')
      @include('products._form')
    </form>
  </div>
</body>
</html>
