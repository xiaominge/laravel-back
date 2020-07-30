<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth.admin']);
    }

    /**
     * 主页
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permissions = service()->permission->getLoginAdminPermission()
            ->sortByDesc('sort')->values();
        $topMenus = $permissions->where('pid', 0)->toArray();
        $permissionsGroupByPid = $permissions->groupBy('pid')->toArray();
//        dd($topMenus, $permissionsGroupByPid, $permissions->toArray());
        return view('admin.home.home', compact('topMenus', 'permissionsGroupByPid'));
    }

    public function welcome()
    {
        $statistics = [];
        $server = $_SERVER;
        $statistics['admin'] = repository()->admin->m()->where('deleted_at', 0)->count();
        $statistics['role'] = repository()->role->m()->where('deleted_at', 0)->count();
        return view('admin.home.welcome', compact('server', 'statistics'));
    }

    public function changePassword()
    {
        return view('admin.home.password');
    }

    public function doChangePassword(AdminRequest $request)
    {
        // 检查旧密码是否正确
        $admin = auth('admin')->user();
        if (!Hash::check($request->old_password, $admin->password)) {
            return user_business_handler()->fail('输入的旧密码不正确');
        }
        if ($request->password != $request->confirm_password) {
            return user_business_handler()->fail('两次输入的密码不一致');
        }

        $admin->password = bcrypt($request->password);
        $db = $admin->save();

        if ($db) {
            return user_business_handler()->success('', '密码修改成功');
        }
        return user_business_handler()->fail('密码修改失败');
    }
}
