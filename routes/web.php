<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// 后台登录
Route::get('admin/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Admin\LoginController@login')->name('admin.login');

Route::group(
    [
        'namespace' => 'Admin',
        'middleware' => 'auth.admin',
        'prefix' => 'admin',
        'as' => 'admin.',
    ],
    function () {
        // 登出
        Route::get('logout', 'LoginController@logout')->name('logout');
        // 后台主页面
        Route::get('/', 'HomeController@index')->name('home');
        // 后台欢迎页
        Route::get('welcome', 'HomeController@welcome')->name('home.welcome');
        // 修改账号密码
        Route::get('password', 'HomeController@changePassword')->name('home.password.change');
        Route::put('password', 'HomeController@doChangePassword')->name('home.password.do-change');

        Route::get('error', 'ErrorController@index')->name('error');

        // 用户角色
        Route::get('roles', 'RoleController@index')->name('roles.index');
        Route::get('roles/create', 'RoleController@create')->name('roles.create');
        Route::post('roles', 'RoleController@store')->name('roles.store');
        Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
        Route::put('roles/{id}', 'RoleController@update')->name('roles.update');
        Route::delete('roles/{id}', 'RoleController@destroy')->name('roles.destroy');

        // 权限管理
        Route::get('permissions', 'PermissionController@index')->name('permissions.index');
        Route::get('permissions/create/{pid?}', 'PermissionController@create')->name('permissions.create');
        Route::post('permissions', 'PermissionController@store')->name('permissions.store');
        Route::get('permissions/{id}/edit', 'PermissionController@edit')->name('permissions.edit');
        Route::put('permissions/{id}', 'PermissionController@update')->name('permissions.update');
        Route::delete('permissions/{id}', 'PermissionController@destroy')->name('permissions.destroy');

        // 管理员管理
        Route::get('admins', 'AdminController@index')->name('admins.index');
        Route::get('admins/create', 'AdminController@create')->name('admins.create');
        Route::post('admins', 'AdminController@store')->name('admins.store');
        Route::get('admins/{id}/edit', 'AdminController@edit')->name('admins.edit');
        Route::put('admins/{id}', 'AdminController@update')->name('admins.update');
        Route::delete('admins/{id}', 'AdminController@destroy')->name('admins.destroy');
    });
