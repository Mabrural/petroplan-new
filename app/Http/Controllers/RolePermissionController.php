<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('rolePermissions')->get();
        return view('role-permission.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        return view('role-permission.create', compact('user'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'permission' => 'required|string|max:255',
        ]);

        $user = User::where('slug', $slug)->firstOrFail();

        RolePermission::create([
            'user_id' => $user->id,
            'permission' => $request->permission,
        ]);

        return redirect()->route('role-permissions.index')->with('success', 'Role assigned successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(RolePermission $rolePermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RolePermission $rolePermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RolePermission $rolePermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        // Cari user berdasarkan slug
        $user = User::where('slug', $slug)->firstOrFail();

        // Hapus semua rolePermissions milik user tersebut
        $user->rolePermissions()->delete();

        return redirect()->route('role-permissions.index')->with('success', 'All roles and permissions have been revoked.');
    }

}
