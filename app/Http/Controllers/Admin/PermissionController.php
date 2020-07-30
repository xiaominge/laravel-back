<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest as PermissionRequest;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin.permission.verify']);
    }

    public function index()
    {
        $permissions = repository()->permission->allFormatPermissions();
        $permissions = collect($permissions)->map(function ($item) {
            return (object)$item;
        });
        return view('admin.permissions.index')->with('permissions', $permissions);
    }

    public function create()
    {
        $permissions = repository()->permission->allFormatPermissions();
        return view('admin.permissions.create', compact('permissions'));
    }

    public function store(PermissionRequest $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? '',
            'pid' => $request->pid,
            'route' => $request->route ?? '',
            'created_at' => time(),
            'updated_at' => time(),
        ];
        repository()->permission->m()->create($data);
        session()->flash('status', '创建成功');

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Request $request, $id)
    {
        try {
            $permission = repository()->permission->findById($id);
            $permissions = repository()->permission->allFormatPermissions();
            return view('admin.permissions.edit', compact('permission', 'permissions'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function update(PermissionRequest $request, $id)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? '',
            'pid' => $request->pid,
            'route' => $request->route ?? '',
            'add_time' => time()
        ];

        repository()->permission->m()->where('id', $id)->update($data);
        session()->flash('status', '更新成功');
        return redirect()->route('admin.permissions.index');
    }

    public function destroy($id)
    {
        try {
            // 检测此权限有没有在被使用
            $permission = repository()->permission->findById($id);
            $roleIds = $permission->roles()->pluck('roles.id')->toArray();
            if ($roleIds) {
                return user_business_handler()->fail('无法删除, 此权限正在被使用');
            }

            $allPermission = repository()->permission->all();
            $permissions = repository()->role->getPermissionsList([$permission->id], $allPermission);

            repository()->permission->m()
                ->whereIn('id', $permissions)
                ->update(['deleted_at' => time()]);
            return user_business_handler()->success();
        } catch (BusinessException $e) {
            return user_business_handler()->fail('删除失败');
        }
    }

}
