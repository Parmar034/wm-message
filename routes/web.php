<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Backend;
use App\Http\Controllers\QRCode\QRController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\WmManagementController;
use App\Http\Controllers\Backend\PlansController;




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

Route::get('login', function () {
    if (Auth::user()) {
        return redirect()->to('/');
    }
    return app(LoginController::class)->index(); // correctly call the controller method
})->name('login');

Route::post('login', [LoginController::class, 'customLogin']);

// ForgotPassword

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.forgot');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('forgot-password-send-otp');;
Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('verifyOtp');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);


// Route::get('reset-password', function () {
//     return view('auth.reset_pass_link');
// })->name('reset-password');

// Route::post('reset-password', [LoginController::class, 'send_link_reset']);

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Dashboard

    Route::get('/', [Backend\DashboardController::class, 'index'])->name('home');
    Route::post('recent-qr-scans-list', [Backend\DashboardController::class, 'list'])->name('recent-qr-scans-list');


    // Member-Managemnet-Routes

    Route::get('member-management', [Backend\UserController::class, 'memebrManagement'])->name('member-management');
    Route::get('member-management/add', [Backend\UserController::class, 'addMemberManagement'])->name('member-management.add');
    Route::post('member-management', [Backend\UserController::class, 'updateMember'])->name('member-management.store');
    Route::get('member-management/edit/{id}', [Backend\UserController::class, 'memeberedit'])->name('member-management.edit');
    Route::post('member-list', [Backend\UserController::class, 'list'])->name('member-list');
    Route::post('member-status', [Backend\UserController::class, 'updateStatus'])->name('member-management.status');
    Route::post('member-management/delete', [Backend\UserController::class, 'destroy'])->name('member-management.delete');
    Route::get('member-management/plan-assign/{id}', [Backend\UserController::class, 'plan_assign'])->name('member-plan.assign');
    Route::post('plan-assign-store', [Backend\UserController::class, 'assign_store'])->name('member-plan.assign.store');
    Route::get('/member-export-excel', [Backend\UserController::class, 'exportExcel'])->name('member.export.excel');
    Route::post('member/plan-history', [Backend\UserController::class, 'planHistory'])->name('member.plan.history');

    


    // Plans
    Route::get('plans', [Backend\PlansController::class, 'index'])->name('plans');
    Route::get('plan/add', [Backend\PlansController::class, 'add'])->name('plan.add');
    Route::post('plan/store', [Backend\PlansController::class, 'store'])->name('plan.store');
    Route::post('plans-list', [Backend\PlansController::class, 'list'])->name('plans-list');
    Route::post('plan-status', [Backend\PlansController::class, 'updateStatus'])->name('plan.status');
    Route::get('plan/edit/{id}', [Backend\PlansController::class, 'planedit'])->name('plan.edit');
    Route::post('plan/delete', [Backend\PlansController::class, 'destroy'])->name('plan.delete');



    // Qr-Managemnet-Routes
    Route::get('qr-management', [QRController::class, 'index'])->name('qr-management');
    Route::get('qr-management/add', [QRController::class, 'addQrManagement'])->name('qr-management.add');
    Route::post('qr-management', [QRController::class, 'updateQRcode'])->name('qr-management.store');
    Route::post('qr-code-import.store', [QRController::class, 'qr_code_import'])->name('qr-code-import.store');


    Route::get('qr-management/bulk-report', [QRController::class, 'BulkEntryReport'])->name('qr-management.bulk-qr-entry-report');
    Route::get('qr-management/edit/{id}', [QRController::class, 'Qredit'])->name('qr-management.edit');
    Route::post('/qr-management/delete', [QRController::class, 'destroy'])->name('qr-management.delete');
    Route::post('/qr-management/used-delete', [QRController::class, 'used_destroy'])->name('qr-management.used-delete');

    Route::post('qr-management-list', [QRController::class, 'list'])->name('qr-management-list');
    Route::get('sample-file-download', [QRController::class, 'sample_file_download'])->name('sample-file-download');

    

    // Reports-Routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::post('report-list', [ReportController::class, 'list'])->name('report-list');
    Route::get('report-excel-download', [ReportController::class, 'excel_export'])->name('report-excel-export');

    // User-Managemnet

    Route::get('user-management', [Backend\UserManagementController::class, 'memebrManagement'])->name('user-management');
    Route::get('user-management/add', [Backend\UserManagementController::class, 'addMemberManagement'])->name('user-management.add');
    Route::post('user-management', [Backend\UserManagementController::class, 'updateMember'])->name('user-management.store');
    Route::get('user-management/edit/{id}', [Backend\UserManagementController::class, 'memeberedit'])->name('user-management.edit');
    Route::post('user-list', [Backend\UserManagementController::class, 'list'])->name('user-list');
    Route::post('user-management/delete', [Backend\UserManagementController::class, 'destroy'])->name('user-management.delete');
    Route::post('user-management/send-message', [Backend\UserManagementController::class, 'send_message'])->name('user-management.send-message');
    Route::post('user-management-status', [Backend\UserManagementController::class, 'updateStatus'])->name('user-management.status');
    Route::get('/user-export-excel', [Backend\UserManagementController::class, 'exportExcel'])->name('user.export.excel');




    // WM-Managemnet
    Route::get('wm-management/add', [Backend\WmManagementController::class, 'addMemberManagement'])->name('wm-management.add');


    // Route::get('reports', [ReportController::class, 'index'])->name('reports');

    //Bulk User Upload
    Route::get('bulk-user/upload', [Backend\BulkUploadUserController::class, 'bulkuserupload'])->name('bulk-user.upload');
    Route::post('bulk-user/store', [Backend\BulkUploadUserController::class, 'storeuploaduser'])->name('bulk-user.store');
    Route::post('bulk-list', [Backend\BulkUploadUserController::class, 'list'])->name('bulk-list');
    Route::get('bulk-download/{id}', [Backend\BulkUploadUserController::class, 'download'])->name('bulk-download');


    //message 
    Route::get('message', [Backend\MessageController::class, 'index'])->name('message.index');
    Route::post('message-list', [Backend\MessageController::class, 'list'])->name('message-list');
    Route::post('search', [Backend\MessageController::class, 'search_get'])->name('message.search-get');
    Route::post('filter', [Backend\MessageController::class, 'filter'])->name('message.filter');
    Route::post('export', [Backend\MessageController::class, 'exportMessages'])->name('export.messages');

    // Route::get('data', [Backend\MessageController::class, 'datastore'])->name('datastore');










});
