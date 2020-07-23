<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin.permission.verify']);
    }

    public function index()
    {
        $limit = request('limit', 10);
        $roles = repository()->role->paginateGetAllRoles($limit);
        return view('admin.roles.index')->with('rolesData', $roles);
    }

    public function create()
    {
        // 查出所有权限
        $permissions = repository()->permission->allFormatPermissions();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        $permissions = repository()->permission->m()->select(['id', 'pid'])->get();
        $data = [
            'name' => $request->name,
            'key' => $request->key,
            'description' => $request->description,
            'created_at' => time(),
            'updated_at' => time(),
        ];

        $role = repository()->role->m()->create($data);
        $inputPermissions = explode(',', $request->permissions);
        $permissions = repository()->role->getSuperiorPermissions($inputPermissions, $permissions);
        $permissions = array_unique($permissions);
        $role->permissions()->attach($permissions, [
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        session()->flash('status', '创建成功');

        return redirect()->route('admin.roles.index');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $permissions = repository()->permission->allFormatPermissions();
        try {
            $role = repository()->role->findById($id);
        } catch (BusinessException $e) {
            return back()->with('status', $e->getMessage());
        }
        $currentPermissions = $role->permissions()->pluck('permission.id')->toArray();
        $currentPermissions = implode(',', $currentPermissions);
        return view('admin.roles.edit', compact('role', 'permissions', 'currentPermissions'));
    }

    /**
     * @param RoleRequest $request
     * @param             $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $role = repository()->role->findById($id);
        } catch (BusinessException $e) {
            return back()->with('status', $e->getMessage());
        }

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => time()
        ];
        $permissions = repository()->permission->all();
        $inputPermissions = explode(',', $request->permissions);
        $permissions = repository()->role->getSuperiorPermissions($inputPermissions, $permissions);
        $permissions = array_unique($permissions);
        $addPermissions = [];
        foreach ($permissions as $p) {
            $addPermissions[$p] = [
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        if ($role->update($updateData) && $role->permissions()->sync($addPermissions)) {
            session()->flash('status', '更新成功');
        } else {
            session()->flash('status', '更新失败');
        }
        return redirect()->route('admin.roles.index');
    }

    public function destroy(Request $request, $id)
    {
        try {
            // 检测此角色有没有在使用
            $role = repository()->role->findById($id);
            $adminIds = $role->admins()->pluck('admin.id')->toArray();
            if ($adminIds) {
                return user_business_handler()->fail('无法删除, 此角色正在被使用');
            }

            // 解除 角色和权限 之间的关系
            DB::beginTransaction();
            $role->permissions()->update(['role_permission.deleted_at' => time()]);
            $role->update(['deleted_at' => time()]);
            DB::commit();
            return user_business_handler()->success();
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }
}
