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

    <form action="{{ $action }}" method="POST" id="formDonasi">
        @csrf

        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">

            {{-- Program --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Program Sedekah <span class="text-danger">*</span>
                </label>

                <select name="program_id"
                        id="program_id"
                        class="form-select form-select-sm"
                        required>
                    <option value="">-- Pilih Program --</option>

                    @foreach($programs as $program)
                        <option value="{{ $program->id }}"
                            {{ old('program_id', $item?->program_id) == $program->id ? 'selected' : '' }}
                            data-nama="{{ $program->nama_program }}"
                            data-deskripsi="{{ $program->deskripsi }}"
                            data-jenis="{{ $program->jenis_target }}"
                            data-target="{{ number_format($program->target_dana, 0, ',', '.') }}">
                            {{ $program->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <div id="programDetail" class="d-none mb-0">
                    <label class="form-label">
                        <strong>Jenis</strong>
                    </label>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span id="detailJenis"></span>

                        <span id="targetDanaContainer" class="d-none">
                            | <strong>Target:</strong>
                            Rp <span id="detailTarget"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Tanggal --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Tanggal Donasi <span class="text-danger">*</span>
                </label>

                <input type="date"
                    name="tanggal_donasi"
                    value="{{ old('tanggal_donasi', $item?->tanggal_donasi?->format('Y-m-d') ?? date('Y-m-d')) }}"
                    class="form-control form-control-sm"
                    required>
            </div>

            {{-- Bulan --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Bulan <span class="text-danger">*</span>
                </label>

                <select name="bulan"
                        class="form-select form-select-sm"
                        required>

                    @foreach([
                        1=>'Januari',
                        2=>'Februari',
                        3=>'Maret',
                        4=>'April',
                        5=>'Mei',
                        6=>'Juni',
                        7=>'Juli',
                        8=>'Agustus',
                        9=>'September',
                        10=>'Oktober',
                        11=>'November',
                        12=>'Desember'
                    ] as $id => $bulan)

                        <option value="{{ $id }}"
                            {{ old('bulan', $item?->bulan ?? date('n')) == $id ? 'selected' : '' }}>
                            {{ $bulan }}
                        </option>

                    @endforeach

                </select>
            </div>

            {{-- Tahun --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Tahun <span class="text-danger">*</span>
                </label>

                <select name="tahun"
                        class="form-select form-select-sm"
                        required>

                    @for($tahun = date('Y') + 1; $tahun >= date('Y') - 5; $tahun--)
                        <option value="{{ $tahun }}"
                            {{ old('tahun', $item?->tahun ?? date('Y')) == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endfor

                </select>
            </div>
        </div>

        <div class="row">
            {{-- Cari Donatur --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Nomor Kode Donatur <span class="text-danger">*</span>
                </label>

                <div class="input-group input-group-lg">
                    <input type="text"
                        id="nomor_kode"
                        class="form-control"
                        value="{{ old('nomor_kode', $item?->donatur?->nomor_kode) }}"
                        placeholder="Contoh: DON-000001">

                    <button type="button"
                            id="btnCariDonatur"
                            class="btn btn-primary">
                        <i class="fa fa-search me-1"></i>
                        Cari
                    </button>
                </div>

                <input type="hidden"
                    name="donatur_id"
                    id="donatur_id"
                    value="{{ old('donatur_id', $item?->donatur_id) }}">
            </div>                    
        </div>

        {{-- Detail Donatur --}}
       <div class="card border shadow-none mb-3"
            id="cardDonatur"
            style="{{ $item ? '' : 'display:none;' }}">
            <div class="card-header">
                <strong>Detail Donatur</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <table class="table table-sm mb-0">
                            <tr>
                                <th width="140">Kode</th>
                                <td id="detail_kode">
                                    {{ $item?->donatur?->nomor_kode ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td id="detail_nama">
                                    {{ $item?->donatur?->nama ?? '-' }}
                                </td>
                            </tr>

                            <tr>
                                <th>No HP</th>
                                <td id="detail_hp">
                                    {{ $item?->donatur?->no_hp ?? '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-sm mb-0">
                            <tr>
                                <th width="140">Alamat</th>
                                <td id="detail_alamat">
                                    {{ $item?->donatur?->alamat ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th width="140">Gang</th>
                                <td id="detail_gang">
                                    {{ $item?->donatur?->gang ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="detail_status">-</td>
                            </tr>
                        </table>
                    </div>

                </div>

            </div>
        </div>

        <div class="row">
        {{-- Nominal --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Nominal Donasi <span class="text-danger">*</span>
                </label>

                <input type="number"
                    name="nominal"
                    min="0"
                    step="0.01"
                    class="form-control form-control-sm"
                    value="{{ old('nominal', $item?->nominal) }}"
                    required>
            </div>
        </div>
        <div class="row">
            {{-- Keterangan --}}
            <div class="mb-3">
                <label class="form-label">
                    Keterangan
                </label>

                <textarea name="keterangan"
                    rows="3"
                    class="form-control form-control-sm">{{ old('keterangan', $item?->keterangan) }}</textarea>
            </div>
        </div>

        <button type="submit"
                class="btn btn-success"
                id="btnSimpan">
            <i class="fa-solid fa-floppy-disk me-1"></i>
             {{ $submitLabel }}
        </button>

    </form>

</div>