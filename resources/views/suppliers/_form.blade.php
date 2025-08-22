@csrf

<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Nombre</span></label>
    <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" class="input input-bordered w-full" required>
    @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Persona de contacto</span></label>
    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" class="input input-bordered w-full">
    @error('contact_person')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Teléfono</span></label>
    <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="input input-bordered w-full">
    @error('phone')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Email</span></label>
    <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="input input-bordered w-full">
    @error('email')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">RUC/NIT</span></label>
    <input type="text" name="tax_id" value="{{ old('tax_id', $supplier->tax_id ?? '') }}" class="input input-bordered w-full">
    @error('tax_id')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-12">
    <label class="label"><span class="label-text">Dirección</span></label>
    <textarea name="address" rows="3" class="textarea textarea-bordered w-full">{{ old('address', $supplier->address ?? '') }}</textarea>
    @error('address')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-12 flex items-center gap-3">
    <label class="label cursor-pointer">
      <span class="label-text mr-3">Activo</span>
      <input type="checkbox" name="status" class="toggle toggle-primary" {{ old('status', $supplier->status ?? true) ? 'checked' : '' }}>
    </label>
  </div>
</div>

<div class="mt-6 flex items-center gap-3">
  <button type="submit" class="btn btn-primary">
    {{ $submitLabel ?? 'Guardar' }}
  </button>
  <a href="{{ url()->previous() }}" class="btn btn-ghost">Cancelar</a>
</div>
