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
        Route::post('logout', 'LoginController@logout')->name('logout');
        // 后台主页面
        Route::get('/', 'HomeController@index')->name('home');
        // 用户角色
        Route::get('api/roles', 'RoleController@data')->name('roles.data');
        Route::get('roles', 'RoleController@index')->name('roles.index');
        Route::get('roles/create', 'RoleController@create')->name('roles.create');
        Route::post('roles', 'RoleController@store')->name('roles.store');
        Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
        Route::put('roles/{id}', 'RoleController@update')->name('roles.update');
        Route::delete('roles/{id}', 'RoleController@destroy')->name('roles.destroy');

        // 权限管理
        Route::get('api/permissions', 'PermissionController@data')->name('permissions.data');
        Route::get('permissions', 'PermissionController@index')->name('permissions.index');
        Route::get('permissions/create', 'PermissionController@create')->name('permissions.create');
        Route::get('permissions/{id}/edit', 'PermissionController@edit')->name('permissions.edit');
        Route::delete('permissions/{id}', 'PermissionController@destroy')->name('permissions.destroy');
        Route::put('permissions/{id}', 'PermissionController@update')->name('permissions.update');
        Route::post('permissions', 'PermissionController@store')->name('permissions.store');

        // 管理员管理
        Route::get('api/admins', 'AdminController@data')->name('admins.data');
        Route::get('admins', 'AdminController@index')->name('admins.index');
        Route::get('admins/create', 'AdminController@create')->name('admins.create');
        Route::delete('admins/{id}', 'AdminController@destroy')->name('admins.destroy');
        Route::post('admins', 'AdminController@store')->name('admins.store');
        Route::get('admins/{id}/edit', 'AdminController@edit')->name('admins.edit');
        Route::put('admins/{id}', 'AdminController@update')->name('admins.update');
        Route::delete('admins/{id}', 'AdminController@destroy')->name('admins.destroy');
    });
