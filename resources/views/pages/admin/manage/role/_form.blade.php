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

<div class="mb-3">
  <label for="name" class="form-label">Nama Role</label>
  <input type="text"
         class="form-control form-control-sm @error('name') is-invalid @enderror"
         id="name"
         name="name"
         value="{{ old('name', $role->name ?? '') }}"
         required>
  @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label d-block mb-2"><strong>Permissions</strong></label>

  {{-- Toolbar --}}
  <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="input-group input-group-sm" style="max-width: 420px;">
      <span class="input-group-text"><i class="fa fa-search"></i></span>
      <input type="text" id="permSearch" class="form-control form-control-sm" placeholder="Cari permission...">
    </div>

    <div class="form-check ms-auto">
      <input class="form-check-input" type="checkbox" id="permToggleAll">
      <label class="form-check-label" for="permToggleAll">Pilih semua (yang terlihat)</label>
    </div>

    <span class="badge bg-primary" id="permSelectedCount">0 terpilih</span>
  </div>

  @php
    $grouped = $permission
        ->groupBy(function ($p) {
            return optional($p->menu->parent)->title ?? 'Tanpa Parent';
        })
        ->map(function ($parentGroup) {
            return $parentGroup->groupBy('menu_id');
        });

    $selectedPermissionIds = collect(old('permission', $rolePermissionIds ?? []))
      ->map(fn($v) => (int) $v)
      ->values()
      ->all();
  @endphp

  <div id="permGroups" class="accordion accordion-flush">
    @foreach($grouped as $parentTitle => $menus)
      <h5 class="mt-4 mb-2 fw-bold text-primary">{{ $parentTitle }}</h5>

      <div class="accordion accordion-flush ms-2">
        @foreach($menus as $menuId => $perms)
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
                    <input class="form-check-input group-toggle-all"
                           type="checkbox"
                           id="groupToggle-{{ $groupKey }}"
                           data-menu-id="{{ $groupKey }}">
                  </div>
                </span>
              </button>
            </h2>

            <div id="collapse-{{ $groupKey }}"
                 class="accordion-collapse collapse"
                 aria-labelledby="heading-{{ $groupKey }}"
                 data-bs-parent="#permGroups">
              <div class="accordion-body">
                <div class="row g-2">
                  @foreach($perms as $p)
                    @php
                      $raw    = $p->name;
                      $pretty = ucwords(str_replace(['.', '_', '-'], ' ', $raw));

                      $icon = 'fa-circle-dot';
                      if (preg_match('/\b(view|show|read|list|index)\b/i', $raw))   $icon = 'fa-eye';
                      elseif (preg_match('/\b(create|store|add)\b/i', $raw))        $icon = 'fa-plus';
                      elseif (preg_match('/\b(edit|update)\b/i', $raw))             $icon = 'fa-pen-to-square';
                      elseif (preg_match('/\b(delete|destroy|remove)\b/i', $raw))   $icon = 'fa-trash';
                      elseif (preg_match('/\b(export|download)\b/i', $raw))         $icon = 'fa-file-export';
                      elseif (preg_match('/\b(import|upload)\b/i', $raw))           $icon = 'fa-file-import';

                      $checked = in_array((int) $p->id, $selectedPermissionIds, true);
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-4 col-xxl-3 perm-item"
                         data-name="{{ strtolower($raw) }}"
                         data-menu-id="{{ $groupKey }}">
                      <div class="form-check m-0">
                        <input class="form-check-input d-none perm-checkbox"
                               type="checkbox"
                               id="perm_{{ $p->id }}"
                               name="permission[{{ $p->id }}]"
                               value="{{ $p->id }}"
                               {{ $checked ? 'checked' : '' }}>

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
    @endforeach
  </div>

  <div id="permEmptyState" class="text-center text-muted fst-italic py-3 d-none">
    Tidak ada permission yang cocok dengan pencarian.
  </div>

  @error('permission')
    <div class="text-danger small mt-2">{{ $message }}</div>
  @enderror
</div>

<div class="d-flex justify-content-end gap-2">
  <button type="submit" class="btn btn-primary">
    <i class="fa-solid fa-floppy-disk me-1"></i> {{ $submitLabel ?? 'Simpan' }}
  </button>
</div>
