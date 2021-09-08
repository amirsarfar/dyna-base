<?php

use Amirsarfar\DynaBase\Http\Controllers\DynaTestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dyna', [DynaTestController::class, 'dyna']);

use Amirsarfar\DynaBase\Http\Controllers\PostController;
use Amirsarfar\DynaBase\Http\Controllers\PostRelatedController;
use Amirsarfar\DynaBase\Http\Controllers\TypeController;
use Amirsarfar\DynaBase\Models\Type;
use Illuminate\Http\Request;

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



//TODO CLEANUP
//! CLEANUP


Route::prefix('api/dyna/v1')->middleware('api')->group(function () {
    Route::apiResource('types', TypeController::class);
    $types = Type::all();
    foreach ($types as $type) {
        Route::apiResource($type->key, PostController::class);
        foreach ($type->definition as $attribute) {
            if ($attribute->type == 'relation' && $attribute->options->type_id != 0) {
                Route::get("/$type->key/{{$type->key}}/{$attribute->name}", [PostRelatedController::class, 'index'])->name("$type->key.$attribute->name.index");

                Route::get("/$type->key/{{$type->key}}/{$attribute->name}/{{$attribute->name}}", [PostRelatedController::class, 'show'])->name("$type->key.$attribute->name.show");

                Route::post("/$type->key/{{$type->key}}/add-{$attribute->name}", [PostRelatedController::class, 'add'.ucfirst($attribute->options->relation)])->name("$type->key.$attribute->name.add");

                Route::post("/$type->key/{{$type->key}}/remove-{$attribute->name}", [PostRelatedController::class, 'remove'.ucfirst($attribute->options->relation)])->name("$type->key.$attribute->name.remove");

                Route::post("/$type->key/{{$type->key}}/sync-{$attribute->name}", [PostRelatedController::class, 'sync'.ucfirst($attribute->options->relation)])->name("$type->key.$attribute->name.sync");
            }
        }
    }
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
