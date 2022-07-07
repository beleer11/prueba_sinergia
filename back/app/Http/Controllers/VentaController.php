<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    /**
     * @return JsonResponse
     * @param $request
     */
    public function create(Request $request) : jsonResponse
    {
        //Crea el nueva factura
        DB::beginTransaction();

        try {
            foreach($request->all() as $data){
                $create = new Venta();

                $create->descripcion = $data['descripcion'];
                $create->id_producto = $data['id_producto'];
                $create->id_cliente = $data['id_cliente'];
                $create->fecha_compra = $data['fecha_compra'];

                $create->save();
            }

            DB::commit();

            return response()->json([
                "message" => "Factura creada exitosamente"
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
