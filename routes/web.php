<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'active', 'admin'])->group(function () {
    // Resource route
    Route::resource('user-management', UserController::class)
        ->parameters(['user-management' => 'user:slug']);

    // Custom actions
    Route::post('/user-management/{user:slug}/grant-admin', [UserController::class, 'grantAdmin'])
        ->name('user-management.grant-admin');
    
    Route::post('/user-management/{user:slug}/revoke-admin', [UserController::class, 'revokeAdmin'])
        ->name('user-management.revoke-admin');

    Route::post('/user-management/{user:slug}/activate', [UserController::class, 'activate'])
        ->name('user-management.activate');

    Route::post('/user-management/{user:slug}/deactivate', [UserController::class, 'deactivate'])
        ->name('user-management.deactivate');
});


Route::middleware(['auth', 'verified', 'active', 'admin'])->group(function () {
    // Resource route untuk RolePermission
    // Manual override untuk create dengan slug
    Route::get('role-permissions/create/{slug}', [RolePermissionController::class, 'create'])
        ->name('role-permissions.create');

    Route::post('role-permissions/{slug}', [RolePermissionController::class, 'store'])
        ->name('role-permissions.store');

    Route::resource('role-permissions', RolePermissionController::class)->except(['store'])
    ->parameters(['role-permissions' => 'role_permission:slug']);
});

require __DIR__.'/auth.php';
