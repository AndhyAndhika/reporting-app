<?php

/* Contoller */
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\ManpowerController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\SetupDataController;
use App\Models\ReportDaily;
/* except controller */
use Illuminate\Support\Facades\Auth;
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

/* Disabled everything, except login and logout */
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

/* Routing for welcome page */
Route::get('/', function () {
    return view('welcome');
});


/* Routing for dashboard */
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

/* Routing Grup for menu Manpower */
Route::name('manpower.')->middleware('auth')->group(function () {
    Route::get('/manpower', [ManpowerController::class, 'index'])->name('index');
    Route::post('/manpower/data/store', [ManpowerController::class, 'store'])->name('store');
    Route::get('/manpower/data/find/{id}', [ManpowerController::class, 'show'])->name('show');
    Route::post('/manpower/data/update', [ManpowerController::class, 'update'])->name('update');
    Route::post('/manpower/data/update-password', [ManpowerController::class, 'update_password'])->name('update-password');
    Route::post('/manpower/data/destroy', [ManpowerController::class, 'destroy'])->name('destroy');
});

/* Routing Grup for menu Setup data */
Route::name('setupdata.')->middleware('auth')->group(function () {
    /* Routing to redirect view setupdata */
    Route::get('/setupdata', [SetupDataController::class, 'index'])->name('index');

    /* Routing Store Data */
    Route::post('/setupdata/store-part', [SetupDataController::class, 'store_part'])->name('store_part');
    Route::post('/setupdata/store-rejection', [SetupDataController::class, 'store_rejection'])->name('store_rejection');
    Route::post('/setupdata/store-rejection-on-part', [SetupDataController::class, 'store_rejection_on_part'])->name('store_rejection_on_part');

    /* Routing to show data */
    Route::get('/setupdata/show-part', [SetupDataController::class, 'show_part'])->name('show_part');
    Route::get('/setupdata/show-rejection', [SetupDataController::class, 'show_rejection'])->name('show_rejection');
    Route::get('/setupdata/show-rejection-on-part', [SetupDataController::class, 'show_rejection_on_part'])->name('show_rejection_on_part');

    /* Routing to update data */
    Route::post('/setupdata/update-part', [SetupDataController::class, 'update_part'])->name('update_part');
    Route::post('/setupdata/update-rejection', [SetupDataController::class, 'update_rejection'])->name('update_rejection');

    /* Routing to delete data */
    Route::post('/setupdata/destroy-part', [SetupDataController::class, 'destroy_part'])->name('destroy_part');
    Route::post('/setupdata/destroy-rejection', [SetupDataController::class, 'destroy_rejection'])->name('destroy_rejection');

    /* Routing datatables */
    Route::get('/setupdata/datatables-part', [SetupDataController::class, 'datatables_part'])->name('datatables_part');
    Route::get('/setupdata/datatables-rejection', [SetupDataController::class, 'datatables_rejection'])->name('datatables_rejection');
    Route::get('/setupdata/datatables-rejection-on-part', [SetupDataController::class, 'datatables_rejection_on_part'])->name('datatables_rejection_on_part');
});

/* Routing Grup for menu reporting */
Route::name('reporting.')->middleware('auth')->group(function () {
    /* Routing to redirect view reporting */
    Route::get('/reporting', [ReportingController::class, 'index'])->name('index');
    Route::get('/reporting/chart-stacked', [ReportingController::class, 'chart_stacked'])->name('chart_stacked');
    Route::get('/reporting/chart-line', [ReportingController::class, 'chart_line'])->name('chart_line');

    /* Routing to redirect view reporting details */
    Route::get('/reporting-detail', [ReportingController::class, 'index_details'])->name('index_details');
    Route::post('/reporting-detail/update', [ReportingController::class, 'update_details'])->name('update_details');
    Route::post('/reporting-detail/destroy', [ReportingController::class, 'delete_details'])->name('delete_details');

    /* Routing datatables_details */
    Route::get('/reporting/datatables', [ReportingController::class, 'datatables_report'])->name('datatables_report');
    Route::get('/reporting-detail/datatables', [ReportingController::class, 'datatables_details'])->name('datatables_details');
});

/* Routing Grup for input data */
Route::name('inputdata.')->middleware('auth')->group(function () {
    /* Routing to redirect view inputdata */
    Route::get('/inputdata', [InputDataController::class, 'index'])->name('index');

    /* Routing Store Data */
    Route::post('/inputdata/store-data', [InputDataController::class, 'store_data'])->name('store_data');
    Route::get('/inputdata/show-data', [InputDataController::class, 'show_data'])->name('show_data');

    /* Routing datatables_resume */
    Route::get('/inputdata/datatables-resume', [InputDataController::class, 'datatables_resume'])->name('datatables_resume');

});

