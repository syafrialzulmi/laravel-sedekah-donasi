@extends('layouts.app')

@section('title', 'Add Menu')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Menu</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Add New Menu</h5>
                <small class="text-muted">Tambah menu</small>
            </div>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif

                <form action="{{ route('menus.store') }}" method="POST">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Select Icon --}}
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Font Awesome)</label>
                        <select class="form-select @error('icon') is-invalid @enderror" id="icon" name="icon">
                            <option value="">-- Pilih Icon --</option>
                            @foreach ($icons as $class => $label)
                                <option value="{{ $class }}" {{ old('icon') == $class ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih icon, tampilan akan menampilkan icon + label.</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Route --}}
                    <div class="mb-3">
                        <label for="route" class="form-label">Route</label>
                        <input type="text" class="form-control @error('route') is-invalid @enderror"
                               id="route" name="route" value="{{ old('route') }}">
                        <small class="text-muted">Contoh: menus.index</small>
                        @error('route')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Parent --}}
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">-- Tidak ada (Root) --</option>
                            @foreach($menus as $menu)
                                <option
                                    value="{{ $menu->id }}"
                                    data-icon="{{ $menu->icon ?? '' }}"
                                    {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Order --}}
                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                               id="order" name="order" value="{{ old('order', 0) }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permission Name --}}
                    <div class="mb-3">
                        <label for="permission_name" class="form-label">Permission</label>
                        <input type="text" class="form-control @error('permission_name') is-invalid @enderror"
                               id="permission_name" name="permission_name" value="{{ old('permission_name') }}">
                        <small class="text-muted">Contoh: manage-list</small>
                        @error('permission_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* === Umum (ikon di item & value) === */
.select2-results__option .fa,
.select2-selection__rendered .fa {
  margin-right: .5rem;
  width: 1.25rem;
  text-align: center;
  font-size: 1rem; /* biar proporsional */
  line-height: 1;
}

/* ===== Bootstrap 4 theme ===== */
.select2-container--bootstrap4 .select2-selection--single {
  height: calc(2.5rem + 2px);            /* samakan dgn .form-control-lg/biasa */
  padding: .5rem .75rem;
  display: flex;
  align-items: center;                   /* pusatkan vertikal */
  position: relative;
  border: 1px solid #d9dee3;
  border-radius: 0.375rem;
}
.select2-container--bootstrap4 .select2-selection__rendered {
  padding-left: 0;                        /* padding sudah dari parent */
  padding-right: 2rem;                    /* ruang utk tombol clear */
  line-height: 1.25;                      /* jangan pakai line-height tinggi */
  display: inline-flex;
  align-items: center;
  gap: .5rem;
}
.select2-container--bootstrap4 .select2-selection__arrow {
  height: 100%;
}
.select2-container--bootstrap4 .select2-selection__clear {
  position: absolute;                     /* posisikan '×' di kanan-tengah */
  right: .75rem;
  top: 50%;
  transform: translateY(-50%);
  margin-right: 0;
}

/* ===== Bootstrap 5 theme ===== */
.select2-container--bootstrap-5 .select2-selection--single {
  min-height: calc(2.5rem + 2px);
  height: calc(2.5rem + 2px);
  padding: .5rem .75rem;
  display: flex;
  align-items: center;
  position: relative;
}
.select2-container--bootstrap-5 .select2-selection__rendered {
  padding-left: 0;
  padding-right: 2rem;
  line-height: 1.25;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
}
.select2-container--bootstrap-5 .select2-selection__clear {
  position: absolute;
  right: .75rem;
  top: 50%;
  transform: translateY(-50%);
}

/* Samakan tinggi item di dropdown agar rapi */
.select2-container .select2-results__option {
  display: flex;
  align-items: center;
  gap: .5rem;
}
</style>
@endpush

@push('scripts')
<script>
(function () {
  function withIcon(item) {
    if (!item.id) return item.text;
    var iconClass = '';
    if (item.element && item.element.dataset && item.element.dataset.icon) {
      iconClass = item.element.dataset.icon;   // untuk parent
    } else {
      iconClass = item.id;                     // untuk icon picker
    }
    if (!iconClass) return $('<span>'+ item.text +'</span>');
    return $('<span><i class="'+ iconClass +'"></i> '+ item.text +'</span>');
  }

  // Ganti 'bootstrap4' -> 'bootstrap-5' kalau project-mu Bootstrap 5
  var theme = 'bootstrap4'; // atau 'bootstrap-5'

  $('#icon').select2({
    theme: theme,
    width: '100%',
    placeholder: '-- Pilih Icon --',
    allowClear: true,
    templateResult: withIcon,
    templateSelection: withIcon,
    escapeMarkup: function (m) { return m; },
    minimumResultsForSearch: 5
  });

  $('#parent_id').select2({
    theme: theme,
    width: '100%',
    placeholder: '-- Tidak ada (Root) --',
    allowClear: true,
    templateResult: withIcon,
    templateSelection: withIcon,
    escapeMarkup: function (m) { return m; },
    minimumResultsForSearch: 10
  });
})();
</script>
@endpush

@endsection
