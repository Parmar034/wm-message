<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MembersController;
use App\Http\Controllers\Api\QrmanagementController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WmController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('dashboard', [DashboardController::class, 'index']);

    /* members */
    
    Route::post('members', [MembersController::class, 'list']);
    Route::post('members-add', [MembersController::class, 'add']);
    Route::post('members-destroy', [MembersController::class, 'destroy']);

    /* QR Management*/

    Route::post('qr-management', [QrmanagementController::class, 'list']);
    Route::post('qr-management-add', [QrmanagementController::class, 'add']);
    Route::post('qr-management-destroy', [QrmanagementController::class, 'destroy']);
    Route::post('qr-management-excel-import', [QrmanagementController::class, 'excel_import']);


    /* Scan Qr code*/
    Route::post('scan-qr-code', [QrmanagementController::class, 'scan']);

    /* Report */
    Route::post('report-dropdown', [ReportController::class, 'report_dropdown']);
    Route::post('reports-list', [ReportController::class, 'list']);
    // Route::post('reports-excel-export', [ReportController::class, 'excel_export']);

});
    Route::get('download-sample-file', [ReportController::class, 'download_file']);
    Route::get('reports-excel-export', [ReportController::class, 'excel_export']);

    Route::post('sendMessage', [WmController::class, 'sendMessage']);
    Route::post('sendTextMessage', [WmController::class, 'sendTextMessage']);

    Route::post('send-whatsapp', [WmController::class, 'send_whatsapp']);

    


