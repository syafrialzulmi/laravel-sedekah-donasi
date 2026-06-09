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

                @if(!$isEdit || empty($item->isi))
                <div class="col-12 mb-3">
                    <div class="dropdown">
                        <button
                            class="btn btn-outline-primary dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown">

                            <i class="fa-solid fa-file-import me-2"></i>
                            Gunakan Template Default
                        </button>

                        <ul class="dropdown-menu">

                            <li>
                                <a href="javascript:void(0)"
                                class="dropdown-item btn-default-template"
                                data-template="donasi_baru">
                                    <i class="fa-solid fa-hand-holding-heart text-success me-2"></i>
                                    Donasi Baru
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)"
                                class="dropdown-item btn-default-template"
                                data-template="donasi_update">
                                    <i class="fa-solid fa-pen-to-square text-warning me-2"></i>
                                    Update Donasi
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)"
                                class="dropdown-item btn-default-template"
                                data-template="donasi_hapus">
                                    <i class="fa-solid fa-trash text-danger me-2"></i>
                                    Hapus Donasi
                                </a>
                            </li>

                        </ul>
                    </div>

                    <small class="text-muted">
                        Pilih salah satu template untuk mengisi form secara otomatis.
                    </small>
                </div>
                @endif

                {{-- Kode --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            Kode Template <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="kode"
                            class="form-control form-control-sm @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $item->kode ?? '') }}"
                            placeholder="Contoh : DONASI_BARU"
                            required>

                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Nama --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Template <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nama_template"
                            class="form-control form-control-sm @error('nama_template') is-invalid @enderror"
                            value="{{ old('nama_template', $item->nama_template ?? '') }}"
                            required>

                        @error('nama_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="aktif"
                            class="form-select form-select-sm">

                            <option value="1"
                                {{ old('aktif', $item->aktif ?? 1)==1 ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="0"
                                {{ old('aktif', $item->aktif ?? 1)==0 ? 'selected' : '' }}>
                                Non Aktif
                            </option>

                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">
                            Isi Template WA <span class="text-danger">*</span>
                        </label>
                    </div>

                    <textarea
                        rows="12"
                        id="isi"
                        name="isi"
                        class="form-control @error('isi') is-invalid @enderror"
                        required>{{ old('isi', $item->isi ?? '') }}</textarea>

                    @error('isi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bantuan --}}
                <div class="col-12">

                    <div class="alert alert-info">

                        <strong>Placeholder yang tersedia</strong>

                        <div class="mt-2">

                            @foreach($placeholders as $v)
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm m-1 btn-placeholder"
                                    data-value="{{ '{'.$v.'}' }}">

                                    {{ '{'.$v.'}' }}

                                </button>
                            @endforeach

                        </div>

                        <small class="d-block mt-2">
                            Klik placeholder untuk memasukkannya ke posisi kursor pada template.
                        </small>

                    </div>

                </div>

            </div>

            <button class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                {{ $submitLabel }}
            </button>

        </form>

    </div>
</div>

