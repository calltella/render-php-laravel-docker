<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function (Request $request) {
    $user = User::find(1);
    $token = $user->createToken('test-token')->plainTextToken;

    return response()->json([
        'token' => $token
    ]);
});

