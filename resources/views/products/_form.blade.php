@csrf
<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Código</span></label>
    <input type="text" name="code" class="input input-bordered w-full" value="{{ old('code', $product->code) }}" required>
    @error('code')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-5">
    <label class="label"><span class="label-text">Nombre</span></label>
    <input type="text" name="name" class="input input-bordered w-full" value="{{ old('name', $product->name) }}" required>
    @error('name')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Categoría</span></label>
    <select name="category_id" class="select select-bordered w-full" required>
      <option value="">-- Seleccione --</option>
      @foreach($categories as $id => $name)
        <option value="{{ $id }}" @selected(old('category_id', $product->category_id)==$id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('category_id')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Proveedor</span></label>
    <select name="supplier_id" class="select select-bordered w-full">
      <option value="">-- Opcional --</option>
      @foreach($suppliers as $id => $name)
        <option value="{{ $id }}" @selected(old('supplier_id', $product->supplier_id)==$id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('supplier_id')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Precio Compra</span></label>
    <input type="number" step="0.01" name="purchase_price" class="input input-bordered w-full" value="{{ old('purchase_price', $product->purchase_price) }}">
    @error('purchase_price')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Precio Venta</span></label>
    <input type="number" step="0.01" name="sale_price" class="input input-bordered w-full" value="{{ old('sale_price', $product->sale_price) }}" required>
    @error('sale_price')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Stock</span></label>
    <input type="number" name="stock" class="input input-bordered w-full" value="{{ old('stock', $product->stock) }}">
    @error('stock')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Stock mínimo</span></label>
    <input type="number" name="min_stock" class="input input-bordered w-full" value="{{ old('min_stock', $product->min_stock) }}">
    @error('min_stock')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>
  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Descripción</span></label>
    <textarea name="description" class="textarea textarea-bordered w-full" rows="2">{{ old('description', $product->description) }}</textarea>
    @error('description')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="md:col-span-12"><div class="divider">Datos de libro (opcional)</div></div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Autor</span></label>
    <input type="text" name="author" class="input input-bordered w-full" value="{{ old('author', $product->author) }}">
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Editorial</span></label>
    <input type="text" name="publisher" class="input input-bordered w-full" value="{{ old('publisher', $product->publisher) }}">
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">ISBN</span></label>
    <input type="text" name="isbn" class="input input-bordered w-full" value="{{ old('isbn', $product->isbn) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Año publicación</span></label>
    <input type="number" name="publication_year" class="input input-bordered w-full" value="{{ old('publication_year', $product->publication_year) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Género</span></label>
    <input type="text" name="genre" class="input input-bordered w-full" value="{{ old('genre', $product->genre) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Páginas</span></label>
    <input type="number" name="pages" class="input input-bordered w-full" value="{{ old('pages', $product->pages) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Idioma</span></label>
    <input type="text" name="language" class="input input-bordered w-full" value="{{ old('language', $product->language ?? 'Español') }}">
  </div>

  <div class="md:col-span-12"><div class="divider">Otros productos (opcional)</div></div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Marca</span></label>
    <input type="text" name="brand" class="input input-bordered w-full" value="{{ old('brand', $product->brand) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Modelo</span></label>
    <input type="text" name="model" class="input input-bordered w-full" value="{{ old('model', $product->model) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Color</span></label>
    <input type="text" name="color" class="input input-bordered w-full" value="{{ old('color', $product->color) }}">
  </div>
  <div class="md:col-span-3">
    <label class="label"><span class="label-text">Tamaño</span></label>
    <input type="text" name="size" class="input input-bordered w-full" value="{{ old('size', $product->size) }}">
  </div>

  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Imagen (ruta)</span></label>
    <input type="text" name="image_path" class="input input-bordered w-full" value="{{ old('image_path', $product->image_path) }}">
  </div>
  <div class="md:col-span-3 flex items-end">
    <input type="hidden" name="status" value="0">
    <label class="label cursor-pointer gap-3">
      <span class="label-text">Activo</span>
      <input type="checkbox" name="status" value="1" class="toggle toggle-primary" id="statusCheck" {{ old('status', $product->status) ? 'checked' : '' }} />
    </label>
  </div>
</div>
<div class="mt-4 flex gap-2">
  <button class="btn btn-primary">Guardar</button>
  <a href="{{ route('products.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
