<?php
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $allowedPageSizes = [5, 10, 20, 50];
        $ps = (int) $request->input('ps', 10);
        if (!in_array($ps, $allowedPageSizes, true)) {
            $ps = 10;
        }

        $q = trim((string) $request->input('q', ''));

        $data = User::query()
            ->with('roles') // cegah N+1 untuk badge role
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhereHas('roles', fn ($r) => $r->where('name', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate($ps)
            ->appends($request->only('ps','q')); // bawa q & ps di pagination

        return view('pages.admin.manage.user.index', [
            'data' => $data,
            'i'    => ($data->currentPage() - 1) * $data->perPage(),
        ]);
    }

    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();

        return view('pages.admin.manage.user.create',compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'no_hp' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'roles' => 'required|string',
            'username' => 'nullable|unique:users,username',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if ($request->hasFile('foto')) {
            $input['foto'] = $request->file('foto')->store('foto_users', 'public');
        }

        $input['created_by'] = auth()->id();

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    public function show($id): View
    {
        $user = User::find($id);

        return view('pages.admin.manage.user.show',compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('pages.admin.manage.user.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'password'      => 'nullable|same:confirm-password|min:6',
            'no_hp'         => 'required|string|max:15',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'roles'         => 'required|string',
            'username'      => 'nullable|unique:users,username,' . $id,
        ]);

        $user = User::find($id);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $input['foto'] = $request->file('foto')->store('foto_users', 'public');
        }

        $input['updated_by'] = auth()->id();

        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function showProfile()
    {
        $user = auth()->user();
        return view('pages.admin.manage.user.profilku', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username'      => 'nullable|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'password'  => 'nullable|same:confirm-password|min:6',
        ]);

        $data = $request->only(['name', 'email', 'no_hp', 'username']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_user', 'public');
            $data['foto'] = $path;
        }

        // Jika password diisi, hash; jika tidak, hapus dari array
        // if (!empty($data['password'])) {
        //     $data['password'] = Hash::make($input['password']);
        // } else {
        //     unset($data['password']);
        // }

        // Jika user masih aktif == 0 → password WAJIB diisi
        if ($user->is_active == 0) {

            // Validasi ulang: password wajib
            $request->validate([
                'password' => 'required|same:confirm-password|min:6',
            ], [
                'password.required' => 'Anda wajib mengganti password karena akun baru dibuat.',
            ]);

            $data['password'] = Hash::make($request->password);
            $data['is_active'] = 1;

        } else {
            // Jika user sudah aktif → password opsional
            if (!empty($request->password)) {
                $data['password'] = Hash::make($request->password);
            }
        }

        $data['updated_by'] = auth()->id();
        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
