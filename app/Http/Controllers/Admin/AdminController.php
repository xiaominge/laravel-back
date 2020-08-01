<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest as AdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin.permission.verify']);
    }

    public function index(Request $request)
    {
        $roles = repository()->role->getAdminRoles();
        $roleId = $request->id ?: $roles->first()->id;

        try {
            $role = repository()->role->findById($roleId);
        } catch (BusinessException $e) {
            return redirect()
                ->route('admin.error')
                ->withErrors([
                    'msg' => $e->getMessage(),
                ]);
        }
        $limit = $request->get('limit', 20);
        $admins = $role->admins()->paginate($limit);

        return view('admin.admins.index', compact('roleId', 'roles', 'admins'));
    }

    public function create()
    {
        $roles = [];
        $rolesCollect = repository()->role->getAdminRoles();
        foreach ($rolesCollect as $role) {
            $roles[] = [
                'value' => $role['id'],
                'name' => $role['name'],
            ];
        }
        return view('admin.admins.create')->with('roles', json_encode($roles));
    }

    public function destroy($id)
    {
        // 管理员假删除
        try {
            DB::beginTransaction();
            $admin = repository()->admin->findById($id);
            $admin->update(['deleted_at' => time()]);
            // 管理员与角色关系
            $admin->roles()->update(['admin_role.deleted_at' => time()]);
            DB::commit();
            return user_business_handler()->success('', '管理员删除成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }

    public function store(AdminRequest $request)
    {
        try {
            DB::beginTransaction();
            $admin = repository()->admin->m()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            // 创建管理员角色对应关系
            $admin->roles()->attach(explode(',', $request->role_id), [
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            DB::commit();

            return user_business_handler()->success('', '管理员创建成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $admin = repository()->admin->findById($id);
            $roleIds = $admin->roles->pluck('id')->toJson();
            $roles = [];
            $rolesCollect = repository()->role->getAdminRoles();
            foreach ($rolesCollect as $role) {
                $roles[] = [
                    'value' => $role['id'],
                    'name' => $role['name'],
                ];
            }
            return view('admin.admins.edit')
                ->with('admin', $admin)
                ->with('roleIds', $roleIds)
                ->with('roles', json_encode($roles));
        } catch (BusinessException $e) {
            return redirect()
                ->route('admin.error')
                ->withErrors([
                    'msg' => $e->getMessage(),
                ]);
        }
    }

    public function update(AdminRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $admin = repository()->admin->findById($id);
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => time(),
            ];
            if (!empty($request->password)) {
                $data['password'] = bcrypt($request->password);
            }
            $admin->update($data);
            // 管理员角色关联表
            $addRoles = [];
            $roleIds = explode(',', $request->role_id);
            foreach ($roleIds as $r) {
                $addRoles[$r] = [
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
            $admin->roles()->sync($addRoles);
            DB::commit();

            return user_business_handler()->success('', '管理员更新成功');
        } catch (\Exception $e) {
            DB::rollBack();
            return user_business_handler()->fail($e->getMessage());
        }
    }
}
