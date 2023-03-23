<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Administrador;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $administrador = Administrador::where('Identificacion', $request->Identificacion)->first();
            try{

                if ($request->Identificacion == $usuario->Identificacion && sha1($request->Contrasena) == $usuario->Contrasena) {
                    $mensaje = [
                        'Usuario' => "Tipo1",
                        'ID' => $usuario->IdUsuario,
                    ];

                    return response()->json($mensaje, 200);

                } elseif ($request->Identificacion == $administrador->Identificacion && sha1($request->Contrasena) == $administrador->Contrasena) {
                    $mensaje = [
                        'Usuario' => "Tipo2",
                        'ID' => $administrador->IdUsuario
                    ];

                    return response()->json($mensaje, 200);
                }else{
                        $mensaje = [
                            'Respuesta del Servidor' => "Identificacion y/o contraseña incorrectos"
                        ];

                        return response()->json($mensaje, 404);
                }
            }catch(Exception $e){
                return response()->json($e, 500);
            }

        }
    }
}
