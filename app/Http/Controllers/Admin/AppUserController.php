<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    public function index()
    {
        $users = \App\Models\AppUser::orderBy('last_active_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }
}
