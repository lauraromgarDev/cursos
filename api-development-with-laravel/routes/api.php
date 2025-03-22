<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/






Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


// Rutas de autenticación globales
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener los datos del usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas accesibles para todos los usuarios, incluyendo usuarios normales
    Route::get('books', [BookController::class, 'index']); // Accesible para todos

    // Rutas solo accesibles por admin
    Route::middleware('CheckRole:admin')->group(function () {
        // Rutas para agregar, editar o eliminar libros
        Route::resource('books', BookController::class)->except(['create', 'edit', 'index']);
    });
});
