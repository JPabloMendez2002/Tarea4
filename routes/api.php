<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ContactosController;
use App\Http\Controllers\CorreosController;
use App\Http\Controllers\LoginController;
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

Route::post('login', [LoginController::class, 'loginUsuarios']);

Route::post('login/admin', [LoginController::class, 'loginAdmins']);

Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios'=>'IdUsuario']);

Route::resource('administrador', AdministradorController::class)->parameters(['administrador'=>'IdAdministrador']);

Route::resource('contactos', ContactosController::class)->parameters(['contactos'=>'IdContacto']);

Route::resource('telefonos', TelefonosController::class)->parameters(['telefonos'=>'IdTelefono']);

Route::resource('correos', CorreosController::class)->parameters(['correos'=>'IdCorreo']);

Route::get('contactos/telefonos/{IdContacto}', [TelefonosController::class, 'telefonosContacto']);

Route::get('contactos/correos/{IdContacto}', [TelefonosController::class, 'correosContacto']);

Route::get('contactos/individual/{IdUsuario}', [ContactosController::class, 'muestraContactos']);
