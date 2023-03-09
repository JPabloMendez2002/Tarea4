<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contactos;
use App\Models\Correos;
use Illuminate\Support\Facades\Validator;

class CorreosController extends Controller
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
        $contacto = Contactos::find($request->IdContacto);
        $correoexiste = Correos::WHERE('IdContacto', "=", $contacto->IdContacto)
            ->Select('Correo')
            ->get();

        for ($i = 0; $i < count($correoexiste); $i++) {
            if ($correoexiste[$i]['Correo'] == $request->Correo) {
                abort(code: 409, message: "Ya existe un E-Mail con estos datos para el Contacto con ID '{$contacto->IdContacto}'");
            }
        }

        if (!empty($contacto)) {
            $reglas = [

                'IdContacto' => 'required|integer',
                'Correo' => 'required|email',
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());
                abort(code: 400, message: "Error de validacion: {$errores}");
            } else {

                $correo = new Correos();
                $correo->IdContacto =  $contacto->IdContacto;
                $correo->Correo   = $request->Correo;
                $correo->save();

                $mensaje = [
                    'Respuesta del Servidor' => "E-Mail agregado correctamente al Contacto con ID: {$request->IdContacto}",
                    'Datos creados' => $correo
                ];

                return response()->json($mensaje, 201);
            }
        } else {
            abort(code: 404, message: "No se encontro un Contacto con ID: {$request->IdContacto}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $correos = Correos::find($request->IdCorreo);
        $contacto = Correos::WHERE('IdCorreo', "=", $request->IdCorreo)
            ->WHERE('IdContacto', "=", $correos->IdContacto)
            ->exists();

        $correoexiste = Correos::WHERE('IdContacto', "=", $correos->IdContacto)
            ->Select('Correo')
            ->get();

        for ($i = 0; $i < count($correoexiste); $i++) {
            if ($correoexiste[$i]['Correo'] == $request->Correo) {
                abort(code: 409, message: "Ya existe un E-Mail con estos datos para el Contacto con ID '{$correos->IdContacto}'");
            }
        }

        if (!empty($correos)) {
            $reglas = [
                'Correo' => 'required|email'
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());
                abort(code: 400, message: "Error de validacion: {$errores}");
            } else {
                if ($correos->IdContacto == $contacto) {

                    $correos->Correo   = $request->Correo;
                    $correos->save();

                    $mensaje = [
                        'Respuesta del Servidor' => "Se actualizo el E-Mail correctamente",
                        'Datos actualizados' => $correos
                    ];

                    return response()->json($mensaje, 201);
                }
            }
        } else {
            abort(code: 404, message: "No se encontro un E-Mail con ID: {$request->IdCorreo}");
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
        $correo = Correos::destroy($request->IdCorreo);

        if (!empty($correo)) {
            $mensaje = [
                'Respuesta del Servidor' => "Se elimino el E-Mail con ID: {$request->IdCorreo}"
            ];

            return response()->json($mensaje, 200);
        } else {
            abort(code: 404, message: "No se encontro el E-Mail con ID: {$request->IdCorreo}");
        }
    }
}
