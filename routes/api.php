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
use App\Http\Controllers\AdministratorsController;
use App\Http\Controllers\BackupController;

Route::group(['middleware' => ['web']], function () {


    Route::middleware([ApiAuthentication::class])->group(function () {

        Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
        Route::get('/get-qr-code-generated', [QrCodeController::class, 'getqrcodegenerated']);
        Route::get('/get-queue-status', [QrCodeController::class, 'queueProgress']);
        Route::post('/export-qr', [QrCodeController::class, 'exportQR']);
        Route::get('/filter-qrcodes', [QrCodeController::class, 'filterqr']);
        Route::get('/view-qrcodes', [QrCodeController::class, 'viewqrdetails']);
        Route::get('/get-export-page-num', [QrCodeController::class, 'checkexportnum']);
        Route::get('/file-download/{path}', [QrCodeController::class, 'zipdownload']);

        // Retail Store Controller
        Route::post('/add-retail-store', [RetailStoreController::class, 'addcluster']);
        Route::get('/get-cluster/{type}', [RetailStoreController::class, 'getcluster']);
        Route::post('/cluster-status', [RetailStoreController::class, 'clusterstatus']);
        Route::post('/update-store', [RetailStoreController::class, 'updatestore']);
        Route::get('/get-all-store', [RetailStoreController::class, 'getallstore']);
        Route::post('/remove-retail', [RetailStoreController::class, 'removeretailstore']);
        Route::post('/upload-retail-store', [RetailStoreController::class, 'uploadcsv']);
        Route::get('/filter-cluster', [RetailStoreController::class, 'filtercluster']);
        Route::post('/add-single-retail-store', [RetailStoreController::class, 'addretailstore']);
        Route::post('/update-cluster', [RetailStoreController::class, 'updatecluster']);
        Route::post('/enable-cluster', [RetailStoreController::class, 'enablecluster']);

        //Raffle
        Route::post('/get-raflle-entry', [RaffleController::class, 'getraffleentry']);
        Route::post('/raffle-draw', [RaffleController::class, 'raffledraw']);
        Route::get('/get-all-winner', [RaffleController::class, 'getallwinner']);
        Route::post('/get-all-entry', [RaffleController::class, 'getallentry']);
        Route::get('/get-all-event', [RaffleController::class, 'getallevent']);
        Route::post('/add-event', [RaffleController::class, 'addevent']);
        Route::post('/raffle-redraw', [RaffleController::class, 'redraw']);
        Route::post('/update-event', [RaffleController::class, 'updateevent']);
        Route::post('/update-event-images', [RaffleController::class, 'updateeventimages']);
        Route::post('/update-event-banner', [RaffleController::class, 'updateeventbanner']);
        Route::post('/inactive-event', [RaffleController::class, 'inactiveevent']);
        Route::post('/event-selected', [RaffleController::class, 'getaselectedevent']);

        //Raffle Draw Report
        Route::post('/event-winner', [RaffleController::class, 'geteventwinner']);
        Route::post('/event-unclaim', [RaffleController::class, 'geteventunclaim']);

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
        Route::get('/clusters/data/{eventId}', [AnalyticsController::class, 'getClusterData']);
        Route::get('/entries/productcluster/{eventId}', [AnalyticsController::class, 'entriesByProductTypeAndCluster']);

        //Administrator Management
        Route::post('/add-admin', [AdministratorsController::class, 'add']);
        Route::post('/update-admin', [AdministratorsController::class, 'update']);
        Route::post('/delete-admin', [AdministratorsController::class, 'delete']);
        Route::post('/changepass-admin', [AdministratorsController::class, 'changepass']);
        Route::get('/list-admin', [AdministratorsController::class, 'list']);
        Route::post('/admin-transfer-status', [AdministratorsController::class, 'transferstatus']);

        //Back up
        Route::get('/backup/list', [BackupController::class, 'list']);
        Route::post('/backup/initiate', [BackupController::class, 'initiate']);
        Route::post('/backup/automate', [BackupController::class, 'automate']);
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
