<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Count related orders in SQL instead of loading every order model.
        // This is much smaller in memory and avoids N+1 queries in the view.
        $users = User::query()
            ->where('is_admin', false)
            ->withCount('orders')
            ->select(['id', 'name', 'email', 'created_at', 'is_admin'])
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }
}
