<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // Get all users from database
        return view('user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'slug' => Str::uuid() // Generate UUID for slug
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'User created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        return view('user-management.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('user-management.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        try {
            $user = User::where('slug', $slug)->firstOrFail();

            // Prevent deleting yourself using Auth facade
            if ($user->id === Auth::id()) {
                return redirect()->route('user-management.index')
                    ->with('error', 'You cannot delete your own account!');
            }

            $user->delete();

            return redirect()->route('user-management.index')
                ->with('success', 'User deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user-management.index')
                ->with('error', 'User not found');
        }
    }

    public function grantAdmin($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->is_admin = true;
        $user->save();

        return redirect()->back()->with('success', 'Admin privileges granted to ' . $user->name);
    }

    public function revokeAdmin($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->is_admin = false;
        $user->save();

        return redirect()->back()->with('success', 'Admin privileges revoked from ' . $user->name);
    }

    public function activate($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->is_active = true;
        $user->save();

        return redirect()->back()->with('success', $user->name . ' has been activated.');
    }

    public function deactivate($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $user->is_active = false;
        $user->save();

        return redirect()->back()->with('success', $user->name . ' has been deactivated.');
    }
}
