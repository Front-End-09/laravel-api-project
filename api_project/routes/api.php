<?php

use App\Http\Controllers\contentsController;
use App\Http\Controllers\subMenuController;
use App\Models\subMenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/menu',     [contentsController::class, 'indexMenu']);
Route::post('/menu',    [contentsController::class,'storeMenu']);
Route::delete('/menu/{id}', [contentsController::class,'deleteMenu']);
Route::put('/menu',    [contentsController::class,'updateMenu']);
Route::get('/menu/{id}', [contentsController::class,'detailMenu']);

// Sub Menu
Route::get('/subMenu',  [subMenuController::class,'indexSubMenu']);
Route::post('/subMenu', [subMenuController::class,'storeSubMenu']);
Route::put('/subMenu',  [subMenuController::class,'updateSubMenu']);
Route::get('subMenu/{id}', [subMenuController::class,'detailSubMenu']);
Route::delete('/subMenu/{id}', [subMenuModel::class,'deleteDataSubMenu']);
