<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{

    /**
     * @description consulta todos los productos que no tengan el borrado logico
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
      //Verifica no exista en la db
      $producto = Producto::withoutTrashed()->get();

      return response()->json([
        "data" => $producto
      ]);

    }

    /**
     * @description Crear los productos nuevos
     * @return JsonResponse
     * @param Request   
     */
    public function create(Request $request) : JsonResponse
    {
        //Verifica no exista en la db
        $exits = Producto::withoutTrashed()
        ->where("nombre", $request->nombre)
        ->exists();

        if($exits){
            return response()->json([
                "message" => "El producto ya existe en la base de datos"
            ]);
        }

        //Crea el nuevo producto
        DB::beginTransaction();

        try {
            $producto = new Producto();

            $producto->nombre = $request->nombre;
            $producto->stock = $request->stock;
            $producto->id_categoria = $request->id_categoria;
            $producto->precio = $request->precio;

            $producto->save();

            DB::commit();

            return response()->json([
                "message" => "Producto creado exitosamente"
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
     * @description Actualizar el producto
     * @param $id, @param Request $resquest
     * @return JsonResponse
     */
    public function update($id, Request $request) : JsonResponse
    {
        //Verificar que exista el producto
        $exist = Producto::withoutTrashed()
        ->where("id", $id)  
        ->exists();

        if(!$exist){
            return response()->json([
                "message" => "El producto ingresado no existe"
            ]);
        }

        //Actualiza el nuevo producto
        DB::beginTransaction();

        try {
            //Actualizar
            $update = Producto::findOrFail($id);
            
            $update->nombre = $request->nombre;
            $update->stock = $request->stock;
            $update->id_categoria = $request->id_categoria;
            $update->precio = $request->precio;

            $update->update();

            DB::commit();

            return response()->json([
                "message" => "Producto actualizado exitosamente"
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
     * @description Elimina el producto
     * @param $id
     * @param JsonResponse
     */
    public function delete($id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            $producto = Producto::findOrFail($id);
            $producto->delete();

            DB::commit();

            return response()->json([
                "message" => "Producto eliminado exitosamente"
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
