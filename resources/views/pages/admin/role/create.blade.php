@extends('layouts.app')

@section('title', 'Tambah hak Akses Baru')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Hak Akses</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0">Tambah Hak Akses Baru</h5>
            <small class="text-muted">Role dan hak akses sistem.</small>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center gap-2">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
        </div>

        <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Periksa kembali input Anda:
                <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Role</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label d-block mb-2"><strong>Permissions</strong></label>

                {{-- Toolbar --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <div class="input-group" style="max-width: 420px;">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" id="permSearch" class="form-control" placeholder="Cari permission...">
                </div>

                <div class="form-check ms-auto">
                    <input class="form-check-input" type="checkbox" id="permToggleAll">
                    <label class="form-check-label" for="permToggleAll">Pilih semua (yang terlihat)</label>
                </div>

                <span class="badge bg-primary" id="permSelectedCount">0 terpilih</span>
                </div>

                {{-- Daftar permission (kelompok per menu) --}}
                @php
                $groups = $permission->groupBy('menu_id');
                @endphp

                <div id="permGroups" class="accordion accordion-flush">
                @foreach($groups as $menuId => $perms)
                    @php
                    $menuTitle = optional($perms->first()->menu)->title ?? 'Tanpa Menu';
                    $groupKey  = $menuId ?? 'null';
                    @endphp

                    <div class="accordion-item perm-group" data-menu-id="{{ $groupKey }}">
                    <h2 class="accordion-header" id="heading-{{ $groupKey }}">
                        <button class="accordion-button collapsed d-flex align-items-center gap-3"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $groupKey }}"
                                aria-expanded="false"
                                aria-controls="collapse-{{ $groupKey }}">
                        <span class="fw-semibold">{{ $menuTitle }}</span>
                        <span class="ms-auto d-flex align-items-center gap-3">
                            <span class="badge bg-secondary group-selected-count" data-menu-id="{{ $groupKey }}">0</span>
                            <span class="text-muted small d-none d-sm-inline">Pilih semua</span>
                            <div class="form-check m-0">
                            <input class="form-check-input group-toggle-all" type="checkbox"
                                    id="groupToggle-{{ $groupKey }}" data-menu-id="{{ $groupKey }}">
                            </div>
                        </span>
                        </button>
                    </h2>
                    <div id="collapse-{{ $groupKey }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $groupKey }}" data-bs-parent="#permGroups">
                        <div class="accordion-body">
                            <div class="row g-2">
                                @foreach($perms as $p)
                                    @php
                                        $raw    = $p->name;
                                        $pretty = ucwords(str_replace(['.', '_', '-'], ' ', $raw));

                                        // deteksi ikon dari kata kunci aksi
                                        $icon = 'fa-circle-dot';
                                        if (preg_match('/\b(view|show|read|list|index)\b/i', $raw))   $icon = 'fa-eye';
                                        elseif (preg_match('/\b(create|store|add)\b/i', $raw))        $icon = 'fa-plus';
                                        elseif (preg_match('/\b(edit|update)\b/i', $raw))             $icon = 'fa-pen-to-square';
                                        elseif (preg_match('/\b(delete|destroy|remove)\b/i', $raw))   $icon = 'fa-trash';
                                        elseif (preg_match('/\b(export|download)\b/i', $raw))         $icon = 'fa-file-export';
                                        elseif (preg_match('/\b(import|upload)\b/i', $raw))           $icon = 'fa-file-import';
                                    @endphp

                                    <div class="col-12 col-sm-6 col-lg-4 col-xxl-3 perm-item"
                                        data-name="{{ strtolower($raw) }}"
                                        data-menu-id="{{ $groupKey }}">
                                        <div class="form-check m-0">
                                        {{-- sembunyikan input, pakai label sebagai kartu yang bisa diklik --}}
                                        <input class="form-check-input d-none perm-checkbox"
                                                type="checkbox"
                                                id="perm_{{ $p->id }}"
                                                name="permission[{{ $p->id }}]"
                                                value="{{ $p->id }}"
                                                {{ old("permission.$p->id") ? 'checked' : '' }}>

                                        <label for="perm_{{ $p->id }}"
                                                class="perm-card border rounded-3 p-3 w-100"
                                                data-bs-toggle="tooltip"
                                                title="{{ $raw }}">
                                            <div class="d-flex align-items-start gap-3">
                                            <span class="perm-icon rounded-circle">
                                                <i class="fa-solid {{ $icon }}"></i>
                                            </span>

                                            <div class="flex-grow-1 min-w-0">
                                                <span class="d-block fw-semibold text-truncate">{{ $pretty }}</span>
                                                <small class="text-muted d-block text-truncate">{{ $raw }}</small>
                                            </div>

                                            <span class="check-badge d-none">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                            </div>
                                        </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    </div>
                @endforeach
                </div>

                <div id="permEmptyState" class="text-center text-muted fst-italic py-3 d-none">
                Tidak ada permission yang cocok dengan pencarian.
                </div>

                @error('permission')
                <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
            </button>
            </form>
        </div>
        </div>
    </div>
</div>

@push('styles')
<style>
  .accordion-button .form-check { transform: translateY(1px); }
  .accordion-button .badge { min-width: 40px; }
</style>

<style>
  /* kartu permission */
  .perm-card{
    cursor:pointer;
    transition: border-color .2s, box-shadow .2s, background-color .2s;
  }
  /* efek saat dicentang */
  .form-check-input:checked + .perm-card{
    border-color: var(--bs-primary) !important;
    background-color: var(--bs-primary-bg-subtle, rgba(var(--bs-primary-rgb), .08));
    box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
  }

  /* ikon bulat di kiri */
  .perm-icon{
    width: 36px; height: 36px;
    display:flex; align-items:center; justify-content:center;
    background-color: var(--bs-gray-100, #f1f3f5);
    flex: 0 0 36px;
  }
  .form-check-input:checked + .perm-card .perm-icon{
    background-color: rgba(var(--bs-primary-rgb), .12);
  }

  /* badge cek di kanan */
  .check-badge{
    width:24px; height:24px;
    border-radius:999px;
    display:flex; align-items:center; justify-content:center;
    background-color: var(--bs-primary);
    color:#fff;
    flex: 0 0 24px;
  }
  .form-check-input:checked + .perm-card .check-badge{
    display:flex !important;
  }

  /* bantu truncation */
  .min-w-0{ min-width:0; }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    const $search       = $('#permSearch');
    const $items        = $('.perm-item');
    const $groups       = $('.perm-group');
    const $checkboxes   = $('.perm-checkbox');
    const $globalToggle = $('#permToggleAll');
    const $globalCount  = $('#permSelectedCount');

    // --- helpers berbasis "match" bukan :visible ---
    function groupItems(menuId) {
      return $items.filter('[data-menu-id="'+menuId+'"]');
    }
    function matchedItems(menuId) {
      return groupItems(menuId).filter('[data-match="1"]');
    }
    function matchedCheckboxes(menuId) {
      return matchedItems(menuId).find('.perm-checkbox');
    }

    function updateGlobalCounter() {
      const selected = $checkboxes.filter(':checked').length;
      const total    = $checkboxes.length;
      $globalCount.text(selected + ' terpilih');
      $globalToggle.prop('checked', selected === total && total > 0);
      $globalToggle.prop('indeterminate', selected > 0 && selected < total);
    }

    function updateGroupState(menuId) {
      const $all     = groupItems(menuId).find('.perm-checkbox');
      const selAll   = $all.filter(':checked').length;
      const totalAll = $all.length;

      // badge jumlah terpilih (TOTAL dalam grup)
      $('.group-selected-count[data-menu-id="'+menuId+'"]').text(selAll);

      // toggle per grup berdasarkan TOTAL (bukan matched saja)
      const $toggle = $('.group-toggle-all[data-menu-id="'+menuId+'"]');
      $toggle.prop('checked', selAll === totalAll && totalAll > 0);
      $toggle.prop('indeterminate', selAll > 0 && selAll < totalAll);

      // Sembunyikan header grup jika TIDAK ADA item yang match pencarian
      const hasMatch = matchedItems(menuId).length > 0;
      $('.perm-group[data-menu-id="'+menuId+'"]').toggle(hasMatch);
    }

    function updateAllGroupsState() {
      const menuIds = [...new Set($groups.map(function(){ return $(this).data('menu-id'); }).get())];
      menuIds.forEach(updateGroupState);
      // empty state global
      const anyMatch = $items.filter('[data-match="1"]').length > 0;
      $('#permEmptyState').toggleClass('d-none', anyMatch);
    }

    // Filter realtime (tanpa bergantung pada accordion visibility)
    function filterList() {
      const q = ($search.val() || '').toLowerCase().trim();
      $items.each(function () {
        const $el = $(this);
        const name = String($el.data('name') || '');
        const match = !q || name.indexOf(q) !== -1;
        $el.attr('data-match', match ? 1 : 0).toggle(match); // boleh tetap toggle untuk UX
      });
      updateAllGroupsState();
    }

    // Toggle global: hanya item YANG MATCH filter
    $globalToggle.on('change', function () {
      const checked = $(this).is(':checked');
      $items.filter('[data-match="1"]').find('.perm-checkbox').prop('checked', checked);
      updateAllGroupsState();
      updateGlobalCounter();
    });

    // Toggle per grup: hanya item YANG MATCH dalam grup tsb
    $(document).on('change', '.group-toggle-all', function () {
      const checked = $(this).is(':checked');
      const menuId  = $(this).data('menu-id');
      matchedCheckboxes(menuId).prop('checked', checked);
      updateGroupState(menuId);
      updateGlobalCounter();
    });

    // Perubahan tiap checkbox
    $checkboxes.on('change', function () {
      const menuId = $(this).closest('.perm-item').data('menu-id');
      updateGroupState(menuId);
      updateGlobalCounter();
    });

    // Pencarian
    $search.on('input', filterList);

    // --- Init: tandai semua item sebagai match ---
    $items.attr('data-match', 1);
    filterList();          // hitung state awal & tampilkan
    updateGlobalCounter(); // hitung total terpilih awal
  });
</script>

<script>
  $(function () {
    // Bootstrap tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
  });
</script>
@endpush
@endsection
