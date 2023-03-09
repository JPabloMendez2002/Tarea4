<?php

namespace App\Http\Controllers;

use App\Models\Telefonos;
use App\Models\Contactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TelefonosController extends Controller
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

        $teleexiste = Telefonos::WHERE('IdContacto', "=", $contacto->IdContacto)
            ->Select('Telefono')
            ->get();

        for ($i = 0; $i < count($teleexiste); $i++) {
            if ($teleexiste[$i]['Telefono'] == $request->Telefono) {
                abort(code: 409, message: "Ya existe un Telefono con estos datos para el Contacto con ID '{$contacto->IdContacto}'");
            }
        }

        if (!empty($contacto)) {
            $reglas = [

                'IdContacto' => 'required|integer',
                'Telefono' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());
                abort(code: 400, message: "Error de validacion: {$errores}");
            } else {

                $telefono = new Telefonos();
                $telefono->IdContacto =  $contacto->IdContacto;
                $telefono->Telefono   = $request->Telefono;
                $telefono->save();

                $mensaje = [
                    'Respuesta del Servidor' => "Telefono agregado correctamente al Contacto con ID: {$request->IdContacto}",
                    'Datos creados' => $telefono
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
        $telefonos = Telefonos::find($request->IdTelefono);
        $contacto = Telefonos::WHERE('IdTelefono', "=", $request->IdTelefono)
            ->WHERE('IdContacto', "=", $telefonos->IdContacto)
            ->exists();
        $teleexiste = Telefonos::WHERE('IdContacto', "=", $telefonos->IdContacto)
            ->Select('Telefono')
            ->get();

        for ($i = 0; $i < count($teleexiste); $i++) {
            if ($teleexiste[$i]['Telefono'] == $request->Telefono) {
                abort(code: 409, message: "Ya existe un Telefono con estos datos para el Contacto con ID '{$telefonos->IdContacto}'");
            }
        }


        if (!empty($telefonos)) {
            $reglas = [
                'Telefono' => 'required|string'
            ];

            $validator = Validator::make($request->all(), $reglas);

            if ($validator->fails()) {
                $errores =  implode(" ", $validator->errors()->all());
                abort(code: 400, message: "Error de validacion: {$errores}");
            } else {
                if ($telefonos->IdContacto == $contacto) {

                    $telefonos->Telefono   = $request->Telefono;
                    $telefonos->save();

                    $mensaje = [
                        'Respuesta del Servidor' => "Se actualizo el Telefono correctamente",
                        'Datos actualizados' => $telefonos
                    ];

                    return response()->json($mensaje, 201);
                }
            }
        } else {
            abort(code: 404, message: "No se encontro un Telefono con ID: {$request->IdTelefono}");
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
        $telefono = Telefonos::destroy($request->IdTelefono);

        if (!empty($telefono)) {
            $mensaje = [
                'Respuesta del Servidor' => "Se elimino el Telefono con ID: {$request->IdTelefono}"
            ];

            return response()->json($mensaje, 200);
        } else {
            abort(code: 404, message: "No se encontro el Telefono con ID: {$request->IdTelefono}");
        }
    }
}
