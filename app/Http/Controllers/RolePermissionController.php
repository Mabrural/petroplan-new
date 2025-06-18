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
            'permission' => 'required|string|in:admin_officer,operasion', // Added enum validation
        ]);

        try {
            $user = User::where('slug', $slug)->firstOrFail();

            // Check if user already has a permission
            if ($user->rolePermissions()->exists()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'This user already has a role assigned. Please revoke it first.');
            }

            // Create new role permission
            RolePermission::create([
                'user_id' => $user->id,
                'permission' => $request->permission,
            ]);

            return redirect()
                ->route('role-permissions.index')
                ->with('success', 'Role assigned successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('role-permissions.index')
                ->with('error', 'User not found.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to assign role: ' . $e->getMessage());
        }
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
