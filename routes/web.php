<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\GlobalSearchController;
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

	// Global Search Route
	Route::get('global-search', [GlobalSearchController::class, 'search'])->name('global.search');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('sweetalert-test', function () {
		return view('sweetalert-test');
	})->name('sweetalert-test');
	
	// User CRUD Routes (Admin Only)
	Route::middleware(['auth'])->group(function () {
		Route::resource('users', App\Http\Controllers\UserController::class);
		Route::get('users/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');
		Route::put('users/{user}/update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update-password');
	});
	


	// Asset Management Routes
    Route::resource('assets', App\Http\Controllers\AssetController::class);
    Route::get('my-assets', [App\Http\Controllers\AssetController::class, 'myAssets'])->name('assets.my-assets');
    Route::resource('asset-types', App\Http\Controllers\AssetTypeController::class);
	Route::resource('manufacturers', App\Http\Controllers\ManufacturerController::class);
	Route::resource('peripherals', App\Http\Controllers\PeripheralController::class);
	Route::resource('asset-transfers', App\Http\Controllers\AssetTransferController::class);
	Route::resource('print-logs', App\Http\Controllers\PrintLogController::class);
	Route::resource('locations', App\Http\Controllers\LocationController::class);

	// Asset Categories Routes
	Route::resource('asset-categories', App\Http\Controllers\AssetCategoriesController::class);

	// Vendor Management Routes
	Route::resource('vendors', App\Http\Controllers\VendorsController::class);

	// Department Management Routes
	Route::resource('departments', App\Http\Controllers\DepartmentsController::class);
	Route::get('departments/{department}/hierarchy', [App\Http\Controllers\DepartmentsController::class, 'hierarchy'])->name('departments.hierarchy');

	// Project Management Routes
	Route::resource('projects', App\Http\Controllers\ProjectsController::class);
	Route::put('projects/{project}/status', [App\Http\Controllers\ProjectsController::class, 'updateStatus'])->name('projects.update-status');
	Route::get('projects/{project}/assets', [App\Http\Controllers\ProjectsController::class, 'assets'])->name('projects.assets');

	// System Logs Routes
	Route::resource('logs', App\Http\Controllers\LogsController::class)->only(['index', 'show']);
	Route::get('logs/export', [App\Http\Controllers\LogsController::class, 'export'])->name('logs.export');
	Route::post('logs/clear-old', [App\Http\Controllers\LogsController::class, 'clearOldLogs'])->name('logs.clear-old');

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

	// Settings Routes (Admin Only)
	Route::middleware(['role:Admin'])->prefix('settings')->name('settings.')->group(function () {
		Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
		Route::get('/roles', [App\Http\Controllers\SettingsController::class, 'roles'])->name('roles');
		Route::post('/roles', [App\Http\Controllers\SettingsController::class, 'storeRole'])->name('roles.store');
		Route::put('/roles/{role}/permissions', [App\Http\Controllers\SettingsController::class, 'updateRolePermissions'])->name('roles.permissions');
		Route::delete('/roles/{role}', [App\Http\Controllers\SettingsController::class, 'destroyRole'])->name('roles.destroy');
		Route::get('/users', [App\Http\Controllers\SettingsController::class, 'users'])->name('users');
		Route::put('/users/{user}/role', [App\Http\Controllers\SettingsController::class, 'updateUserRole'])->name('users.role');
	});

	// API Routes for AJAX requests
	Route::prefix('api')->group(function () {
		Route::get('users/{user}/permissions', function($userId) {
			$user = App\Models\User::with(['role.permissions'])->findOrFail($userId);
			return response()->json([
				'role' => $user->role,
				'permissions' => $user->role ? $user->role->permissions : []
			]);
		});
	});



    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});