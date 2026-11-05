<?php

namespace App\Http\Controllers\Admincp\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admincp\AdminLogs;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
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
        return view('admincp.admins.index')->with([
            'users' => $this->getAllUsers(),
        ]);
    }

    /**
     * Show the lenders edit page.
     */
    public function create()
    {
        return view('admincp.admins.add')->with([
            'roles' => $this->getAllRoles(),
        ]);
    }

    /**
     * Create new user
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
        ]);

        $user = new User();

        $user->first_name = $data['first_name'];
        $user->email = $data['email'];
        $user->last_name = $data['last_name'];
        $user->username = $data['username'];
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);

        $user->save();

        foreach ($request->permissions as $group => $active) {
            $user->givePermissionTo($group);
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Added admin "' . $data['first_name'] . ' ' . $data['last_name'] . '"']);

        return redirect(route('admincp.admins.index'))->with('success', 'Admin has been added!');
    }

    /**
     * Show the lenders edit page.
     */
    public function edit($id)
    {
        return view('admincp.admins.edit')->with([
            'user' => $this->getCurrentUser($id),
            'roles' => $this->getAllRoles(),
        ]);
    }

    /**
     * Update user function
     */
    public function update($id, Request $request)
    {
        $user = $this->getCurrentUser($id);

        $permissions = $user->getPermissionNames();
        foreach ($permissions as $permission) {
            $user->revokePermissionTo($permission);
        }

        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);

        if ($request->permissions) {
            foreach ($request->permissions as $group => $active) {
                $user->givePermissionTo($group);
            }
        }

        if ($request->password) {
            $user->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Updated admin "' . $user->first_name . ' ' . $user->last_name . '"']);

        return redirect(route('admincp.admins.index'))->with('success', 'Admin has been updated!');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = $this->getCurrentUser($id);

        if (Auth()->id() !== $user->id) {
            $user->delete();
        }

        AdminLogs::create(['user_id' => Auth()->id(), 'log' => 'Deleted admin "' . $user->first_name . ' ' . $user->last_name . '"']);

        return redirect(route('admincp.admins.index'))->with('success', 'Admin has been deleted!');
    }

    /**
     * Get current $id user
     */
    public function getCurrentUser($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Return all users with pagination
     */
    public function getAllUsers()
    {
        return User::paginate(20);
    }

    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        return Role::get();
    }
}
