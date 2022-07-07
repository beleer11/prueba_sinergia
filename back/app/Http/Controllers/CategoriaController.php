<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{

    /**
     * @description consulta todos los categorias que no tengan el borrado logico
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
      //Verifica no exista en la db
      $categoria = Categoria::withoutTrashed()->get();

      return response()->json([
        "data" => $categoria
      ]);

    }

    /**
     * @description Crear las categorias nuevas
     * @return JsonResponse
     * @param Request   
     */
    public function create(Request $request) : JsonResponse
    {
        //Verifica no exista en la db
        $exits = Categoria::withoutTrashed()
        ->where("nombre", $request->nombre)
        ->exists();

        if($exits){
            return response()->json([
                "message" => "La categoria ya existe en la base de datos"
            ]);
        }

        //Crea el nueva categoria
        DB::beginTransaction();

        try {
            $categoria = new Categoria();

            $categoria->nombre = $request->nombre;

            $categoria->save();

            DB::commit();

            return response()->json([
                "message" => "Categoria creada exitosamente"
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
     * @description Actualizar la categoria
     * @param $id, @param Request $resquest
     * @return JsonResponse
     */
    public function update($id, Request $request) : JsonResponse
    {
        //Verificar que exista la categoria
        $exist = Categoria::withoutTrashed()
        ->where("id", $id)  
        ->exists();

        if(!$exist){
            return response()->json([
                "message" => "La categoria ingresada no existe"
            ]);
        }

        //Actualiza la nueva categoria
        DB::beginTransaction();

        try {
            //Actualizar
            $update = Categoria::findOrFail($id);

            $update->nombre = $request->nombre;

            $update->update();

            DB::commit();

            return response()->json([
                "message" => "Categoria actualizada exitosamente"
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
     * @description Elimina la categoria
     * @param $id
     * @param JsonResponse
     */
    public function delete($id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            DB::commit();

            return response()->json([
                "message" => "Categoria eliminada exitosamente"
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
