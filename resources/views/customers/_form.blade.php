@csrf

<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Nombre</span></label>
    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}" class="input input-bordered w-full" required>
    @error('first_name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-6">
    <label class="label"><span class="label-text">Apellido</span></label>
    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" class="input input-bordered w-full" required>
    @error('last_name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Tipo documento</span></label>
    <select name="document_type" class="select select-bordered w-full">
      @foreach(['CI','NIT','Pasaporte'] as $opt)
        <option value="{{ $opt }}" @selected(old('document_type', $customer->document_type ?? 'CI') === $opt)>{{ $opt }}</option>
      @endforeach
    </select>
    @error('document_type')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Nro documento</span></label>
    <input type="text" name="document_number" value="{{ old('document_number', $customer->document_number ?? '') }}" class="input input-bordered w-full" required>
    @error('document_number')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Tipo cliente</span></label>
    <select name="customer_type" class="select select-bordered w-full">
      @foreach(['regular' => 'Regular','frecuente' => 'Frecuente','mayorista' => 'Mayorista'] as $val => $label)
        <option value="{{ $val }}" @selected(old('customer_type', $customer->customer_type ?? 'regular') === $val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('customer_type')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Teléfono</span></label>
    <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="input input-bordered w-full">
    @error('phone')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Email</span></label>
    <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="input input-bordered w-full">
    @error('email')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>
  <div class="md:col-span-4">
    <label class="label"><span class="label-text">Fecha nacimiento</span></label>
    <input type="date" name="birth_date" value="{{ old('birth_date', optional($customer->birth_date ?? null)->format('Y-m-d')) }}" class="input input-bordered w-full">
    @error('birth_date')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-12">
    <label class="label"><span class="label-text">Dirección</span></label>
    <textarea name="address" rows="3" class="textarea textarea-bordered w-full">{{ old('address', $customer->address ?? '') }}</textarea>
    @error('address')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-6">
    <label class="label"><span class="label-text">% Descuento</span></label>
    <input type="number" step="0.01" min="0" max="100" name="discount_percentage" value="{{ old('discount_percentage', $customer->discount_percentage ?? 0) }}" class="input input-bordered w-full">
    @error('discount_percentage')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-6 flex items-center gap-3">
    <label class="label cursor-pointer">
      <span class="label-text mr-3">Activo</span>
      <input type="checkbox" name="status" class="toggle toggle-primary" {{ old('status', $customer->status ?? true) ? 'checked' : '' }}>
    </label>
  </div>
</div>

<div class="mt-6 flex items-center gap-3">
  <button type="submit" class="btn btn-primary">
    {{ $submitLabel ?? 'Guardar' }}
  </button>
  <a href="{{ url()->previous() }}" class="btn btn-ghost">Cancelar</a>
</div>
