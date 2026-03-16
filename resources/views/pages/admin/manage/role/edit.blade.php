@extends('layouts.app')

@section('title', 'Ubah Hak Akses')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Hak Akses</h4>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div>
        <h5 class="mb-0">Ubah Hak Akses</h5>
        <small class="text-muted">Role dan hak akses sistem</small>
      </div>
      <a href="{{ route('roles.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center gap-2">
        <i class="fa fa-arrow-left me-1"></i> Kembali
      </a>
    </div>

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
          @csrf
          @method('PUT')

            @include('pages.admin.manage.role._form', [
            'role' => $role,
            'rolePermissionIds' => $rolePermissionIds ?? [],
            'submitLabel' => 'Simpan Perubahan'
          ])
        </form>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
  .accordion-button .form-check { transform: translateY(1px); }
  .accordion-button .badge { min-width: 40px; }

  .perm-card{
    cursor:pointer;
    transition: border-color .2s, box-shadow .2s, background-color .2s;
  }

  .form-check-input:checked + .perm-card{
    border-color: var(--bs-primary) !important;
    background-color: var(--bs-primary-bg-subtle, rgba(var(--bs-primary-rgb), .08));
    box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
  }

  .perm-icon{
    width: 36px; height: 36px;
    display:flex; align-items:center; justify-content:center;
    background-color: var(--bs-gray-100, #f1f3f5);
    flex: 0 0 36px;
  }

  .form-check-input:checked + .perm-card .perm-icon{
    background-color: rgba(var(--bs-primary-rgb), .12);
  }

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

    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(el => new bootstrap.Tooltip(el));

    function groupItems(menuId){ return $items.filter('[data-menu-id="'+menuId+'"]'); }
    function matchedItems(menuId){ return groupItems(menuId).filter('[data-match="1"]'); }
    function matchedCheckboxes(menuId){ return matchedItems(menuId).find('.perm-checkbox'); }

    function updateGlobalCounter(){
      const selected = $checkboxes.filter(':checked').length;
      const total    = $checkboxes.length;
      $globalCount.text(selected + ' terpilih');
      $globalToggle.prop('checked', selected === total && total > 0);
      $globalToggle.prop('indeterminate', selected > 0 && selected < total);
    }

    function updateGroupState(menuId){
      const $all   = groupItems(menuId).find('.perm-checkbox');
      const selAll = $all.filter(':checked').length;
      const total  = $all.length;

      $('.group-selected-count[data-menu-id="'+menuId+'"]').text(selAll);

      const $toggle = $('.group-toggle-all[data-menu-id="'+menuId+'"]');
      $toggle.prop('checked', selAll === total && total > 0);
      $toggle.prop('indeterminate', selAll > 0 && selAll < total);

      const hasMatch = matchedItems(menuId).length > 0;
      $('.perm-group[data-menu-id="'+menuId+'"]').toggle(hasMatch);
    }

    function updateAllGroupsState(){
      const menuIds = [...new Set($groups.map(function(){ return $(this).data('menu-id'); }).get())];
      menuIds.forEach(updateGroupState);
      const anyMatch = $items.filter('[data-match="1"]').length > 0;
      $('#permEmptyState').toggleClass('d-none', anyMatch);
    }

    function filterList(){
      const q = ($search.val() || '').toLowerCase().trim();
      $items.each(function(){
        const $el = $(this);
        const name = String($el.data('name') || '');
        const match = !q || name.indexOf(q) !== -1;
        $el.attr('data-match', match ? 1 : 0).toggle(match);
      });
      updateAllGroupsState();
    }

    $globalToggle.on('change', function(){
      const checked = $(this).is(':checked');
      $items.filter('[data-match="1"]').find('.perm-checkbox').prop('checked', checked);
      updateAllGroupsState();
      updateGlobalCounter();
    });

    $(document).on('change', '.group-toggle-all', function(){
      const checked = $(this).is(':checked');
      const menuId  = $(this).data('menu-id');
      matchedCheckboxes(menuId).prop('checked', checked);
      updateGroupState(menuId);
      updateGlobalCounter();
    });

    $checkboxes.on('change', function(){
      const menuId = $(this).closest('.perm-item').data('menu-id');
      updateGroupState(menuId);
      updateGlobalCounter();
    });

    $search.on('input', filterList);

    $items.attr('data-match', 1);
    filterList();
    updateGlobalCounter();
  });
</script>
@endpush
@endsection
