<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Assets\{
    InventoryController,
    InventoryTotalController,
    InventoryHistoryController,
    InventoryLocationController,
};
use App\Http\Controllers\Auth\{
    MicrosoftAuthController,
    UserAuthController,
};
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Merk\MerkController;
use App\Http\Controllers\Shared\{
    DashboardAdminController,
    DashboardUserController,
};
use App\Http\Controllers\Transactions\{
    TransactionsAdminController,
    TransactionsUserController,
};
use App\Http\Controllers\{
    HomeSalesController,
    PrintController,
    ReportController,
    SalesController,
};



Route::get('/', function () {
    return view('auth.login');
});

Route::get('auth/microsoft', [MicrosoftAuthController::class, 'redirectToProvider'])->name('auth.microsoft');
Route::get('auth/microsoft/callback', [MicrosoftAuthController::class, 'handleProviderCallback']);
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
Route::get('/register', [UserAuthController::class, 'register'])->name('auth.register');
Route::post('/register', [UserAuthController::class, 'storeregister'])->name('user.storeregister');
Route::get('/print/qr/{id}', [PrintController::class, 'print'])->name('prints.qr');
Route::get('/auth/detailQR/{id}', [PrintController::class, 'showAssetDetail'])->name('auth.detailQR');

Route::middleware(['auth.check'])->group(function () {
    Route::get('/welcome-user', [DashboardUserController::class, 'index'])->name('shared.homeUser');
    Route::get('/portal-user', [DashboardAdminController::class, 'indexUser'])->name('dashboard.user');
    Route::get('/home/sales', [HomeSalesController::class, 'index'])->name('shared.homeSales');

    Route::get('/my-assets', [TransactionsUserController::class, 'indexuser'])->name('asset-user');
    Route::get('/assets/serahterima/{ids}', [TransactionsUserController::class, 'serahterima'])->name('transactions.serahterima');
    Route::put('/assets/updateserahterima', [TransactionsUserController::class, 'updateserahterima'])->name('transactions.updateserahterima');
    Route::delete('/assets-user/returnmultiple', [TransactionsUserController::class, 'returnMultiple'])->name('assets-user.returnmultiple');

    Route::delete('/assets/{id}/return', [TransactionsUserController::class, 'returnAsset'])->name('transactions.return');
    Route::post('/assets/reject/{id}', [TransactionsAdminController::class, 'reject'])->name('transactions.reject');
    Route::delete('assets/{id}', [TransactionsAdminController::class, 'destroy'])->name('transactions.delete');
    Route::post('/assets/approve-multiple', [TransactionsAdminController::class, 'approveMultiple'])->name('transactions.approve_multiple');
    Route::post('/assets/bulk-action', [TransactionsAdminController::class, 'bulkAction'])->name('transactions.bulkAction');

    Route::get('/prints/handover', [PrintController::class, 'handover'])->name('prints.handover');
    Route::get('/prints/mutation', [PrintController::class, 'mutation'])->name('prints.mutation');
    Route::get('/prints/return', [PrintController::class, 'return'])->name('prints.return');
    Route::post('/assets/approve-selected', [TransactionsUserController::class, 'approveSelected'])->name('transactions.approveSelected');

    Route::get('edit/profile/{id}', [CustomerController::class, 'editUser'])->name('customer.editUser');
    Route::put('profile/{id}', [CustomerController::class, 'updateUser'])->name('customer.updateUser');



});

Route::middleware(['auth.check:sales'])->group(function () {
    Route::get('sales/{id}/salesserahterima', [SalesController::class, 'salesserahterima'])->name('sales.salesserahterima');
    Route::put('/assets/{id}/updateserahterimaSales', [SalesController::class, 'updateserahterimaSales'])->name('transactions.updateserahterimaSales');
    Route::resource('sales', SalesController::class);
    Route::get('/saless/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/saless', [SalesController::class, 'store'])->name('sales.store');
});


Route::middleware(['auth.check:admin'])->group(function () {
    Route::get('/portal-admin', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::resource('customer', CustomerController::class);
    Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');

    Route::resource('assets', TransactionsAdminController::class);
    Route::get('assetsgsi', [TransactionsAdminController::class, 'index'])->name('transactions.index');
    Route::get('assetsgsi/mutasi', [TransactionsAdminController::class, 'indexmutasi'])->name('transactions.indexmutasi');
    Route::get('assetsgsi/return', [TransactionsAdminController::class, 'indexreturn'])->name('transactions.indexreturn');
    Route::delete('assets/{id}', [TransactionsAdminController::class, 'destroy'])->name('transactions.delete');
    Route::get('assets/create', [TransactionsAdminController::class, 'create'])->name('transactions.create');
    Route::post('assetsgsi', [TransactionsAdminController::class, 'store'])->name('transactions.store');
    Route::get('assets/{id}/edit', [TransactionsAdminController::class, 'edit'])->name('transactions.edit');
    Route::get('assets/{id}/pindahtangan', [TransactionsAdminController::class, 'pindah'])->name('transactions.pindahtangan');
    Route::put('/assets/{id}/pindah', [TransactionsAdminController::class, 'pindahUpdate'])->name('transactions.pindahUpdate');
    Route::put('assets/{id}', [TransactionsAdminController::class, 'update'])->name('transactions.update');
    Route::get('assets-history', [TransactionsAdminController::class, 'history'])->name('transactions.history');
    Route::get('/assets/track/{id}', [TransactionsAdminController::class, 'track'])->name('transactions.track');
    Route::get('/history', [TransactionsAdminController::class, 'history'])->name('history');
    Route::get('/history/data', [TransactionsAdminController::class, 'getData'])->name('history.data');

    Route::get('/saless', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/saless/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');
    Route::put('/saless/{id}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/saless/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');


    Route::get('/assets/return/{id}', [TransactionsAdminController::class, 'returnAsset'])->name('transactions.return');
    Route::put('/assets/return/{id}', [TransactionsAdminController::class, 'returnUpdate'])->name('transactions.returnUpdate');
    Route::put('/assets/{id}/approvereturn', [TransactionsAdminController::class, 'approveReturn'])->name('transactions.approvereturn');
    Route::put('/assets/{id}/approvemutasi', [TransactionsAdminController::class, 'approveMutasi'])->name('transactions.approvemutasi');
    Route::put('/assets/{id}/approveaction', [TransactionsAdminController::class, 'approveAction'])->name('transactions.approveaction');
    Route::post('/assets/rollbackMutasi/{id}', [TransactionsAdminController::class, 'rollbackMutasi'])->name('transactions.rollbackMutasi');

    Route::resource('inventorys', InventoryController::class);
    Route::get('inventorys', [InventoryController::class, 'index'])->name('assets.index');
    Route::get('inventorystotal', [InventoryTotalController::class, 'summary'])->name('assets.total');
    Route::delete('inventorys/delete', [InventoryController::class, 'destroy'])->name('assets.delete');
    Route::get('inventorys/create', [InventoryController::class, 'create'])->name('assets.create');
    Route::post('inventorys', [InventoryController::class, 'store'])->name('assets.store');
    Route::get('inventorys/edit', [InventoryController::class, 'edit'])->name('assets.edit');

    // Route for updating multiple assets at once
    Route::post('inventorys/update', [InventoryController::class, 'update'])->name('assets.update');
    Route::get('/inventory/{id}/detail', [InventoryController::class, 'show'])->name('assets.show');
    Route::get('inventory-location', [InventoryLocationController::class, 'mapping'])->name('assets.mapping');
    Route::get('/scrap-history', [InventoryHistoryController::class, 'index'])->name('inventory.history');
    Route::get('/asset-history-modal', [InventoryHistoryController::class, 'historyAssetModal'])->name('inventory.historyModal');
    Route::get('/inventory/scrap', [InventoryController::class, 'showScrapForm'])->name('assets.scrap');
    Route::get('/inventory/edit', [InventoryController::class, 'showEditForm'])->name('assets.edit');


    Route::resource('merk', MerkController::class);
    Route::get('/merks', [MerkController::class, 'index'])->name('merk.index');
    Route::get('/merks/create', [MerkController::class, 'create'])->name('merk.create');
    Route::post('/merks', [MerkController::class, 'store'])->name('merk.store');
    Route::get('/merks/{id}/edit', [MerkController::class, 'edit'])->name('merk.edit');
    Route::put('/merks/{id}', [MerkController::class, 'update'])->name('merk.update');
    Route::delete('/merks/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');

    Route::get('/summary-report', [ReportController::class, 'summaryReport'])->name('summary.report');

});
