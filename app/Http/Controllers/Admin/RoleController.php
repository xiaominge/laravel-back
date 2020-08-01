<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $permissions = repository()->permission->allNestPermissions();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'key' => $request->key,
                'description' => $request->description,
                'created_at' => time(),
                'updated_at' => time(),
            ];
            $role = repository()->role->m()->create($data);

            $permissions = repository()->permission->m()->select(['id', 'pid'])->get();
            $permissions = repository()->permission->getSuperiorPermissions($request->permissions, $permissions);
            $permissions = array_unique($permissions);
            $role->permissions()->attach($permissions, [
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            DB::commit();

            return user_business_handler()->success('', '角色创建成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $permissions = repository()->permission->allNestPermissions();
        try {
            $role = repository()->role->findById($id);
        } catch (BusinessException $e) {
            return redirect()
                ->route('admin.error')
                ->withErrors([
                    'msg' => $e->getMessage(),
                ]);
        }
        $currentPermissions = $role->permissions->pluck('id')->toArray();
        $currentPermissions = implode(',', $currentPermissions);
        return view('admin.roles.edit', compact('role', 'permissions', 'currentPermissions'));
    }

    /**
     * @param RoleRequest $request
     * @param             $id
     *
     * @return
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $role = repository()->role->findById($id);
        } catch (BusinessException $e) {
            return user_business_handler()->fail($e->getMessage());
        }

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => time(),
        ];
        $permissions = repository()->permission->all();
        $permissions = repository()->permission->getSuperiorPermissions($request->permissions, $permissions);
        $permissions = array_unique($permissions);
        $addPermissions = [];
        foreach ($permissions as $p) {
            $addPermissions[$p] = [
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        if ($role->update($updateData) && $role->permissions()->sync($addPermissions)) {
            return user_business_handler()->success('', '角色更新成功');
        } else {
            return user_business_handler()->fail('角色更新失败');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // 检测此角色有没有在使用
            $role = repository()->role->findById($id);
            $adminIds = $role->admins->pluck('id')->toArray();
            if ($adminIds) {
                return user_business_handler()->fail('角色正在被使用，无法删除');
            }

            // 解除 角色和权限 之间的关系
            DB::beginTransaction();
            $role->permissions()->update(['role_permission.deleted_at' => time()]);
            $role->update(['deleted_at' => time()]);
            DB::commit();

            return user_business_handler()->success('', '角色删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }
}
