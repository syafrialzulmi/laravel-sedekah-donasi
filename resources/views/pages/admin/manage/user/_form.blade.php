<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada beberapa kesalahan pada input Anda.
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ $action }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row small">
                {{-- Kolom Kiri --}}
                <div class="col-md-6 small">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name ?? '') }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username ?? '') }}"
                            required
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email ?? '') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('no_hp') is-invalid @enderror"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp ?? '') }}"
                            required
                        >
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input
                            type="file"
                            class="form-control form-control-sm @error('foto') is-invalid @enderror"
                            id="foto"
                            name="foto"
                            accept="image/*"
                        >
                        <small class="form-text text-muted">
                            Format: <strong>JPEG, PNG, JPG</strong>. Maksimal ukuran <strong>1 MB</strong>.
                        </small>

                        @if($isEdit && !empty($user->foto))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" class="rounded" width="100">
                            </div>
                        @endif

                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-6 small">
                    <div class="mb-3 form-password-toggle">
                        <label for="password" class="form-label">
                            Password
                            @if($isEdit)
                                <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small>
                            @endif
                        </label>
                        <div class="input-group input-group-merge input-group-sm">
                            <input
                                type="password"
                                id="password"
                                class="form-control form-control-sm @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="************"
                                {{ $isEdit ? '' : 'required' }}
                            >
                            <span class="input-group-text cursor-pointer toggle-password" data-target="#password">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label for="confirm-password" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-merge input-group-sm" id="merge-confirm-password">
                            <input
                                type="password"
                                class="form-control form-control-sm @error('confirm-password') is-invalid @enderror"
                                id="confirm-password"
                                name="confirm-password"
                                placeholder="************"
                                {{ $isEdit ? '' : 'required' }}
                            >
                            <span class="input-group-text cursor-pointer toggle-password" data-target="#confirm-password">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                        @error('confirm-password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="roles" class="form-label">Role</label>
                        <select
                            class="form-select form-select-sm @error('roles') is-invalid @enderror"
                            id="roles"
                            name="roles"
                            required
                        >
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    {{ old('roles', $selectedRole ?? '') == $value ? 'selected' : '' }}
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
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
$(document).ready(function () {
    const password = $('#password');
    const confirmPassword = $('#confirm-password');

    function validatePasswordMatch() {
        $('#passwordMatchAlert').remove();

        if (confirmPassword.val().length > 0 && confirmPassword.val() !== password.val()) {
            $('#merge-confirm-password').after(
                '<div id="passwordMatchAlert" class="text-danger small mt-1">⚠️ Password tidak cocok!</div>'
            );
        }
    }

    confirmPassword.on('keyup', validatePasswordMatch);
    password.on('keyup', validatePasswordMatch);

    $('.toggle-password').on('click', function () {
        const target = $($(this).data('target'));
        const icon = $(this).find('i');

        if (target.attr('type') === 'password') {
            target.attr('type', 'text');
            icon.removeClass('bx-hide').addClass('bx-show');
        } else {
            target.attr('type', 'password');
            icon.removeClass('bx-show').addClass('bx-hide');
        }
    });
});
</script>
@endpush
