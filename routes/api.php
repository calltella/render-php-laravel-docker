<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/status', function () {
    return response()->json([
        'server' => 'Render Server',
        'status' => 'ok',
        'timestamp' => now()->toDateTimeString()
    ]);
});

/**
 *  X-API-KEY、UserIDでトークンを取得
 */
Route::get('/token/{id}', function (Request $request, $id) {
    // APIキーで認証（最低限のセキュリティ）
    if ($request->header('X-API-KEY') !== config('app.x_api_key')) {
        abort(403, 'Unauthorized');
    }
    \Log::info(config('app.x_api_key'));

    // ユーザー取得
    $user = User::findOrFail($id);

    // スコープ付きトークン発行
    $token = $user->createToken('custom-token', ['read', 'update'])->plainTextToken;

    return response()->json([
        'token' => $token,
        'scopes' => ['read', 'update']
    ]);
});

// token認証
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')
        ->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
        });
});