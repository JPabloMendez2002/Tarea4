<?php

namespace App\Http\Controllers;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'Apellidos' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $reglas);

        if ($validator->fails()) {
            $errores =  implode(" ", $validator->errors()->all());

            abort(code: 400, message: "Error de validacion: {$errores}");
        }else{
            try {
                $administrador = new Administrador();

                $administrador->Identificacion = $request->Identificacion;
                $administrador->Contrasena = sha1($request->Contrasena);
                $administrador->Nombre = $request->Nombre;
                $administrador->Apellidos = $request->Apellidos;
                $administrador->save();

                $mensaje = [
                    'Respuesta del Servidor' => "Administrador agregado correctamente",
                    'Datos creados' => $administrador
                ];

                return response()->json($mensaje, 201);
            } catch (\Throwable $th) {
                abort(code: 409, message: "El administrador '{$request->Identificacion}' ya se encuentra registrado");
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
         $administrador = Administrador::where('IdAdministrador', $request->IdAdministrador)->first();
 
         if (!empty($administrador)) {
             $mensaje = [
                 'IdAdministrador' => $administrador->IdAdministrador,
                 'Identificacion' => $administrador->Identificacion,
                 'Nombre' => $administrador->Nombre,
                 'Apellidos' => $administrador->Apellidos
             ];
 
             return response()->json($mensaje, 200);
         } else {
             abort(code: 404, message: "No se encontro el administrador con ID: {$request->IdAdministrador}");
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
