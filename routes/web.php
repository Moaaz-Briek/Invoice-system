<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckStatus;
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
Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::resource('invoices', App\Http\Controllers\InvoiceController::class);
Route::resource('sections', App\Http\Controllers\SectionController::class);
Route::resource('products', App\Http\Controllers\ProductController::class);
Route::resource('Attachss', App\Http\Controllers\AttachmentController::class);
Route::resource('archives', App\Http\Controllers\ArchiveController::class);
Route::post('/Unarchive_invoice', 'App\Http\Controllers\ArchiveController@update')->name('archive');

Route::get('/section/{id}', 'App\Http\Controllers\InvoiceController@getproducts');

Route::get('/InvoiceDetails/{id}', 'App\Http\Controllers\InvoiceDetailsController@DisplaySection');
Route::get('/edit_invoice/{id}', 'App\Http\Controllers\InvoiceController@edit');
Route::get('/edit_invoice_status/{id}', 'App\Http\Controllers\InvoiceController@edit_invoice_status');
Route::Post('/Status-Update/{id}', 'App\Http\Controllers\InvoiceController@Status_Update');
Route::get('/download/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoiceDetailsController@get_file');
Route::get('/View_file/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoiceDetailsController@open_file');
Route::post('/delete_file', 'App\Http\Controllers\InvoiceDetailsController@destroy')->name('delete_file');

Route::get('Paid_invoices', 'App\Http\Controllers\InvoiceController@Paid_invoices');
Route::get('Unpaid_invoices', 'App\Http\Controllers\InvoiceController@Unpaid_invoices');
Route::get('Partial_paid_invoices', 'App\Http\Controllers\InvoiceController@Partial_paid_invoices');
Route::get('/print_invoice/{id}', 'App\Http\Controllers\InvoiceController@print_invoice')->name('print_invoice');

//Maatwebsite Excel
Route::get('export_invoices', [InvoiceController::class, 'export']);

//Permission
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

//Reports
Route::get('invoice-Report', '\App\Http\Controllers\InvoiceReportController@index');
Route::post('Search_invoices', '\App\Http\Controllers\InvoiceReportController@search_invoice');
Route::get('customer_Report', '\App\Http\Controllers\CustomerReportController@index');
Route::post('Search_customers', '\App\Http\Controllers\CustomerReportController@search_customers');

//Notification DB
Route::get('MarkAsRead_all','App\Http\Controllers\InvoiceController@MarkAsRead_all')->name('MarkAsRead_all');
Route::get('MarkAsRead/{id}','App\Http\Controllers\InvoiceController@MarkAsRead')->name('MarkAsRead');

Route::get('/{page}', 'App\Http\Controllers\AdminController@index');
