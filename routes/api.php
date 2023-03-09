<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\CorreosController;
use App\Http\Controllers\TelefonosController;
use App\Http\Controllers\UsuarioController;
use App\Models\Contactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('usuarios/login', [UsuarioController::class, 'login']);

Route::middleware('auth:sanctum')->resource('usuarios', UsuarioController::class)->parameters(['usuarios'=>'IdUsuario']);

Route::middleware('auth:sanctum')->resource('contactos', ContactosController::class)->parameters(['contactos'=>'IdContacto']);

Route::middleware('auth:sanctum')->resource('telefonos', TelefonosController::class)->parameters(['telefonos'=>'IdTelefono']);

Route::middleware('auth:sanctum')->resource('correos', CorreosController::class)->parameters(['correos'=>'IdCorreo']);
