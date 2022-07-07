<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Clientes
Route::group(["prefix" => "cliente"], function(){

    Route::get("get", [ClienteController::class, "get"]);
    Route::post("create", [ClienteController::class, "create"]);
    Route::post("update/{id?}", [ClienteController::class, "update"]);
    Route::delete("delete/{id?}", [ClienteController::class, "delete"]);

});

//Productos
Route::group(["prefix" => "producto"], function(){

    Route::get("get", [ProductoController::class, "get"]);
    Route::post("create", [ProductoController::class, "create"]);
    Route::post("update/{id?}", [ProductoController::class, "update"]);
    Route::delete("delete/{id?}", [ProductoController::class, "delete"]);

});

//Categorias
Route::group(["prefix" => "categoria"], function(){

    Route::get("get", [CategoriaController::class, "get"]);
    Route::post("create", [CategoriaController::class, "create"]);
    Route::post("update/{id?}", [CategoriaController::class, "update"]);
    Route::delete("delete/{id?}", [CategoriaController::class, "delete"]);

});

//Ventas
Route::group(["prefix" => "venta"], function(){

    Route::post("create", [VentaController::class, "create"]);

});
