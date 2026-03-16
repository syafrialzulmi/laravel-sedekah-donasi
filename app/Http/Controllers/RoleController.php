<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $allowedPageSizes = [5, 10, 20, 50];
        $ps = (int) $request->input('ps', 10);
        if (!in_array($ps, $allowedPageSizes, true)) {
            $ps = 10;
        }

        $q = trim((string) $request->input('q', ''));

        $roles = Role::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate($ps)
            ->appends($request->only('ps', 'q')); // bawa query di pagination

        return view('pages.admin.manage.role.index', [
            'roles' => $roles,
            'i'     => ($roles->currentPage() - 1) * $roles->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = \App\Models\Permission::with('menu')
            ->orderBy('menu_id')
            ->orderBy('name')
            ->get();


        return view('pages.admin.manage.role.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);

        $rolePermissions = \App\Models\Permission::with('menu:id,title')
            ->select('permissions.*')
            ->join('role_has_permissions','role_has_permissions.permission_id','=','permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->orderBy('permissions.menu_id')
            ->orderBy('permissions.name')
            ->get();

        // kelompokkan per menu_id
        $groups = $rolePermissions->groupBy('menu_id');

        return view('pages.admin.manage.role.show', compact('role','rolePermissions','groups'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);

        // Ambil semua permission beserta menu untuk grouping
        $permission = \App\Models\Permission::with('menu:id,title')
            ->orderBy('menu_id')
            ->orderBy('name')
            ->get();

        // ID permission yang sudah dimiliki role (untuk pre-check)
        $rolePermissionIds = \DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id')
            ->all();

        return view('pages.admin.manage.role.edit', compact('role','permission','rolePermissionIds'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );

        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
