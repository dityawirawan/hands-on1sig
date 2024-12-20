<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Tugas1Controller;

use App\Http\Controllers\MapDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/map', [MapController::class, 'index']);

Route::get('/tugas1', [Tugas1Controller::class, 'index']);


Route::get('/latihan2', [MapDataController::class, 'latihan2']);







Route::get('/tugas2', [MapDataController::class, 'index'])->name('map.index');
Route::get('/api/markers', [MapDataController::class, 'getMarkers']);
Route::get('/api/polygons', [MapDataController::class, 'getPolygons']);
Route::post('/api/markers', [MapDataController::class, 'storeMarker']);
Route::post('/api/polygons', [MapDataController::class, 'storePolygon']);

Route::delete('/api/markers/{id}', [MapDataController::class, 'deleteMarker']);
Route::delete('/api/polygons/{id}', [MapDataController::class, 'deletePolygon']);
Route::get('/api/markers/{id}', [MapDataController::class, 'viewMarker']);
Route::get('/api/polygons/{id}', [MapDataController::class, 'viewPolygon']);




// Route::get('/tugas2', [MapDataController::class, 'index'])->name('map.index');

// Route::get('/api/markers', [MapDataController::class, 'getMarkers']);
// Route::get('/api/polygons', [MapDataController::class, 'getPolygons']);
// Route::post('/api/markers', [MapDataController::class, 'storeMarker']);
// Route::post('/api/polygons', [MapDataController::class, 'storePolygon']);



// Route::delete('/api/markers/{id}', [MapDataController::class, 'deleteMarker']);
// Route::delete('/api/polygons/{id}', [MapDataController::class, 'deletePolygon']);
// Route::get('/api/markers/{id}', [MapDataController::class, 'viewMarker']);
// Route::get('/api/polygons/{id}', [MapDataController::class, 'viewPolygon']);


// interactive -> digunakan untuk menampilakn view map dan form data
//Route::get('/interactive', [MapDataController::class, 'index'])->name('map.index');
// post markers -> digunakan untuk menyimpan data markers
//Route::post('/markers', [MapDataController::class, 'storeMarker'])->name('map.storeMarker');
// post polygon -> menyimpan data poligon
//Route::post('/polygons', [MapDataController::class, 'storePolygon'])->name('map.storePolygon');
// get data : mengambil data dari data spasial yang sudah disimpan ke database.
//Route::get('/data', [MapDataController::class, 'getData'])->name('map.getData');