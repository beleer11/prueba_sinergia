<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    /**
     * @description consulta todos los clientes que no tengan el borrado logico
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
      //Verifica no exista en la db
      $cliente = Cliente::withoutTrashed()->get();

      return response()->json([
        "data" => $cliente
      ]);

    }

    /**
     * @description Crear los clientes nuevos
     * @return JsonResponse
     * @param Request   
     */
    public function create(Request $request) : JsonResponse
    {
        //Verifica no exista en la db
        $exits = Cliente::withoutTrashed()
        ->where("numero_documento", $request->numero_documento)
        ->exists();

        if($exits){
            return response()->json([
                "message" => "El cliente ya existe en la base de datos"
            ]);
        }

        //Crea el nuevo cliente
        DB::beginTransaction();

        try {
            $cliente = new Cliente();

            $cliente->nombre = $request->nombre;
            $cliente->numero_documento = $request->numero_documento;
            $cliente->cel = $request->cel;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->direccion = $request->direccion;

            $cliente->save();

            DB::commit();

            return response()->json([
                "message" => "Cliente creado exitosamente"
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                "cod_error" => $e->getCode(),
                "message"   => $e->getMessage()
            ]);
        }
    }

    /**
     * @description Actualizar el cliente
     * @param $id, @param Request $resquest
     * @return JsonResponse
     */
    public function update($id, Request $request) : JsonResponse
    {
        //Verificar que exista el cliente
        $exist = Cliente::withoutTrashed()
        ->where("id", $id)  
        ->exists();

        if(!$exist){
            return response()->json([
                "message" => "El cliente ingresado no existe"
            ]);
        }

        //Actualiza el nuevo cliente
        DB::beginTransaction();

        try {
            //Actualizar
            $update = Cliente::findOrFail($id);

            $update->nombre = $request->nombre;
            $update->numero_documento = $request->numero_documento;
            $update->cel = $request->cel;
            $update->fecha_nacimiento = $request->fecha_nacimiento;
            $update->direccion = $request->direccion;

            $update->update();

            DB::commit();

            return response()->json([
                "message" => "Cliente actualizado exitosamente"
            ]);
            
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                "cod_error" => $e->getCode(),
                "message"   => $e->getMessage()
            ]);
        }

    }

    /**
     * @description Elimina el cliente
     * @param $id
     * @param JsonResponse
     */
    public function delete($id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            DB::commit();

            return response()->json([
                "message" => "Cliente eliminado exitosamente"
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                "cod_error" => $e->getCode(),
                "message"   => $e->getMessage()
            ]);
        }
    }
}
