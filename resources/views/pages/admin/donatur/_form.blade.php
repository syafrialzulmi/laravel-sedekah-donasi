<div class="card-body">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
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

            @if($isEdit)
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Kode Donatur
                    </label>

                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            name="nomor_kode"
                            class="form-control @error('nomor_kode') is-invalid @enderror"
                            value="{{ old('nomor_kode', $item->nomor_kode) }}"
                            readonly>
                    </div>

                    <small class="text-muted">
                        Nomor kode donatur tidak dapat diubah.
                    </small>
                </div>
            @else
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Kode Donatur
                        <span class="text-muted">(Otomatis)</span>
                    </label>

                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            name="nomor_kode"
                            class="form-control @error('nomor_kode') is-invalid @enderror"
                            value="{{ old('nomor_kode', $kodeDonatur ?? '') }}">

                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            id="btnGenerateKode">
                            <i class="fa-solid fa-rotate"></i>
                        </button>

                        @error('nomor_kode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <small class="text-muted">
                        Saran kode dibuat otomatis berdasarkan nomor terakhir.
                    </small>
                </div>
            @endif


            <div class="col-md-6 mb-3">
                <label class="form-label">Nama *</label>
                <input type="text"
                       name="nama"
                       class="form-control form-control-sm"
                       value="{{ old('nama', $item->nama ?? '') }}"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">No HP</label>
                <input type="text"
                       name="no_hp"
                       class="form-control form-control-sm"
                       value="{{ old('no_hp', $item->no_hp ?? '') }}">
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control form-control-sm"
                       value="{{ old('email', $item->email ?? '') }}">
            </div> --}}

            <div class="col-md-6 mb-3">
                <label class="form-label">Status *</label>
                <select name="status"
                        class="form-select form-select-sm">
                    <option value="aktif"
                        {{ old('status', $item->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif"
                        {{ old('status', $item->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Kecamatan</label>

                <input
                    type="text"
                    class="form-control form-control-sm"
                    value="{{ $kecamatan->kecamatan }}"
                    readonly>

                <input
                    type="hidden"
                    name="kecamatan_id"
                    value="{{ $kecamatan->id }}">
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label">Kecamatan</label>

                <select name="kecamatan_id"
                        id="kecamatan_id"
                        class="form-select form-select-sm">
                    <option value="">-- Pilih Kecamatan --</option>

                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}"
                            {{ old('kecamatan_id', $item->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                            {{ $kecamatan->kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <div class="col-md-4 mb-3">
                <label class="form-label">Desa</label>

                <input
                    type="text"
                    class="form-control form-control-sm"
                    value="{{ $desa->desa }}"
                    readonly>

                <input
                    type="hidden"
                    name="desa_id"
                    value="{{ $desa->id }}">
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label">Desa</label>

                <select name="desa_id"
                        id="desa_id"
                        class="form-select form-select-sm">
                    <option value="">-- Pilih Desa --</option>

                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}"
                            {{ old('desa_id', $item->desa_id ?? '') == $desa->id ? 'selected' : '' }}>
                            {{ $desa->desa }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            {{-- <div class="col-md-6 mb-3">
                <label class="form-label">Dukuh</label>
                <input type="text"
                       name="dukuh"
                       class="form-control form-control-sm"
                       value="{{ old('dukuh', $item->dukuh ?? '') }}">
            </div> --}}

            <div class="col-md-4 mb-3">
                <label class="form-label">Gang</label>

                <select name="gang" class="form-select form-select-sm">
                    <option value="">-- Pilih Gang --</option>

                    @for ($i = 1; $i <= 40; $i++)
                        <option value="{{ $i }}"
                            {{ old('gang', $item->gang ?? '') == $i ? 'selected' : '' }}>
                            Gang {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat"
                          rows="3"
                          class="form-control form-control-sm">{{ old('alamat', $item->alamat ?? '') }}</textarea>
            </div>

        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk me-1"></i>
            {{ $submitLabel }}
        </button>

    </form>

</div>