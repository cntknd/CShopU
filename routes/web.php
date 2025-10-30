<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;     
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Users\ProdController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserFeedbackController;




// ==========================
// PUBLIC ROUTES
// ==========================
Route::get('/', function () {
    $produktomo = Product::all();
    return view('welcome', compact('produktomo'));
})->name('home');

Route::view('/shipping', 'pages.shipping')->name('shipping');
Route::view('/citizens-charter', 'pages.citizens-charter')->name('citizens-charter');
Route::view('/payment', 'pages.payment')->name('payment');
Route::view('/contact-us', 'pages.contact-us')->name('contact-us');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


// ==========================
// AUTH DASHBOARD
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('users.dashboard');
    })->name('dashboard');
    
    // Test route for order confirmation email
    Route::get('/test-order-email/{id}', [UserOrderController::class, 'confirmAndNotify']);
});

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth', 'verified', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Products Management
    Route::resource('manageproducts', \App\Http\Controllers\Admin\ProdCtrl::class);
    
    // Categories Management
    Route::resource('categories', CategoryController::class);
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}', [OrderController::class, 'update'])->name('update');
        Route::post('/{order}/complete', [OrderController::class, 'complete'])->name('complete');
        Route::post('/{order}/confirm', [OrderController::class, 'confirm'])->name('confirm');
        Route::post('/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('mark-paid');
        Route::get('/{order}/receipt', [OrderController::class, 'generateReceipt'])->name('receipt');
        Route::get('/count', [OrderController::class, 'getNewOrderCount'])->name('count');
    });
    
    // Feedback Management
    Route::get('feedbacks', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('feedbacks/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'show'])->name('feedbacks.show');
    // Sales Management
    Route::get('sales/overview', [SalesController::class, 'overview'])->name('sales.overview');
    Route::get('sales/report', [SalesController::class, 'salesReport'])->name('sales.report');
    
    // Feedback Management
    Route::get('feedbacks', [UserFeedbackController::class, 'index'])->name('feedbacks.index');
});

// Admin Order Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('/{id}/status', [OrderController::class, 'updateStatus'])->name('status');
        Route::post('/{order}/confirm', [OrderController::class, 'confirm'])->name('confirm');
        Route::post('/{order}/complete', [OrderController::class, 'complete'])->name('complete');
        Route::post('/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('paid');
        Route::post('/{order}/reject', [OrderController::class, 'reject'])->name('reject');
        Route::get('/{order}/receipt', [OrderController::class, 'generateReceipt'])->name('receipt');
        Route::get('/user-orders', [OrderController::class, 'userOrders'])->name('user');
        Route::get('/count', [OrderController::class, 'getNewOrderCount'])->name('count');
    });

// User Feedback Routes
Route::middleware(['auth'])->group(function () {
    // Feedback routes
    Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    
    // Order payslip routes
    Route::get('/orders/{order}/print-payslip', [\App\Http\Controllers\UserOrderController::class, 'printPayslip'])->name('user.orders.print-payslip');
    Route::get('/orders/{order}/download-payslip', [\App\Http\Controllers\UserOrderController::class, 'downloadPayslip'])->name('user.orders.download-payslip');
});

// ==========================
// PROFILE ROUTES
// ==========================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// USER ROUTES
// ==========================
Route::middleware(['auth', 'can:user-access'])->prefix('users')->name('users.')->namespace('App\Http\Controllers\Users')->group(function () {
    Route::resource('/feedback', 'CTRLFeedbacks')->except(['update', 'edit', 'destroy']);
});

// User Product Viewing
Route::prefix('user')->middleware('auth')->name('user.')->group(function () {
    Route::get('/products', [ProdController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProdController::class, 'show'])->name('products.show');
});

// User Cart & Orders
Route::prefix('user')->middleware('auth')->group(function () {
    // Cart
        Route::post('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('user.cart.add');
        Route::get('/cart', [CartController::class, 'viewCart'])->name('user.cart.view');
        Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity'])->name('user.cart.updateQuantity');
        Route::get('/cart/remove-item/{id}', [CartController::class, 'removeItem'])->name('user.cart.removeItem');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('user.cart.checkout');
        Route::post('/cart/update-size', [CartController::class, 'updateSize'])->name('user.cart.updateSize');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\UserOrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [\App\Http\Controllers\UserOrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [\App\Http\Controllers\UserOrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [\App\Http\Controllers\UserOrderController::class, 'destroy'])->name('destroy');
        Route::patch('/{order}/cancel', [\App\Http\Controllers\UserOrderController::class, 'cancel'])->name('cancel');
    });
    // Route for getting new order count moved to admin routes section

});

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth', 'can:admin-access'])
    ->prefix('admin')
    ->name('admin.')
    ->namespace('App\Http\Controllers\Admin')
    ->group(function () {

        // 👥 Users & Feedback
        Route::resource('/users', 'UserController')->except(['create', 'store', 'destroy']);
        Route::get('/userfeedbacks', 'UserController@userfeedback')->name('userfeedback');

        // 💰 Sales Overview - moved to main admin group

        // 💬 Feedback view (Admin)
        Route::get('/feedbacks', [\App\Http\Controllers\AdminFeedbackController::class, 'index'])
            ->name('feedbacks.index');

        // 🏷️ Category Management
        Route::resource('categories', CategoryController::class);

        // 💾 Backup Management
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/create', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/delete', [BackupController::class, 'destroy'])->name('backups.destroy');
        Route::post('/backups/cleanup', [BackupController::class, 'cleanup'])->name('backups.cleanup');
        Route::get('/backups/stats', [BackupController::class, 'stats'])->name('backups.stats');
    });


        

   

// ==========================
// AUTH ROUTES
// ==========================
require __DIR__.'/auth.php';
