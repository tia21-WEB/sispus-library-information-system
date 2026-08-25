<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MobileBorrowingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;

// PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/categories', [BookController::class, 'categories']);
Route::post(
    '/borrowings',
    [MobileBorrowingController::class, 'store']
);

Route::get(
    '/my-borrowings/{userId}',
    [MobileBorrowingController::class, 'myBorrowings']
);
Route::get(
    '/clearance/{userId}',
    [MobileBorrowingController::class, 'clearanceStatus']
);
Route::get(
    '/clearance/{userId}/download',
    [MobileBorrowingController::class, 'downloadClearance']
);
Route::post(
    '/scan-return',
    [MobileBorrowingController::class, 'scanReturn']
);
Route::get(
    '/dashboard/{userId}',
    [DashboardController::class, 'index']
);
Route::post(
    '/request-return',
    [MobileBorrowingController::class,
    'requestReturn']
);
Route::get(
    '/profile/{id}',
    [ProfileController::class, 'show']
);
Route::get(
    '/ranking/{id}',
    [ProfileController::class, 'ranking']
);
Route::get(
    '/leaderboard/{role}',
    [ProfileController::class, 'leaderboard']
);
Route::post('/change-password',
    [ProfileController::class, 'changePassword']);
Route::post(
    '/report-lost-book',
    [MobileBorrowingController::class,
     'reportLostBook']
);
Route::post(
    '/update-profile',
    [ProfileController::class, 'updateProfile']
);
Route::post('/mobile/borrowings/report-lost', [MobileBorrowingController::class, 'reportLostBook']);
Route::put(
    '/borrowings/{id}/extension',
    [MobileBorrowingController::class, 'requestExtension']
);
    // PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/books', [BookController::class, 'store']);
});