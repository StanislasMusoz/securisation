<?php

use App\Http\Controllers\CrayonController;
use App\Http\Controllers\SessionController;
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


Route::get('/', [CrayonController::class, 'index'])->name('index');

Route::get('/crayons', [CrayonController::class, 'index'])->name('crayons.index');
Route::get('/crayons/create', [CrayonController::class, 'create'])->name('crayons.create');
Route::post('/crayons', [CrayonController::class, 'store'])->name('crayons.store');
Route::get('/crayons/{id}/edit', [CrayonController::class, 'edit'])->name('crayons.edit');
Route::put('/crayons/{id}', [CrayonController::class, 'update'])->name('crayons.update');
Route::delete('/crayons/{id}', [CrayonController::class, 'destroy'])->name('crayons.destroy');

Route::get('/login', function (){return view('login');})->name('login');
Route::get('/register', function (){return view('register');})->name('register');
Route::post('/login', [SessionController::class, 'login'])->name('login2');
Route::post('/register', [SessionController::class, 'register'])->name('register2');
Route::get('/logout', [SessionController::class, 'logout'])->name('logout');

Route::post('/recherche',  [CrayonController::class, 'search']);
