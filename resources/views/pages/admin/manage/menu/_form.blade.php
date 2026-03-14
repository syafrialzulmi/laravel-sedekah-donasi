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

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row small">
                {{-- Kolom kiri --}}
                <div class="col-md-6 small">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title', $menu->title ?? '') }}"
                            required
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Font Awesome)</label>
                        <select class="form-select form-select-sm @error('icon') is-invalid @enderror" id="icon" name="icon">
                            <option value="">-- Pilih Icon --</option>
                            @foreach ($icons as $class => $label)
                                <option value="{{ $class }}" {{ old('icon', $menu->icon ?? '') == $class ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih icon, tampilan akan menampilkan icon + label.</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon_image" class="form-label">Upload Image Icon (PNG/JPG)</label>

                        <input
                            type="file"
                            class="form-control form-control-sm @error('icon_image') is-invalid @enderror"
                            id="icon_image"
                            name="icon_image"
                            accept="image/png, image/jpeg"
                        >

                        @error('icon_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if($isEdit && !empty($menu->icon_image))
                            <div class="mt-2">
                                <p class="text-muted small mb-1">Gambar saat ini:</p>
                                <img
                                    id="current_icon"
                                    src="{{ asset('storage/' . $menu->icon_image) }}"
                                    class="img-thumbnail"
                                    style="max-height: 80px;"
                                    alt="Icon saat ini"
                                >
                            </div>
                        @endif

                        <div class="mt-2">
                            <img
                                id="preview_icon"
                                src="#"
                                alt="Preview"
                                class="img-thumbnail d-none"
                                style="max-height: 80px;"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="route" class="form-label">Route</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('route') is-invalid @enderror"
                            id="route"
                            name="route"
                            value="{{ old('route', $menu->route ?? '') }}"
                        >
                        <small class="text-muted">Contoh: menus.index</small>
                        @error('route')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-md-6 small">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent</label>
                        <select class="form-select form-select-sm @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                            <option value="">-- Tidak ada (Root) --</option>
                            @foreach($menus as $m)
                                @if(!$isEdit || $m->id !== $menu->id)
                                    <option
                                        value="{{ $m->id }}"
                                        data-icon="{{ $m->icon ?? '' }}"
                                        {{ old('parent_id', $menu->parent_id ?? '') == $m->id ? 'selected' : '' }}
                                    >
                                        {{ $m->title }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan</label>
                        <input
                            type="number"
                            class="form-control form-control-sm @error('order') is-invalid @enderror"
                            id="order"
                            name="order"
                            value="{{ old('order', $menu->order ?? 0) }}"
                        >
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="permission_name" class="form-label">Permission</label>
                        <input
                            type="text"
                            class="form-control form-control-sm @error('permission_name') is-invalid @enderror"
                            id="permission_name"
                            name="permission_name"
                            value="{{ old('permission_name', $menu->permission_name ?? '') }}"
                        >
                        <small class="text-muted">Contoh: menu-list</small>
                        @error('permission_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i> {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
</div>
