@extends('layouts.app')

@section('title', 'Edit Roles')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Roles Management</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Edit Roles</h5>
                <small class="text-muted">Ubah roles sistem.</small>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
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

                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block"><strong>Permissions</strong></label>

                        {{-- Toolbar --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <div class="input-group" style="max-width: 360px;">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="permSearch" class="form-control" placeholder="Cari permission...">
                            </div>

                            <div class="form-check ms-auto">
                            <input class="form-check-input" type="checkbox" id="permToggleAll">
                            <label class="form-check-label" for="permToggleAll">Pilih semua</label>
                            </div>

                            <span class="badge bg-primary" id="permSelectedCount">0 terpilih</span>
                        </div>

                        {{-- Daftar permission --}}
                        <div id="permList"
                            class="row g-2"
                            style="max-height: 360px; overflow:auto; border: 1px solid #e9ecef; border-radius: .5rem; padding: .75rem;">
                            @foreach($permission as $value)
                            <div class="col-12 col-sm-6 col-lg-4 perm-item" data-name="{{ strtolower($value->name) }}">
                                <div class="form-check border rounded p-2 h-100">
                                @php
                                    $isChecked = old("permission.$value->id") || in_array($value->id, $rolePermissions ?? []);
                                @endphp
                                <input
                                    class="form-check-input perm-checkbox"
                                    type="checkbox"
                                    id="perm_{{ $value->id }}"
                                    name="permission[{{ $value->id }}]"
                                    value="{{ $value->id }}"
                                    {{ $isChecked ? 'checked' : '' }}
                                >
                                <label class="form-check-label w-100" for="perm_{{ $value->id }}">
                                    {{ $value->name }}
                                </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @error('permission')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        </div>

                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
  $(function () {
    const $root       = $(document); // scope global bila halaman partial
    const $search     = $('#permSearch');
    const $list       = $('#permList');
    const $items      = $list.find('.perm-item');
    const $toggleAll  = $('#permToggleAll');
    const $checkboxes = $list.find('.perm-checkbox'); // batasi ke dalam list
    const $counter    = $('#permSelectedCount');

    function updateCounter() {
      const selected = $checkboxes.filter(':checked').length;
      const total    = $checkboxes.length;
      $counter.text(selected + ' terpilih');
      $toggleAll.prop('checked', selected === total && total > 0);
      $toggleAll.prop('indeterminate', selected > 0 && selected < total);
    }

    function filterList() {
      const q = ($search.val() || '').toLowerCase().trim();
      $items.each(function () {
        const $el = $(this);
        const name = String($el.data('name') || '');
        $el.toggle(name.indexOf(q) !== -1);
      });
    }

    // Toggle semua item yang SEDANG terlihat (sesuai filter)
    $toggleAll.on('change', function () {
      const checked = $(this).is(':checked');
      $items.filter(':visible').find('.perm-checkbox').prop('checked', checked);
      updateCounter();
    });

    // Hitung saat checkbox berubah
    $checkboxes.on('change', updateCounter);

    // Filter realtime
    $search.on('input', filterList);

    // Init
    updateCounter();
  });
</script>
@endpush

@endsection
