<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();
        $datos = [];

        foreach ($usuarios as $usuario) {
            $datos[] = [
                'IdUsuario' => $usuario->IdUsuario,
                'Identificacion' => $usuario->Identificacion,
                'Nombre' => $usuario->Nombre,
                'Apellidos' => $usuario->Apellidos,
                'Estado' => $usuario->Estado,
                'Contrasena' => $usuario->Contrasena,
                'Token' => $usuario->Token
            ];
        }

        if (!empty($datos)) {
            return response()->json($datos, 200);
        } else {
            $mensaje = [
                'Respuesta del Servidor' => "No hay datos por mostrar",
            ];

            return response()->json($mensaje, 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $reglas = [
            'Identificacion' => 'required|integer',
            'Contrasena' => 'required|string',
            'Nombre' => 'required|string',
            'Apellidos' => 'required|string',
            'Estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->all(), $reglas);

        if ($validator->fails()) {
            $errores =  implode(" ", $validator->errors()->all());

            abort(code: 400, message: "Error de validacion: {$errores}");
        } else {
            try {
                $usuario = new Usuario();

                $usuario->Identificacion = $request->Identificacion;
                $usuario->Contrasena = sha1($request->Contrasena);
                $usuario->Nombre = $request->Nombre;
                $usuario->Apellidos = $request->Apellidos;
                $usuario->Estado = $request->Estado;
                $usuario->save();

                $mensaje = [
                    'Respuesta del usuario' => "Usuario agregado correctamente",
                    'Datos creados' => $usuario
                ];

                return response()->json($mensaje, 201);
            } catch (\Throwable $th) {
                abort(code: 409, message: "El Usuario '{$request->Identificacion}' ya se encuentra registrado");
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $usuario = Usuario::where('IdUsuario', $request->IdUsuario)->first();

        if (!empty($usuario)) {
            $mensaje = [
                'IdUsuario' => $usuario->IdUsuario,
                'Identificacion' => $usuario->Identificacion,
                'Nombre' => $usuario->Nombre,
                'Apellidos' => $usuario->Apellidos
            ];

            return response()->json($mensaje, 200);
        } else {
            abort(code: 404, message: "No se encontro el usuario con ID: {$request->IdUsuario}");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $usuario = Usuario::find($request->IdUsuario);


        if (!empty($usuario)) {
            $reglas = [
                'Contrasena' => 'required|string',
                'Nombre' => 'required|string',
                'Apellidos' => 'required|string',
                'Estado' => 'required|boolean'
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());

                abort(code: 400, message: "No pueden existir campos vacíos: {$errores}");
            } else {
                $usuario->Contrasena = sha1($request->Contrasena);
                $usuario->Nombre = $request->Nombre;
                $usuario->Apellidos = $request->Apellidos;
                $usuario->Estado = $request->Estado;
                $usuario->save();

                $mensaje = [
                    'Respuesta del Servidor' => "Se actualizaron los datos correctamente",
                    'Nombre' => $usuario->Nombre,
                    'Apellidos' => $usuario->Apellidos,
                    'Contrasena' => $usuario->Contrasena,
                    'Estado' => $usuario->Estado
                ];

                return response()->json($mensaje, 200);
            }
        } else {
            abort(code: 404, message: "No se encontro un Usuario con ID: {$request->IdUsuario}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $existe = Usuario::find($request->IdUsuario);

        if (!empty($existe)) {

            try {
                $usuario = Usuario::destroy($request->IdUsuario);

                if ($usuario) {
                    $mensaje = [
                        'Respuesta del Servidor' => "Se elimino el Usuario con ID: {$request->IdUsuario}"
                    ];

                    return response()->json($mensaje, 200);
                }
            } catch (\Throwable $th) {
                abort(code: 500, message: "El usuario con ID '{$request->IdUsuario}' no se puede eliminar por que tiene registros enlazados a otra tabla");
            }
        } else {
            abort(code: 404, message: "No se encontro el Usuario con ID: {$request->IdUsuario}");
        }
    }
}
