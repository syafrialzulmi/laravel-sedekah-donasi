@extends('layouts.app')

@section('title', 'Profilku')

@section('main')
<div class="container mt-4">
    <div class="card shadow p-4">
        <h4 class="mb-4">Profil Saya</h4>

        <div class="row">
            {{-- Kolom Kiri --}}
            <div class="col-md-4 border-end small">
                <h6 class="text-muted mb-3">Informasi Umum</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    @php $roles = $user->getRoleNames(); @endphp
                    @if($roles && $roles->count())
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($roles as $r)
                                <span class="badge rounded-pill bg-primary-subtle text-primary border">{{ $r }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="col-md-8 small">

                {{-- tampil alert jika $user->is_active = 0, maka wajib ubah password. update email, nomor hp, foto profil harus terisi --}}
                {{-- ALERT WAJIB UPDATE PROFIL --}}
                @if($user->is_active == 0)
                <div class="alert alert-warning d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                    <div>
                        <strong>Profil belum lengkap!</strong><br>
                        Silakan lengkapi email, nomor HP, foto, dan ganti password.
                    </div>
                </div>
                @endif

                <h6 class="text-muted mb-3">Informasi Akun</h6>

                {{-- Form Langsung Submit ke Server --}}
                <form id="formProfil" action="{{ route('profilku.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>Nama</label>
                        {{-- @if (!empty($user->rw) && !empty($user->rt)) --}}
                            {{-- READONLY --}}
                            <input type="text"
                                name="name"
                                class="form-control form-control-sm bg-light text-muted"
                                value="{{ $user->name }}"
                                readonly>
                        {{-- @else --}}
                            {{-- EDITABLE --}}
                            {{-- <input type="text"
                                name="name"
                                class="form-control form-control-sm"
                                value="{{ $user->name }}"> --}}
                        {{-- @endif --}}
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        {{-- @if (!empty($user->rw) && !empty($user->rt)) --}}
                            {{-- READONLY --}}
                            <input type="text"
                                name="username"
                                class="form-control form-control-sm bg-light text-muted"
                                value="{{ $user->username }}"
                                readonly>
                        {{-- @else --}}
                            {{-- EDITABLE --}}
                            {{-- <input type="text"
                                name="username"
                                class="form-control form-control-sm"
                                value="{{ $user->username }}"> --}}
                        {{-- @endif --}}
                    </div>

                    <div class="mb-3">
                        <label>Email (operator/ pengguna)</label>
                        <input type="email" name="email" class="form-control form-control-sm" value="{{ $user->email }}">
                    </div>

                    <div class="mb-3">
                        <label>No HP (operator/ pengguna)</label>
                        <input type="text" name="no_hp" class="form-control form-control-sm" value="{{ $user->no_hp }}">
                    </div>

                    <div class="mb-3">
                        <label>Foto Profil</label><br>
                        @if($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="foto" width="100" class="rounded mb-2">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" width="100" class="rounded mb-2">
                        @endif
                        <input type="file" name="foto" class="form-control form-control-sm mt-2">
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label for="password" class="form-label">Password

                            @if ($user->is_active == 1)
                            <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small>
                            @else
                            <small class="text-muted">(Wajib terisi)</small>
                            @endif
                        </label>
                        <div class="input-group input-group-sm input-group-merge">
                        <input
                            type="password"
                            id="password"
                            class="form-control form-control-sm @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="************"
                            aria-describedby="password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label for="confirm-password" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-sm input-group-merge" id="merge-confirm-password">
                        <input
                            type="password"
                            class="form-control form-control-sm @error('confirm-password') is-invalid @enderror"
                            id="confirm-password"
                            name="confirm-password"
                            placeholder="************"
                            aria-describedby="password">
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                        @error('confirm-password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

  // --- Validasi realtime password ---
  const password = $('#password');
  const confirmPassword = $('#confirm-password');

  confirmPassword.on('keyup', function() {
    if (confirmPassword.val() !== password.val()) {
      if (!$('#passwordMatchAlert').length) {
        $('#merge-confirm-password').after('<div id="passwordMatchAlert" class="text-danger small mt-1">⚠️ Password tidak cocok!</div>');
      }
    } else {
      $('#passwordMatchAlert').remove();
    }
  });

  password.on('keyup', function() {
    if (confirmPassword.val().length > 0) {
      confirmPassword.trigger('keyup');
    }
  });

});
</script>
@endpush
