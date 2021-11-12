<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        echo 'Xin chào Admin, '. $user->name;
    }
}
