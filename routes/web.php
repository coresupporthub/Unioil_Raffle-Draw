<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Models\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Customers;
use App\Models\ProductList;
use App\Models\RaffleEntries;
use App\Http\Services\Tools;
use App\Models\Event;

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('Admin.index');
    })->name('index');
    Route::get('/blank', function () {
        return view('Admin.blank');
    })->name('blank');
    Route::get('/qr/generator', function () {
        return view('Admin.qrgenerator');
    })->name('qrgenerator');
    Route::get('/raffle/draw', function () {
        return view('Admin.raffledraw');
    })->name('raffledraw');
    Route::get('/retail/outlets', function () {
        return view('Admin.retailoutlets');
    })->name('retailoutlets');

    Route::get('/raffle/events', function () {
        return view('Admin.raffleevents');
    })->name('raffleevents');

    Route::get('/raffle/events/results', function () {
        return view('Admin.raffleeventresults');
    })->name('raffleeventresults');

    Route::get('/raffle/events/results/print', function () {
        return view('Admin.raffleeventresultsprint');
    })->name('raffleeventresultsprint');

    Route::get('/raffle/entries', function () {
        return view('Admin.raffleentries');
    })->name('raffleentries');
    Route::get('/product/reports', function () {
        return view('Admin.productreports');
    })->name('productreports');

    //SETTINGS
    Route::get('/account/settings', function () {
        return view('Admin.accountsettings');
    })->name('accountsettings');

    Route::get('/activity-logs', function () {
        return view('Admin.activitylogs');
    });
});


//AUTHENTICATION
Route::get('/admin/sign-in', function () {
    return view('Admin.signin');
})->name('adminsignin');
Route::get('/admin/verification-code', function () {
    return view('Admin.signin2');
})->name('adminsignin2');


//CUSTOMER SIDE
Route::get('/registration/page/{code}/{uuid}', function ($code, $uuid) {

    $checkCode = QrCode::where('qr_id', $uuid)->where('code', $code)->first();
    if (!$checkCode) {
        abort(402);
    }

    $checkUsed = QrCode::where('qr_id', $uuid)->where('code', $code)->where('status', 'used')->first();

    if ($checkUsed) {
        abort(402);
    }

    if ($checkCode->entry_type == 'Dual Entry QR Code') {
        $product = ProductList::where('entries', 2)->get();
        $productType = 'Dual Entry';
    } else {
        $product = ProductList::where('entries', 1)->get();
        $productType = 'Single Entry';
    }

    return view('Customer.registration', ['code' => $code, 'uuid' => $uuid, 'products' => $product, 'product_type' => $productType]);
})->name('customer_registrations');

Route::get('/registration-complete/coupon-serial-number/{customer_id}', function ($customer_id) {

    $customers = Customers::where('customer_id', $customer_id)->first();

    if (!$customers) {
        abort(404);
    }

    $productPurchased = ProductList::where('product_id', $customers->product_purchased)->first();

    if ($productPurchased->entries == 1) {
        $raffleEntries = RaffleEntries::where('customer_id', $customers->customer_id)->first();
    } else {
        $raffleEntries = RaffleEntries::where('customer_id', $customers->customer_id)->get();
    }

    $event = Event::where('event_status', 'Active')->first();
    $prizeImagePath = storage_path('app/event_images/' . $event->event_prize_image);
    $event->event_prize_image = base64_encode(file_get_contents($prizeImagePath));
    $bannerImagePath = storage_path('app/event_images/' . $event->event_banner);
    $event->event_banner = base64_encode(file_get_contents($bannerImagePath));

    return view('Customer.coupon', [
        'entries' => $productPurchased->entries,
        'code' => $raffleEntries,
        'customers' => $customers,
        'banner' => $event->event_banner,
        'prize_image' => $event->event_prize_image,
        'prize' => $event->event_prize,
        'duration' => Tools::dateFormat($event->event_start) . ' - ' . Tools::dateFormat($event->event_end),
        'disclaimer' => $event->event_prize_disclaimer ?? null,
    ]);
});
