<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Product Routes (SQL Injection)
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product', [ProductController::class, 'show'])->name('products.show');
Route::post('/coupon/apply', [ProductController::class, 'applyCoupon'])->name('coupon.apply');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Buy Feature
    Route::post('/product/{id}/buy', [ProductController::class, 'buy'])->name('products.buy');
    // Support Ticket Routes (Unrestricted File Upload)
    Route::get('/support/ticket', [SupportTicketController::class, 'create'])->name('tickets.create');
    Route::post('/support/ticket/upload', [SupportTicketController::class, 'store'])->name('tickets.store');

    // Invoice Routes (Path Traversal + OOB SQLi)
    Route::get('/account/invoice/download', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::post('/account/invoice/send-email', [InvoiceController::class, 'sendEmail'])->name('invoice.send_email');

    // Order Routes (IDOR)
    Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
