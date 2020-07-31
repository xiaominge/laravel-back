<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function mysql_xdevapi\getSession;

class ErrorController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('admin.common.error');
    }
}
