<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FarmsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\VeterinariesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\FonctionsController;
use App\Http\Controllers\AnimalsController;
use App\Http\Controllers\DeseasesController;
use App\Http\Controllers\SensorsController;
use App\Http\Controllers\SensorsDataController;
use App\Http\Controllers\VisitorsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SupplierFarmController;
use App\Http\Controllers\VeterinaryFarmController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::resource('/farms', FarmsController::class);
Route::resource('/suppliers', SuppliersController::class);
Route::resource('/veterinaries', VeterinariesController::class);
Route::resource('/categories', CategoriesController::class);
Route::resource('/contracts', ContractsController::class);
Route::resource('/fonctions', FonctionsController::class);
Route::resource('/employees', EmployeesController::class);
Route::resource('/deseases', DeseasesController::class);
Route::resource('/animals', AnimalsController::class);
Route::resource('/sensors', SensorsController::class);
Route::resource('/sensorsdata', SensorsDataController::class);
Route::resource('/visitors', VisitorsController::class);
Route::resource('/posts', PostsController::class);
Route::resource('/comments', CommentsController::class);
Route::resource('/roles', RolesController::class);
Route::resource('/supplierfarm', SupplierFarmController::class);
Route::resource('/veterinaryfarm', VeterinaryFarmController::class);


Route::get('/veterinaryfarm/farms/{farm_id}', [VeterinaryFarmController::class, 'veterinaryByFarm']);

Route::get('/veterinaryfarm/veterinary/{veterinary_id}', [VeterinaryFarmController::class, 'farmBYVeterinary']);

//Route::post ( 'posts' , [ PostsController:: class , 'upload' ]) ;
