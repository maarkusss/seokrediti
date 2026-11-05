<?php

namespace App\Http\Controllers\Admincp\AdminLogs;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use App\User;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admincp.adminlogs.index')->with([
            'adminlogs' => $this->getAllLogs(),
            'users' => $this->getAllUsers(),
        ]);
    }
    /**
     * Return all users with pagination
     */
    public function getAllLogs()
    {
        return AdminLogs::orderby('created_at', 'desc')->paginate(20);
    }
    /**
     * Return all users with pagination
     */
    public function getAllUsers()
    {
        return User::all();
    }
}
