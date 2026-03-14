@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Menu</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Tambah Menu Baru</h5>
                <small class="text-muted">Menu sistem</small>
            </div>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @include('pages.admin.manage.menu._form', [
            'action' => route('menus.store'),
            'isEdit' => false,
            'menu' => null,
            'menus' => $menus,
            'icons' => $icons,
            'submitLabel' => 'Simpan'
        ])
    </div>
</div>
@endsection

@push('styles')
<style>
.select2-results__option .fa,
.select2-selection__rendered .fa {
  margin-right: .5rem;
  width: 1.25rem;
  text-align: center;
  font-size: 1rem;
  line-height: 1;
}

.select2-container--bootstrap4 .select2-selection--single {
  height: calc(2.5rem + 2px);
  padding: .5rem .75rem;
  display: flex;
  align-items: center;
  position: relative;
  border: 1px solid #d9dee3;
  border-radius: 0.375rem;
}
.select2-container--bootstrap4 .select2-selection__rendered {
  padding-left: 0;
  padding-right: 2rem;
  line-height: 1.25;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
}
.select2-container--bootstrap4 .select2-selection__arrow {
  height: 100%;
}
.select2-container--bootstrap4 .select2-selection__clear {
  position: absolute;
  right: .75rem;
  top: 50%;
  transform: translateY(-50%);
  margin-right: 0;
}

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
      iconClass = item.element.dataset.icon;
    } else {
      iconClass = item.id;
    }

    if (!iconClass) return $('<span>' + item.text + '</span>');
    return $('<span><i class="' + iconClass + '"></i> ' + item.text + '</span>');
  }

  var theme = 'bootstrap4'; // ganti ke 'bootstrap-5' bila pakai Bootstrap 5

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

<script>
$(document).ready(function () {
    $('#icon_image').on('change', function () {
        if (this.files.length === 0) {
            $('#preview_icon').addClass('d-none').attr('src', '#');
            return;
        }

        let reader = new FileReader();
        reader.onload = function (e) {
            $('#preview_icon')
                .removeClass('d-none')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });
});
</script>
@endpush
