<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RetailStoreController;
use App\Http\Controllers\RaffleController;
use App\Http\Middleware\ApiAuthentication;
use App\Http\Controllers\CustomerRegistration;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\AnalyticsController;

Route::group(['middleware' => ['web']], function () {


    Route::middleware([ApiAuthentication::class])->group(function () {

        Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
        Route::get('/get-qr-code-generated', [QrCodeController::class, 'getqrcodegenerated']);
        Route::get('/get-queue-status', [QrCodeController::class, 'queueProgress']);
        Route::post('/export-qr', [QrCodeController::class, 'exportQR']);
        Route::get('/filter-qrcodes', [QrCodeController::class, 'filterqr']);
        Route::get('/view-qrcodes', [QrCodeController::class, 'viewqrdetails']);

        // Retail Store Controller
        Route::post('/add-retail-store', [RetailStoreController::class, 'addcluster']);
        Route::get('/get-cluster', [RetailStoreController::class, 'getcluster']);
        Route::post('/cluster-status', [RetailStoreController::class, 'clusterstatus']);
        Route::post('/update-store', [RetailStoreController::class, 'updatestore']);
        Route::get('/get-all-store', [RetailStoreController::class, 'getallstore']);
        Route::post('/remove-retail', [RetailStoreController::class, 'removeretailstore']);
        Route::post('/upload-retail-store', [RetailStoreController::class, 'uploadcsv']);
        Route::get('/filter-cluster', [RetailStoreController::class, 'filtercluster']);
        Route::post('/add-single-retail-store', [RetailStoreController::class, 'addretailstore']);


        //Raffle
        Route::post('/get-raflle-entry', [RaffleController::class, 'getraffleentry']);
        Route::post('/raffle-draw', [RaffleController::class, 'raffledraw']);
        Route::get('/get-all-winner', [RaffleController::class, 'getallwinner']);
        Route::post('/get-all-entry', [RaffleController::class, 'getallentry']);
        Route::get('/get-all-event', [RaffleController::class, 'getallevent']);
        Route::post('/add-event', [RaffleController::class, 'addevent']);
        Route::post('/raffle-redraw', [RaffleController::class, 'redraw']);
        Route::post('/update-event', [RaffleController::class, 'updateevent']);
        Route::post('/inactive-event', [RaffleController::class, 'inactiveevent']);
        Route::post('/event-winner', [RaffleController::class, 'geteventwinner']);
        Route::post('/event-selected', [RaffleController::class, 'getaselectedevent']);

        // Product Report
        Route::post( '/product-report', [RaffleController::class, 'productreport']);


        //Admin Details
        Route::get('/get-admin-details', [AuthenticationController::class, 'getadmindetails']);
        Route::post('/admin-changepassword', [AuthenticationController::class, 'changepassword']);
        Route::post('/update-admin-details', [AuthenticationController::class, 'updateadmin']);

        //Activity Logs
        Route::get('/activitylogs/list', [ActivityLogsController::class, 'list']);
        Route::get('/activitylogs/details/{id}', [ActivityLogsController::class, 'details']);

        //Analytics
        Route::get('/events/data/{eventId}', [AnalyticsController::class, 'getEventData']);
        Route::get('/events/datas/active', [AnalyticsController::class, 'getActiveEvent']);
        Route::get('/entry/issuance/{filter}', [AnalyticsController::class, 'entryissuance']);
        Route::get('/entry/product-type/{event}', [AnalyticsController::class, 'entriesbyproducttype']);

    });

    //Authentication
    Route::post('/admin/auth', [AuthenticationController::class, 'signin']);

    Route::get('/get-auth', [AuthenticationController::class, 'getauth']);
    Route::post('/verify-user', [AuthenticationController::class, 'verifyuser']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/resend-code', [AuthenticationController::class, 'resendcode']);
    Route::post('/check-retail-store', [CustomerRegistration::class, 'checkretailstore']);
    //Customer Api
    Route::post('/register-raffle-entry', [CustomerRegistration::class, 'register']);


});
