 <?php

use App\Http\Controllers\Admin\categorie\CategorieController;
use App\Http\Controllers\Admin\clients\ClientController;
use App\Http\Controllers\Admin\clients\checkController;
use App\Http\Controllers\Admin\company\CompanyController;
use App\Http\Controllers\Admin\depot\depotController;
use App\Http\Controllers\Admin\orders\OrderController;
use App\Http\Controllers\Admin\product\ProductController;
use App\Http\Controllers\Admin\users\UserController;
use App\Http\Controllers\Admin\DetailOrders\DetailOrderController;
use App\Http\Controllers\Admin\employees\Categories;
use App\Http\Controllers\Admin\employees\Stocks;
use App\Http\Controllers\Admin\statistics\BoardController;
use App\Http\Controllers\Admin\statistics\ClientClassement;
use App\Http\Controllers\Admin\statistics\RepportController;
use App\Http\Controllers\Admin\statistics\deletedOrdersController;
use App\Http\Controllers\Admin\statistics\trackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesAdminOperations;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\Master\Roles;
use App\Http\Controllers\SessionController;
use App\Http\Livewire\SearchClient;
use App\Models\Categorie;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laratrust\Http\Controllers\RolesController;
use App\Http\Controllers\Admin\clients\LocationController;

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

// Route::get('/', function () {
//     return view('auth.login');
// });


Route::group(['middleware' => ['auth']], function () {
     Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::group(['middleware' => ['auth', 'role:master']], function () {
    Route::resource('roles', Roles::class);
});

Route::group(['middleware' => ['auth', 'role:master|admin|gerant rapport|gerant BL|gerant product|gerant Validation BL']], function () {
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);// gerant proucts
    Route::resource('categories', CategorieController::class);//gerant products
    Route::get('/usersdata', function () {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->pluck('name', 'id')->toArray();
        return response()->json($users);
    })->name('users.data');

    Route::prefix('employees')->group(function () {
        Route::get('/statistics', [EmployeesAdminOperations::class, 'showStatistics'])->name('employees.statistics');
        Route::get('/orders', [EmployeesAdminOperations::class, 'allOrders'])->name('employees.allorders');
        Route::get('/clients', [EmployeesAdminOperations::class, 'listOfClients'])->name('employees.clients');
        Route::get('/products', [EmployeesAdminOperations::class, 'productsManagement'])->name('employees.products');
        Route::get('/newproduct', [EmployeesAdminOperations::class, 'newProduct'])->name('employees.newproduct');
        Route::post('/saveproduct', [EmployeesAdminOperations::class, 'saveProduct'])->name('employees.saveproduct');
        Route::get('/editproduct/{id}', [EmployeesAdminOperations::class, 'editProduct'])->name('employees.editproduct');
        Route::patch('/editproduct/{product}', [EmployeesAdminOperations::class, 'updateProduct'])->name('employees.updateproduct');
        Route::delete('/deleteproduct/{product}', [EmployeesAdminOperations::class, 'deleteProduct'])->name('employees.deleteproduct');
    });

    Route::prefix('statistics')->group(function () {
        Route::get('/board', [BoardController::class, 'index'])->name('statistics.board');
        Route::post('/board', [BoardController::class, 'operationHandler']);

        Route::get('/clientclassement', [ClientClassement::class, 'index'])->name('statistics.clientclassement');
        Route::post('/clientclassement', [ClientClassement::class, 'operationHandler']);
        
        Route::get('/repport',[RepportController::class,'index'])->name('repport');
        Route::post('/repport',[RepportController::class,'search'])->name('searchStatistics');
       
        Route::get('/deleted',[deletedOrdersController::class,'index'])->name('deleted-orders');
        Route::post('/searchDeletedOrders',[deletedOrdersController::class,'search'])->name('searchDeletedOrders');
        Route::get('/trackings',[trackingController::class,'index'])->name('trackings-orders');
        Route::post('/searchTrackings',[trackingController::class,'search'])->name('searchTrackings');
        Route::get('/searchTrackingsDetails/{id}',[trackingController::class,'details'])->name('searchTrackingsDetails');
    });
    Route::post('/repportPdf',[RepportController::class,'incoicePDF'])->name('pdfSearchStatistics');
});

Route::group(['middleware' => ['auth', 'role:admin|employee|master|gerant BL|gerant Validation BL|gerant product']], function () {
    Route::resource('clients', ClientController::class);
    Route::resource('companys', CompanyController::class);
    Route::get('check', [checkController::class ,  "index"])->name('clients.check');
    Route::post('check', [checkController::class ,  "checkClient"])->name('clients.check-post');
    Route::get('scan', [LocationController::class ,  "show"])->name('clients.scan');
    Route::post('scaned', [LocationController::class ,  "scaned"])->name('client.scaned');
    Route::post('qrcode', [LocationController::class ,  "qrcode"])->name('client.qrcode');
    Route::resource('depots', depotController::class);
    Route::get('/orders/return', [OrderController::class, 'returnOrders'])->name('orders.return');
    Route::resource('orders', OrderController::class);
    Route::get('orders/validationOrd/{id}', [OrderController::class, 'validation'])->name('orders.validation');
    Route::get('orders/PDF/{id}', [OrderController::class, "IncoicePDF"]);
    Route::get('orders/PDF/mini/{id}', [OrderController::class, "IncoicePDFMini"])->name('mini-pdf');
    Route::post('orders/router', [OrderController::class, "router"])->name('order.router');
    Route::get('details', [OrderController::class, "details"])->name('orders.details');
    Route::get('Products/fetchDataDepot', [ProductController::class, "fetchDataDepot"])->name('products.fetchDataDepot');
    Route::get('Products/fetchDataCategoris', [ProductController::class, "fetchDataCategoris"])->name('products.fetchDataCategoris');
    Route::get('orders/selectClients', [SearchClient::class, "selectClients"])->name('selectClients');
    Route::get('orders/fetchDataDepot', [OrderController::class, "fetchDataDepot"])->name('orders.fetchDataDepot');
    Route::POST('addSession', [SessionController::class, "SetSessionProduct"])->name('store.session');
    Route::get('fetchDataProductSession', [OrderController::class, "fetchDataProductSession"])->name('orders.fetchDataProductSession');
    Route::get('DeleteDataProductSession', [OrderController::class, "DeleteDataProductSession"])->name('orders.DeleteDataProductSession');
    Route::get('UpdateDataProductSession', [OrderController::class, "UpdateDataProductSession"])->name('orders.UpdateDataProductSession');
    Route::get('UpdatePriceProductSession', [OrderController::class, "UpdatePriceProductSession"])->name('orders.UpdatePriceProductSession');
    Route::get('fetchTotalProductSession', [OrderController::class, "fetchTotalProductSession"])->name('orders.fetchTotalProductSession');
    Route::resource('invoice', invoiceController::class);

    Route::resource('orderdetail', DetailOrderController::class);

    Route::put('/updateorderdetail', [DetailOrderController::class, 'update'])->name('updateorderdetail');

    Route::post('/productsbycategorie', [ProductController::class, 'getProductsByCategory']);
    Route::post('/productsbyname', [ProductController::class, 'getProductsByName']);

    Route::resource('/employeescategories', Categories::class);
    Route::resource('/employeesstocks', Stocks::class);

    Route::get('/employeescategory/{id}', [Categories::class, 'categoriesOfEmployees']);
    Route::get('/employeesstock/{id}', [Stocks::class, 'stockOfEmployee']);
});


//for admin
Route::group(['middleware' => ['auth', 'role:admin|master']], function () {
    
});





require __DIR__ . '/auth.php';
