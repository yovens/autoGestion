<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

// Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\AdminProfileController;

// Client
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Client\VehicleController as ClientVehicle;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\LoanController as ClientLoan;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\PurchaseController;
use App\Http\Controllers\Client\ProfileController;
// Middlewares
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsClient;
use App\Http\Middleware\CheckUserStatus;

/*
|--------------------------------------------------------------------------
| 🔓 AUTH ROUTES & PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 🔐 ADMIN ROUTES (Netwaye ak tout koriksyon yo)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', IsAdmin::class, CheckUserStatus::class])
    ->group(function () {
        
        // Profile Admin
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        // Rout Profile yo
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    
    // Asire w liy sa a egziste byen ekri anndan gwoup la:
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');




        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users CRUD + Block/Unblock
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/block', [UserController::class, 'block'])->name('users.block');

        // Vehicles CRUD
        Route::resource('vehicles', VehicleController::class);

      // 🌟 GESTION LOCATIONS
Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
Route::get('/loans/{id}', [LoanController::class, 'show'])->name('loans.show');
Route::put('/loans/{id}', [LoanController::class, 'update'])->name('loans.update');
Route::post('/loans/{id}/renew', [LoanController::class, 'renew'])->name('loans.renew');
Route::delete('/loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');
// Asire w wout la gen non sa a anndan gwoup la
    Route::post('/loans/approve-renewal/{id}', [App\Http\Controllers\Admin\LoanController::class, 'approveRenewal'])->name('loans.approve-renewal');



// 🔥 RANPLASE LIY SA A KONSAMAN (Wete "admin.") :
Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        // Transactions CRUD
        Route::resource('transactions', TransactionController::class)->only(['index', 'destroy']);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    });


/*
|--------------------------------------------------------------------------
| 🧑‍🤝‍🧑 CLIENT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('client')
    ->name('client.')
    ->middleware(['auth', IsClient::class, CheckUserStatus::class])
    ->group(function () {
       
        // Dashboard
        Route::get('/dashboard', [ClientDashboard::class, 'index'])->name('dashboard');
      
        // Vehicles
        Route::get('/vehicles', [ClientVehicle::class, 'index'])->name('vehicles');
        Route::get('/vehicle/{id}', [ClientVehicle::class, 'show'])->name('vehicle.show');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

        // Loans & Renewals
        Route::get('/loan', [ClientLoan::class, 'index'])->name('loan');
        Route::post('/loan/start/{id}', [ClientLoan::class, 'start'])->name('loan.start');
        Route::post('/loan/renew/{id}', [ClientLoan::class, 'renew'])->name('loan.renew');

        // Profile (Kounye a li byen plase anndan gwoup client)
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Transactions
        Route::get('/transactions', [ClientLoan::class, 'transactions'])->name('transactions');

        // Contact & About
        Route::get('/about', [ClientDashboard::class, 'about'])->name('about');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
    });