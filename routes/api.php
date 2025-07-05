<?php
   use App\Http\Controllers\PostController;
   use App\Http\Controllers\GalleryController;
   use App\Http\Controllers\BlogController;
   //use App\Http\Controllers\PortfolioController;
   use App\Http\Controllers\ProjectController;
   use App\Http\Controllers\DonationController;
   use App\Http\Controllers\AuthController;
   use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

   //admin part
   Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.api_token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // protected routes here
    Route::get('/blogs', [BlogController::class, 'index']);
   Route::post('/blogs', [BlogController::class, 'store']);
   Route::get('/blogs/{id}', [BlogController::class, 'show']);
   Route::put('/blogs/{id}', [BlogController::class, 'update']);
   Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
//gallery
Route::apiResource('gallery', GalleryController::class);
Route::put('/gallery/{id}', [GalleryController::class, 'update']);
   Route::apiResource('posts', PostController::class);
});

   Route::apiResource('projects', ProjectController::class);
//portfolios
//Route::get('/portfolios', [PortfolioController::class, 'index']);
 // blogs
   Route::get('/blogs', [BlogController::class, 'index']);
   Route::post('/blogs', [BlogController::class, 'store']);
   Route::get('/blogs/{id}', [BlogController::class, 'show']);
   Route::put('/blogs/{id}', [BlogController::class, 'update']);
   Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
//gallery
Route::apiResource('gallery', GalleryController::class);
Route::put('/gallery/{id}', [GalleryController::class, 'update']);
   Route::apiResource('posts', PostController::class);
   Route::middleware('auth:sanctum')->post('/admin/update-credentials', [AuthController::class, 'updateCredentials']);
   Route::middleware('auth:sanctum')->post('/admin/update-email', [AuthController::class, 'updateEmail']);
   Route::middleware('auth:sanctum')->post('/admin/create', [AuthController::class, 'createAdmin']);
//get the current email
Route::middleware('auth:sanctum')->get('/admin/profile', function (Request $request) {
    return response()->json([
        'email' => $request->user()->email
    ]);
});
//forget password

Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);



   //donation
   //Route::post('/donations/start', [DonationController::class, 'start']);
   