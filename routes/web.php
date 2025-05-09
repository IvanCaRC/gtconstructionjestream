<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\Cotisaciones;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\FichasTecnicas;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemCotizar;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ordenVentas;
use App\Http\Controllers\RecpecioLlamadas;
use App\Http\Controllers\VentasRecepcionCotisaciones;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PDFListaController;
use App\Http\Controllers\PDFCotizacionController;
use App\Http\Controllers\PDFOrdenCompraController;
use App\Http\Controllers\PDFOrdenVentaController;

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

Route::get('/api/projects', [ChartController::class, 'getProjectData']);

Route::get('/proyecto/{id}/pdf', [PdfController::class, 'generarPdf'])->name('proyecto.pdf');

Route::get('/proyecto/{id}/pdf-lista', [PDFListaController::class, 'generarPDFLista'])->name('proyecto.pdf-lista');

Route::get('/proyecto/{id}/pdf-cotizacion', [PDFCotizacionController::class, 'generarPDFCotizacion'])->name('proyecto.pdf-cotizacion');

Route::get('/proyecto/{id}/pdf-orden-venta', [PDFOrdenVentaController::class, 'generarPDFOrdenVenta'])->name('proyecto.pdf-orden-venta');

Route::get('/proyecto/{cotizacionId}/{proveedorId}/pdf-orden-compra', [PDFOrdenCompraController::class, 'generarOrdenCompraPDF'])->name('proyecto.pdf-orden-compra');

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

Route::get('admin/cancelaciones', [UserController::class, 'verCancelaciones'])->middleware('auth', 'nocache')->name('admin.cancelaciones');

Route::get('profile/profileView', [UserController::class, 'verPerfil'])->middleware('auth', 'nocache')->name('profile.profileView');

Route::get('compras/familias/viewFamilias', [FamiliaController::class, 'index'])->middleware('auth', 'nocache')->name('compras.familias.viewFamilias');

Route::get('compras/familias/createFamilias', [FamiliaController::class, 'crearUsuario'])->middleware('auth', 'nocache')->name('compras.familias.createFamilias');


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

Route::get('compras/items/viewItems', [ItemController::class, 'index'])->middleware('auth', 'nocache')->name('compras.items.viewItems');

Route::get('compras/cotisaciones/verCotisaciones', [Cotisaciones::class, 'index'])->middleware('auth', 'nocache')->name('compras.cotisaciones.verCotisaciones');

Route::get('compras/cotisaciones/verMisCotisaciones', [Cotisaciones::class, 'verMisCotisaciones'])->middleware('auth', 'nocache')->name('compras.cotisaciones.verMisCotisaciones');

Route::get('compras/catalogoCotisacion/catalogoItem', [ItemCotizar::class, 'index'])->middleware('auth', 'nocache')->name('compras.catalogoCotisacion.catalogoItem');

Route::get('compras/cotisaciones/verCarritoCotisaciones/{idCotisacion}', [Cotisaciones::class, 'verCarritoCotisaciones'])->middleware('auth', 'nocache')->name('compras.cotisaciones.verCarritoCotisaciones');

Route::get('compras/catalogoCotisacion/vistaEspecificaItemCotizar/{idItem}', [ItemCotizar::class, 'vistaEspecificaDeCotisacion'])->middleware('auth', 'nocache')->name('compras.catalogoCotisacion.vistaEspecificaItemCotizar');

Route::get('ventas/ordenesVenta/vistaOrdenVenta', [ordenVentas::class, 'index'])->middleware('auth', 'nocache')->name('ventas.ordenesVenta.vistaOrdenVenta');

Route::get('finanzas/ordenesVenta/vistaOrdenVentaFin', [FinanzasController::class, 'ordenesVenta'])->middleware('auth', 'nocache')->name('finanzas.ordenesVenta.vistaOrdenVentaFin');

Route::get('compras/cotisaciones/verOrdenesCompra', [ComprasController::class, 'ordenesCompra'])->middleware('auth', 'nocache')->name('compras.cotisaciones.verOrdenesCompra');

Route::get('compras/cotisaciones/vistaEspecificaOrdenesCompra/{idCotisacion}', [ComprasController::class, 'vistaEspecificaOrdenCompra'])->middleware('auth', 'nocache')->name('compras.cotisaciones.vistaEspecificaOrdenesCompra');

Route::get('finanzas/ordenCompra/vistaOrdenCompraFin', [FinanzasController::class, 'ordenescompra'])->middleware('auth', 'nocache')->name('finanzas.ordenCompra.vistaOrdenCompraFin');

Route::get('finanzas/ingresosEgresos/ingresosEgeresosVistaGeneral', [FinanzasController::class, 'ingresosEgresos'])->middleware('auth', 'nocache')->name('finanzas.ingresosEgresos.ingresosEgeresosVistaGeneral');
