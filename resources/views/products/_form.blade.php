@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">Código</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $product->code) }}" required>
    @error('code')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-5">
    <label class="form-label">Nombre</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Categoría</label>
    <select name="category_id" class="form-select" required>
      <option value="">-- Seleccione --</option>
      @foreach($categories as $id => $name)
        <option value="{{ $id }}" @selected(old('category_id', $product->category_id)==$id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Proveedor</label>
    <select name="supplier_id" class="form-select">
      <option value="">-- Opcional --</option>
      @foreach($suppliers as $id => $name)
        <option value="{{ $id }}" @selected(old('supplier_id', $product->supplier_id)==$id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('supplier_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Precio Compra</label>
    <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}">
    @error('purchase_price')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Precio Venta</label>
    <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}" required>
    @error('sale_price')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label">Stock</label>
    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
    @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label">Stock mínimo</label>
    <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}">
    @error('min_stock')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Descripción</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $product->description) }}</textarea>
    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-12"><hr><strong>Datos de libro (opcional)</strong></div>
  <div class="col-md-4">
    <label class="form-label">Autor</label>
    <input type="text" name="author" class="form-control" value="{{ old('author', $product->author) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Editorial</label>
    <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $product->publisher) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">ISBN</label>
    <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $product->isbn) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Año publicación</label>
    <input type="number" name="publication_year" class="form-control" value="{{ old('publication_year', $product->publication_year) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Género</label>
    <input type="text" name="genre" class="form-control" value="{{ old('genre', $product->genre) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Páginas</label>
    <input type="number" name="pages" class="form-control" value="{{ old('pages', $product->pages) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Idioma</label>
    <input type="text" name="language" class="form-control" value="{{ old('language', $product->language ?? 'Español') }}">
  </div>

  <div class="col-12"><hr><strong>Otros productos (opcional)</strong></div>
  <div class="col-md-3">
    <label class="form-label">Marca</label>
    <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Modelo</label>
    <input type="text" name="model" class="form-control" value="{{ old('model', $product->model) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Color</label>
    <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Tamaño</label>
    <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Imagen (ruta)</label>
    <input type="text" name="image_path" class="form-control" value="{{ old('image_path', $product->image_path) }}">
  </div>
  <div class="col-md-2 form-check d-flex align-items-end">
    <input type="hidden" name="status" value="0">
    <input class="form-check-input me-2" type="checkbox" name="status" value="1" id="statusCheck" {{ old('status', $product->status) ? 'checked' : '' }}>
    <label class="form-check-label" for="statusCheck">Activo</label>
  </div>
</div>
<div class="mt-3">
  <button class="btn btn-primary">Guardar</button>
  <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
