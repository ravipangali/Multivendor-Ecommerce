<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'saasrolemanager:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', function () {
        return view('saas_customer.saas_dashboard');
    })->name('customer.dashboard');
});