<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $permissions = service()->permission->getLoginAdminPermission();
        $topMenus = $permissions->where('pid', 0)->toArray();
        $permissionsGroupByPid = $permissions->groupBy('pid')->toArray();

        return view('admin.home.home', compact('topMenus', 'permissionsGroupByPid'));
    }
}
