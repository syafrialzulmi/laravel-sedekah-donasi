@php
  // $customer wajib di-pass dari view parent
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name', $customer->name) }}" placeholder="Nama customer">
  </div>
  <div class="col-md-6">
    <label class="form-label">Telepon</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $customer->phone) }}" placeholder="Nomor telepon">
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control"
           value="{{ old('email', $customer->email) }}" placeholder="email@contoh.com">
  </div>
  <div class="col-12">
    <label class="form-label">Alamat</label>
    <textarea name="address" rows="3" class="form-control" placeholder="Alamat lengkap">{{ old('address', $customer->address) }}</textarea>
  </div>
</div>
