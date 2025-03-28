<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\FichasTecnicas;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecpecioLlamadas;
use App\Http\Controllers\VentasRecepcionCotisaciones;
use App\Http\Controllers\PdfController;

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


Route::get('/proyecto/{id}/pdf', [PdfController::class, 'generarPdf'])->name('proyecto.pdf');

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
    ->middleware('auth', 'nocache')->name('admin.usersView');

Route::get('admin/roles', [UserController::class, 'verRoles'])->middleware('auth', 'nocache')->name('admin.roles');

Route::get('profile/profileView', [UserController::class, 'verPerfil'])->middleware('auth', 'nocache')->name('profile.profileView');

Route::get('compras/familias/viewFamilias', [FamiliaController::class, 'index'])->middleware('auth', 'nocache')->name('compras.familias.viewFamilias');

Route::get('compras/familias/createFamilias', [FamiliaController::class, 'crearUsuario'])->middleware('auth', 'nocache')->name('compras.familias.createFamilias');

Route::get('compras/items/viewItems', [ItemController::class, 'index'])->middleware('auth', 'nocache')->name('compras.items.viewItems');

Route::get('compras/items/createItems', [ItemController::class, 'crearItem'])->middleware('auth', 'nocache')->name('compras.items.createItems');

Route::get('compras/familias/viewFamiliaEspecifica/{idfamilia}', [FamiliaController::class, 'verFamilia'])
    ->middleware('auth', 'nocache')
    ->name('compras.familias.viewFamiliaEspecifica');

Route::get('compras/familias/edicionFamilia/{idfamilia}', [FamiliaController::class, 'editarFamilia'])
    ->middleware('auth', 'nocache')
    ->name('compras.familias.edicionFamilia');

Route::get('compras/proveedores/viewProveedores', [ProveedorController::class, 'index'])->middleware('auth', 'nocache')->name('compras.proveedores.viewProveedores');

Route::get('compras/proveedores/createProveedores', [ProveedorController::class, 'crearProveedor'])->middleware('auth', 'nocache')->name('compras.proveedores.createProveedores');

Route::get('compras/proveedores/viewProveedorEspecifico/{idproveedor}', [ProveedorController::class, 'verProveedor'])
    ->middleware('auth', 'nocache')
    ->name('compras.proveedores.viewProveedorEspecifico');

Route::get('compras/proveedores/editProveedores/{idproveedor}', [ProveedorController::class, 'editProveedor'])
    ->middleware('auth', 'nocache')->name('compras.proveedores.editProveedores');

Route::get('compras/items/edicionItem/{idItem}', [ItemController::class, 'editItem'])->middleware('auth', 'nocache')->name('compras.items.edicionItem');

Route::post('compras/proveedores', [ProveedorController::class, 'store'])->middleware('auth', 'nocache')->name('compras.proveedores.store');

Route::get('compras/items/vistaEspecificaItem/{idItem}', [ItemController::class, 'ciewEspecItem'])->middleware('auth', 'nocache')->name('compras.items.vistaEspecificaItem');

Route::get('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

Route::get('mantenimiento/enconstruccion', [MantenimientoController::class, 'index'])->middleware('auth', 'nocache')->name('mantenimiento.enconstruccion');

//Aqui inicia lo de ventas
Route::get('ventas/clientes/recepcionLlamadas', [RecpecioLlamadas::class, 'index'])->middleware('auth', 'nocache')->name('ventas.clientes.recepcionLlamadas');

Route::post('ventas/clientes/recepcionLlamadas', [RecpecioLlamadas::class, 'store'])->middleware('auth', 'nocache')->name('ventas.clientes.recepcionLlamadas.store');

Route::get('ventas/clientes/vistaEspecificaCliente/{idCliente}', [RecpecioLlamadas::class, 'viewEspecCliente'])->middleware('auth', 'nocache')->name('ventas.clientes.vistaEspecificaCliente');

Route::get('ventas/clientes/vistaEspecProyecto/{idProyecto}', [RecpecioLlamadas::class, 'vistaEspecProyecto'])->middleware('auth', 'nocache')->name('ventas.clientes.vistaEspecProyecto');

Route::get('ventas/clientes/vistaEspecificaListaCotizar/{idLista}', [RecpecioLlamadas::class, 'vistaEspecificaListaCotizar'])->middleware('auth', 'nocache')->name('ventas.clientes.vistaEspecificaListaCotizar');

Route::get('ventas/clientes/gestionClientes', [RecpecioLlamadas::class, 'vista'])->middleware('auth', 'nocache')->name('ventas.clientes.gestionClientes');

Route::get('ventas/fichasTecnicas/fichasTecnicas', [FichasTecnicas::class, 'index'])->middleware('auth', 'nocache')->name('ventas.fichasTecnicas.fichasTecnicas');

Route::get('ventas/fichasTecnicas/vistaEspecificaItem/{idItem}', [FichasTecnicas::class, 'viewEspecItem'])->middleware('auth', 'nocache')->name('ventas.fichasTecnicas.fichaEspecificaItem');

Route::get('ventas/recepcionCotizaciones/recepcionCotizacion', [VentasRecepcionCotisaciones::class, 'index'])->middleware('auth', 'nocache')->name('ventas.recepcionCotizaciones.recepcionCotizacion');

Route::get('ventas/clientes/EditCliente/{idcliente}', [ClienteController::class, 'editCliente'])
    ->middleware('auth', 'nocache')->name('ventas.cliente.EditCliente');
