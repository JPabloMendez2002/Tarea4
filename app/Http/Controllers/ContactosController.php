<?php

namespace App\Http\Controllers;

use App\Models\Contactos;
use App\Models\Correos;
use App\Models\Telefonos;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ContactosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$contactos = Contactos::Select('IdContacto', 'Nombre', 'Apellidos', 'Facebook', 'Instagram', 'Twitter')->get();
        // $telefonos = Contactos::JOIN('Telefonos', 'Telefonos.IdContacto', '=', 'Contactos.IdContacto')
        //     ->Select('Telefonos.IdContacto', 'Telefonos.Telefono')->get();

        // $correos =  Contactos::JOIN('Correos', 'Correos.IdContacto', '=', 'Contactos.IdContacto')
        //     ->Select('Correos.IdContacto', 'Correos.Correo')->get();

        
        // if (!empty($contactos)) {
        //     $listaDatos[] = [
        //         'Lista de Contactos' => $contactos
        //     ];


        $usuarios = Contactos::all();
        $datos = [];

        foreach ($usuarios as $usuario) {
            $datos[] = [
                'IdContacto' => $usuario->IdContacto,
                'Nombre' => $usuario->Nombre,
                'Apellidos' => $usuario->Apellidos,
                'Facebook' => $usuario->Facebook,
                'Instagram' => $usuario->Instagram,
                'Twitter' => $usuario->Twitter
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
        $usuario = Usuario::find($request->IdUsuario);

        if (!empty($usuario)) {
            $reglas = [

                'IdUsuario' => 'required|integer',
                'Nombre' =>  'required|string',
                'Apellidos' =>  'required|string',
                'Correo' => 'required|string',
                'Telefono' => 'required|string',
                'Facebook' =>  'required|string',
                'Instagram' =>  'required|string',
                'Twitter' => 'required|string'

            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());
                abort(code: 400, message: "Error de validacion: {$errores}");
            } else {
                $contacto = new Contactos();
                $contacto->IdUsuario  = $request->IdUsuario;
                $contacto->Nombre     = $request->Nombre;
                $contacto->Apellidos  = $request->Apellidos;
                $contacto->Facebook   = $request->Facebook;
                $contacto->Instagram  = $request->Instagram;
                $contacto->Twitter    = $request->Twitter;
                $contacto->save();

                $correo = new Correos();
                $correo->IdContacto =  $contacto->IdContacto;
                $correo->Correo     = $request->Correo;
                $correo->save();

                $telefono = new Telefonos();
                $telefono->IdContacto =  $contacto->IdContacto;
                $telefono->Telefono   = $request->Telefono;
                $telefono->save();

                $mensaje = [
                    'Respuesta del Servidor' => "Contacto agregado correctamente",
                    'Datos creados' => $contacto, $correo, $telefono
                ];

                return response()->json($mensaje, 201);
            }
        } else {
            abort(code: 404, message: "No se encontro un Usuario con ID: {$request->IdUsuario}");
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
            //
    }


    public function muestraContactos(Request $request)
    {

        $contactos= Contactos::where('IdUsuario', $request->IdUsuario)->get();
        foreach ($contactos as $usuario) {
            $datos[] = [
                'IdContacto' => $usuario->IdContacto,
                'Nombre' => $usuario->Nombre,
                'Apellidos' => $usuario->Apellidos,
                'Facebook' => $usuario->Facebook,
                'Instagram' => $usuario->Instagram,
                'Twitter' => $usuario->Twitter
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
        $contacto = Contactos::find($request->IdContacto);
      


        if (!empty($contacto)) {
            $reglas = [
          
                'Nombre' => 'required|string',
                'Apellidos' => 'required|string',
                'Facebook' => 'required|string',
                'Instagram' => 'required|string',
                'Twitter' => 'required|string'
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());

                abort(code: 400, message: "No pueden existir campos vacíos: {$errores}");
            } else {
                
    
                    $contacto->Nombre = $request->Nombre;
                    $contacto->Apellidos = $request->Apellidos;
                    $contacto->Facebook = $request->Facebook;
                    $contacto->Instagram = $request->Instagram;
                    $contacto->Twitter = $request->Twitter;
                    $contacto->save();

                    $mensaje = [
                        'Respuesta del Servidor' => "Se actualizaron los datos correctamente",
                        'IdUsuario Pertenece' => $contacto->IdUsuario,
                        'Nombre' => $contacto->Nombre,
                        'Apellidos' => $contacto->Apellidos,
                        'Facebook' => $contacto->Facebook,
                        'Instagram' => $contacto->Instagram,
                        'Twitter' => $contacto->Twitter
                    ];

                    return response()->json($mensaje, 200);
                
            }
        } else {
            abort(code: 404, message: "No se encontro un Contacto con ID: {$request->IdContacto}");
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
        $contacto = Contactos::destroy($request->IdContacto);

        if (!empty($contacto)) {
            $mensaje = [
                'Respuesta del Servidor' => "Se elimino el Contacto con ID: '{$request->IdContacto}' además de Correo(s) y Telefono(s) asociados"
            ];

            return response()->json($mensaje, 200);
        } else {
            abort(code: 404, message: "No se encontro el Contacto con ID: {$request->IdContacto}");
        }
    }
}
