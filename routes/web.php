<?php


use App\Http\Controllers\UploadShipmentDocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\TerminController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeriodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('/set-period', [PeriodeController::class, 'setActivePeriod'])->name('set.period')->middleware(['auth', 'verified', 'active', 'period']);


Route::middleware(['auth', 'verified', 'active', 'admin'])->group(function () {
    // Resource route untuk RolePermission
    // Manual override untuk create dengan slug
    Route::get('role-permissions/create/{slug}', [RolePermissionController::class, 'create'])
        ->name('role-permissions.create');

    Route::post('role-permissions/{slug}', [RolePermissionController::class, 'store'])
        ->name('role-permissions.store');

    Route::resource('role-permissions', RolePermissionController::class)->except(['store'])
    ->parameters(['role-permissions' => 'role_permission:slug']);
});

// administrator / IT
Route::middleware(['auth', 'verified', 'active', 'admin'])->group(function () {

    // Resource route
    Route::resource('user-management', UserController::class)
        ->parameters(['user-management' => 'user:slug']);

    // Custom actions
    Route::post('/user-management/{user:slug}/grant-admin', [UserController::class, 'grantAdmin'])
        ->name('user-management.grant-admin');
    
    Route::post('/user-management/{user:slug}/revoke-admin', [UserController::class, 'revokeAdmin'])
        ->name('user-management.revoke-admin');

    Route::post('/user-management/{user:slug}/activate', [UserController::class, 'activate'])
        ->name('user-management.activate');

    Route::post('/user-management/{user:slug}/deactivate', [UserController::class, 'deactivate'])
        ->name('user-management.deactivate');

    Route::resource('fuels', FuelController::class);

    Route::resource('document-types', DocumentTypeController::class);

    // sini
    Route::resource('period-list', PeriodeController::class);
    Route::post('/period/{id}/activate', [PeriodeController::class, 'activate'])->name('period-list.activate');
    Route::post('/period/{id}/deactivate', [PeriodeController::class, 'deactivate'])->name('period-list.deactivate');


    Route::resource('vessels', VesselController::class);

    Route::resource('termins', TerminController::class);

    Route::resource('spks', SpkController::class);

    Route::resource('shipments', ShipmentController::class);
    Route::get('/shipments/{shipment}/details', [ShipmentController::class, 'showDetails'])->name('shipments.details');
    Route::get('/get-termins/{periodId}', [ShipmentController::class, 'getTermins']);

    Route::get('/shipments/{id}/upload-documents', [ShipmentController::class, 'uploadDocuments'])->name('shipments.upload.documents');
    Route::post('/shipments/{id}/upload-documents', [ShipmentController::class, 'storeUploadedDocument'])->name('shipments.upload.documents.store');

    Route::delete('shipments/{id}/uploaded-documents/{documentId}', [ShipmentController::class, 'destroyUploadedDocument'])
    ->name('shipments.upload.documents.destroy');


});

// admin officer
Route::middleware(['auth', 'verified', 'active', 'admin_officer'])->group(function () {

});

// operasion
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::resource('upload-shipment-documents', UploadShipmentDocumentController::class);
    Route::get('/get-shipments/{periodId}', [UploadShipmentDocumentController::class, 'getShipments']);
});

require __DIR__.'/auth.php';
