<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;


// Route::view('/', 'website.home')->name('home');
Route::redirect('/', '/login');
Route::view('/privacy-policies', 'website.privacypolicies')->name('privacy.policies');
Route::view('/contact', 'website.contact')->name('contact');
Route::view('/help_center', 'website.helpCenter')->name('helpCenter');
Route::get('/supplier-costs', [SupplierController::class, 'getSupplierCosts']);


require __DIR__.'/auth.php';
