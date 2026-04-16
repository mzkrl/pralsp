<?php

use App\Http\Controllers\c_berita;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('welcome');
});
Route::get('/berita', [c_berita::class, 'index']);
Route::get('/berita/create', [c_berita::class, 'create']);
Route::post('/berita/store', [c_berita::class, 'store']);
Route::get('/berita/delete/{id}', [c_berita::class, 'delete']);