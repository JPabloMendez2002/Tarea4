<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Administrador;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginUsuarios(Request $request)
    {
        $reglas = [
            'Identificacion' => 'required|integer',
            'Contrasena' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $reglas);

        if ($validator->fails()) {
            $errores =  implode(" ", $validator->errors()->all());

            abort(code: 400, message: "No pueden existir campos vacíos: {$errores}");
        } else {
            $usuario = Usuario::where('Identificacion', $request->Identificacion)->where('Estado', '=', 1)->first();

            $token = bin2hex(random_bytes(16));

            try {
                if ($request->Identificacion == $usuario->Identificacion && sha1($request->Contrasena) == $usuario->Contrasena) {
                    $mensaje = [
                        'ID' => $usuario->IdUsuario,
                        'Token' => $token
                    ];
                    return response()->json($mensaje, 200);
                } else {
                    $mensaje = [
                        'Respuesta del Servidor' => "Identificacion y/o contraseña incorrectos"
                    ];
                    return response()->json($mensaje, 404);
                }
            } catch (Exception $e) {
                $mensaje = [
                    'Respuesta del Servidor' => "Identificacion y/o contraseña incorrectos"
                ];
                return response()->json($mensaje, 404);
            }
        }
    }


    public function loginAdmins(Request $request)
    {
        $reglas = [
            'Identificacion' => 'required|integer',
            'Contrasena' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $reglas);

        if ($validator->fails()) {
            $errores =  implode(" ", $validator->errors()->all());
            abort(code: 400, message: "No pueden existir campos vacíos: {$errores}");
        } else {
            $administrador = Administrador::where('Identificacion', $request->Identificacion)->first();

            $token = bin2hex(random_bytes(16));

            try {

                if ($request->Identificacion == $administrador->Identificacion && sha1($request->Contrasena) == $administrador->Contrasena) {
                    $mensaje = [
                        'ID' => $administrador->IdAdministrador,
                        'Token' => $token
                    ];
                    return response()->json($mensaje, 200);
                } else {
                    $mensaje = [
                        'Respuesta del Servidor' => "Identificacion y/o contraseña incorrectos"
                    ];
                    return response()->json($mensaje, 404);
                }
            } catch (Exception $e) {
                $mensaje = [
                    'Respuesta del Servidor' => "Identificacion y/o contraseña incorrectos"
                ];
                return response()->json($mensaje, 404);
            }
        }
    }
}
