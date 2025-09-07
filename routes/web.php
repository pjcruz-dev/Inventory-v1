<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');
	
	// User CRUD Routes
	Route::resource('users', App\Http\Controllers\UserController::class);
	Route::get('users/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');
	Route::put('users/{user}/update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update-password');
	
	// Role and Permission Routes
	Route::resource('roles', App\Http\Controllers\RoleController::class);
	Route::resource('permissions', App\Http\Controllers\PermissionController::class);

	// Asset Management Routes
	Route::resource('asset-types', App\Http\Controllers\AssetTypeController::class);
	Route::resource('assets', App\Http\Controllers\AssetController::class);
	Route::resource('peripherals', App\Http\Controllers\PeripheralController::class);
	Route::resource('asset-transfers', App\Http\Controllers\AssetTransferController::class);
	Route::resource('print-logs', App\Http\Controllers\PrintLogController::class);

	// Asset Transfer Specific Routes
	Route::put('asset-transfers/{assetTransfer}/complete', [App\Http\Controllers\AssetTransferController::class, 'completeTransfer'])->name('asset-transfers.complete');

	// Print Asset Routes
	Route::get('assets/{asset}/print', [App\Http\Controllers\PrintLogController::class, 'printAsset'])->name('assets.print');

	// Audit Trail Routes
	Route::get('audit-trail', [App\Http\Controllers\AuditTrailController::class, 'index'])->name('audit-trail.index');
	Route::get('audit-trail/{auditTrail}', [App\Http\Controllers\AuditTrailController::class, 'show'])->name('audit-trail.show');

	// Import/Export Routes
	Route::get('import-export/import', [App\Http\Controllers\ImportExportController::class, 'importForm'])->name('import.form');
	Route::post('import-export/import', [App\Http\Controllers\ImportExportController::class, 'import'])->name('import.process');
	Route::get('import-export/export', [App\Http\Controllers\ImportExportController::class, 'export'])->name('export.assets');
	Route::get('import-export/template', [App\Http\Controllers\ImportExportController::class, 'downloadTemplate'])->name('export.template');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');