@extends('layouts.app')

@section('title', 'Edit Menu')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Menu</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Edit Menu</h5>
                <small class="text-muted">Ubah menu</small>
            </div>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('menus.update', $menu->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title" name="title"
                            value="{{ old('title', $menu->title) }}" required>
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
                                <option value="{{ $class }}" {{ old('icon', $menu->icon) == $class ? 'selected' : '' }}>
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
                        <input type="text"
                            class="form-control @error('route') is-invalid @enderror"
                            id="route" name="route"
                            value="{{ old('route', $menu->route) }}">
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
                            @foreach($menus as $m)
                                <option value="{{ $m->id }}" {{ old('parent_id', $menu->parent_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->title }}
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
                        <input type="number"
                            class="form-control @error('order') is-invalid @enderror"
                            id="order" name="order"
                            value="{{ old('order', $menu->order ?? 0) }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permission Name --}}
                    <div class="mb-3">
                        <label for="permission_name" class="form-label">Permission</label>
                        <input type="text"
                            class="form-control @error('permission_name') is-invalid @enderror"
                            id="permission_name" name="permission_name"
                            value="{{ old('permission_name', $menu->permission_name) }}">
                        <small class="text-muted">Contoh: menu-list</small>
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
    .select2-results__option .fa { margin-right:.5rem; width:1.25rem; text-align:center; }
    .select2-selection__rendered .fa { margin-right:.5rem; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    function formatOption (item) {
        if (!item.id) return item.text;
        var $node = $('<span><i class="' + item.id + '"></i> ' + item.text + '</span>');
        return $node;
    }
    $('#icon').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Pilih Icon --',
        allowClear: true,
        templateResult: formatOption,
        templateSelection: formatOption,
        escapeMarkup: function(m) { return m; }
    });
})();
</script>
@endpush

@endsection
