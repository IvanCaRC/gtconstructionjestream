<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\UserController;


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
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('admin/dashboardAdmin', [adminController::class, 'index'])->middleware('auth', 'nocache')->name('admin.dashboardAdmin');
Route::get('admin/users', [UserController::class, 'index'])->middleware('auth', 'nocache')->name('admin.users');

Route::get('admin/userView/{iduser}', [UserController::class, 'verUsuario'])
    ->middleware('auth', 'nocache')
    ->name('admin.usersView');

Route::get('admin/roles', [UserController::class, 'verRoles'])->middleware('auth', 'nocache')->name('admin.roles');

Route::get('profile/profileView', [UserController::class, 'verPerfil'])->middleware('auth', 'nocache')->name('profile.profileView');

Route::get('compras/familias/viewFamilias', [FamiliaController::class, 'index'])->middleware('auth', 'nocache')->name('compras.familias.viewFamilias');

Route::get('compras/familias/createFamilias', [FamiliaController::class, 'crearUsuario'])->middleware('auth', 'nocache')->name('compras.familias.createFamilias');


