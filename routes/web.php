<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\UserController;


Auth::routes();

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('optimize:clear');
    return "Cleared and optimized!";
});

Route::get('/', [HomeController::class, 'index'])->name('');

Route::prefix('dashboard')->group(function(){
    Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('add-clients', [UserController::class, 'add_clients'])->name('add-clients');
    Route::get('edit-client/{id}', [UserController::class, 'edit_clients'])->name('edit-clients');
    Route::get('view-clients', [UserController::class, 'view_clients'])->name('view-clients');
    Route::get('view_clients_', [UserController::class,'fetch_tables'])->name('view_clients_');

    Route::post('delete-records', [UserController::class, 'delete_records']);
    Route::post('add-clients', [UserController::class, 'submit_clients']);    
});


