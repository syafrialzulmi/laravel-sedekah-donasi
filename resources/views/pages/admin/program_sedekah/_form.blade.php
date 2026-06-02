<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada beberapa masalah pada input Anda.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $action }}" method="POST">
            @csrf

            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row">

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_program" class="form-label">
                            Nama Program <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('nama_program') is-invalid @enderror"
                            id="nama_program"
                            name="nama_program"
                            value="{{ old('nama_program', $item->nama_program ?? '') }}"
                            required
                        >

                        @error('nama_program')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select form-select-sm @error('status') is-invalid @enderror"
                            required
                        >
                            <option value="draft"
                                {{ old('status', $item->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="aktif"
                                {{ old('status', $item->status ?? '') == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="selesai"
                                {{ old('status', $item->status ?? '') == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="ditutup"
                                {{ old('status', $item->status ?? '') == 'ditutup' ? 'selected' : '' }}>
                                Ditutup
                            </option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jenis_target" class="form-label">
                            Jenis Target <span class="text-danger">*</span>
                        </label>

                        <select
                            name="jenis_target"
                            id="jenis_target"
                            class="form-select form-select-sm @error('jenis_target') is-invalid @enderror"
                            required
                        >
                            <option value="target"
                                {{ old('jenis_target', $item->jenis_target ?? 'target') == 'target' ? 'selected' : '' }}>
                                Target Dana
                            </option>

                            <option value="sukarela"
                                {{ old('jenis_target', $item->jenis_target ?? '') == 'sukarela' ? 'selected' : '' }}>
                                Sukarela
                            </option>
                        </select>

                        @error('jenis_target')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" id="targetDanaWrapper">
                    <div class="mb-3">
                        <label for="target_dana" class="form-label">
                            Target Dana
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control form-control-sm @error('target_dana') is-invalid @enderror"
                            id="target_dana"
                            name="target_dana"
                            value="{{ old('target_dana', $item->target_dana ?? '') }}"
                        >

                        @error('target_dana')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Isi jika jenis target = Target Dana
                        </small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            rows="5"
                            class="form-control form-control-sm @error('deskripsi') is-invalid @enderror"
                        >{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>

                        @error('deskripsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(function() {

    function toggleTargetDana() {

        const jenis = $('#jenis_target').val();

        if (jenis === 'sukarela') {
            $('#targetDanaWrapper').hide();
            $('#target_dana').val('');
        } else {
            $('#targetDanaWrapper').show();
        }
    }

    toggleTargetDana();

    $('#jenis_target').on('change', function() {
        toggleTargetDana();
    });

});
</script>
@endpush