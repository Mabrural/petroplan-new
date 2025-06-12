<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/check-email', function (Request $request) {
    $email = $request->query('email');
    $exists = \App\Models\User::where('email', $email)->exists();
    return response()->json(['exists' => $exists]);
});
