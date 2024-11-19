<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Assets\{
    AssetsController,
    AssetsTotalController,
    AssetsHistoryController,
    AssetsLocationController,
    MaintenanceHistoryController,
};
use App\Http\Controllers\Auth\{
    MicrosoftAuthController,
    UserAuthController,
};
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Tickets\TicketController;
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
Route::get('/healthz', function () {
    return response()->json(['status' => 'OK']);
});

Route::get('auth/microsoft', [MicrosoftAuthController::class, 'redirectToProvider'])->name('auth.microsoft');
Route::get('auth/microsoft/callback', [MicrosoftAuthController::class, 'handleProviderCallback']);
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
Route::get('/register', [UserAuthController::class, 'register'])->name('auth.register');
Route::post('/register', [UserAuthController::class, 'storeregister'])->name('user.storeregister');
Route::get('/auth/detailQR/{id}', [PrintController::class, 'showAssetDetail'])->name('auth.detailQR');

Route::middleware(['auth.check'])->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets-user/{id}', [TicketController::class, 'showMobile'])->name('tickets.showMobile');
    Route::post('/tickets/{id}/message', [TicketController::class, 'addMessage'])->name('tickets.addMessage');



    Route::get('/welcome-user', [DashboardUserController::class, 'index'])->name('shared.homeUser');
    Route::get('/portal-user', [DashboardAdminController::class, 'indexUser'])->name('dashboard.user');
    Route::get('/home/sales', [HomeSalesController::class, 'index'])->name('shared.homeSales');

    Route::get('/my-assets', [TransactionsUserController::class, 'indexuser'])->name('asset-user');
    Route::get('/assets/serahterima/{ids}', [TransactionsUserController::class, 'serahterima'])->name('transactions.serahterima');
    Route::put('/assets/updateserahterima', [TransactionsUserController::class, 'updateserahterima'])->name('transactions.updateserahterima');
    Route::delete('/transactions-user/returnmultiple', [TransactionsUserController::class, 'returnMultiple'])->name('transactions-user.returnmultiple');

    Route::delete('/assets/{id}/return', [TransactionsUserController::class, 'returnAsset'])->name('transactions.return');
    Route::post('/assets/reject/{id}', [TransactionsAdminController::class, 'reject'])->name('transactions.reject');
    Route::delete('assets/{id}', [TransactionsAdminController::class, 'destroy'])->name('transactions.delete');
    Route::post('/assets/approve-multiple', [TransactionsAdminController::class, 'approveMultiple'])->name('transactions.approve_multiple');
    Route::post('/assets/bulk-action', [TransactionsAdminController::class, 'bulkAction'])->name('transactions.bulkAction');

    Route::get('/prints/mutation', [PrintController::class, 'mutation'])->name('prints.mutation');
    Route::get('/prints/handover/{id}', [PrintController::class, 'handover'])->name('prints.handover');
    Route::get('/prints/return/{id}', [PrintController::class, 'return'])->name('prints.return');

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
    Route::get('/admin/tickets', [TicketController::class, 'adminIndex'])->name('tickets.adminIndex');
    Route::get('/tickets-admin/{id}', [TicketController::class, 'show'])->name('tickets.show');

    Route::get('/portal-admin', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::resource('customer', CustomerController::class);
    Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');

    Route::resource('transaction', TransactionsAdminController::class);
    Route::get('transactiongsi', [TransactionsAdminController::class, 'index'])->name('transactions.index');
    Route::get('transactiongsi/mutasi', [TransactionsAdminController::class, 'indexmutasi'])->name('transactions.indexmutasi');
    Route::get('transactiongsi/return', [TransactionsAdminController::class, 'indexreturn'])->name('transactions.indexreturn');
    Route::delete('transaction/{id}', [TransactionsAdminController::class, 'destroy'])->name('transactions.delete');
    Route::get('transaction/create', [TransactionsAdminController::class, 'create'])->name('transactions.handover');
    Route::post('transactiongsi', [TransactionsAdminController::class, 'store'])->name('transactions.store');
    Route::get('transaction/{id}/edit', [TransactionsAdminController::class, 'edit'])->name('transactions.edit');
    Route::get('transaction/{id}/pindahtangan', [TransactionsAdminController::class, 'pindah'])->name('transactions.pindahtangan');
    Route::put('/transaction/{id}/pindah', [TransactionsAdminController::class, 'pindahUpdate'])->name('transactions.pindahUpdate');
    Route::put('transaction/{id}', [TransactionsAdminController::class, 'update'])->name('transactions.update');
    Route::get('transaction-history', [TransactionsAdminController::class, 'history'])->name('transactions.history');
    Route::get('/transaction/track/{id}', [TransactionsAdminController::class, 'track'])->name('transactions.track');
    Route::get('/history', [TransactionsAdminController::class, 'history'])->name('history');
    Route::get('/history/data', [TransactionsAdminController::class, 'getData'])->name('history.data');

    Route::get('/saless', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/saless/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');
    Route::put('/saless/{id}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/saless/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');


    Route::get('/assets/return/{id}', [TransactionsAdminController::class, 'returnAsset'])->name('transactions.returnform');
    Route::put('/assets/return/{id}', [TransactionsAdminController::class, 'returnUpdate'])->name('transactions.returnUpdate');
    Route::put('/assets/{id}/approvereturn', [TransactionsAdminController::class, 'approveReturn'])->name('transactions.approvereturn');
    Route::put('/assets/{id}/approvemutasi', [TransactionsAdminController::class, 'approveMutasi'])->name('transactions.approvemutasi');
    Route::put('/assets/{id}/approveaction', [TransactionsAdminController::class, 'approveAction'])->name('transactions.approveaction');
    Route::post('/assets/rollbackMutasi/{id}', [TransactionsAdminController::class, 'rollbackMutasi'])->name('transactions.rollbackMutasi');

    Route::resource('assets', AssetsController::class);
    Route::get('/assets-list', [AssetsController::class, 'index'])->name('assets.index');
    Route::get('/print/qr', [PrintController::class, 'print'])->name('printQR');
    Route::get('/export/excel', [PrintController::class, 'exportToExcel']);
    Route::post('/assets/create', [AssetsController::class, 'store'])->name('assets.store');
    Route::get('assets/create', [AssetsController::class, 'create'])->name('assets.add-asset');
    Route::get('/asset/{asset_code}/depreciation', [AssetsController::class, 'getDepreciation'])->name('asset.depreciation');
    Route::get('assetstotal', [AssetsTotalController::class, 'summary'])->name('assets.total');
    Route::get('/assets-scrap', [AssetsController::class, 'showScrapForm'])->name('assets.scrap');
    Route::delete('/assets-scrap', [AssetsController::class, 'destroy'])->name('assets.delete');
    Route::get('assets/edit', [AssetsController::class, 'edit'])->name('assets.edit');
    Route::post('assets/update', [AssetsController::class, 'update'])->name('assets.update');
    Route::get('/assets/{id}/detail', [AssetsController::class, 'show'])->name('assets.show');
    Route::get('assets-location', [AssetsLocationController::class, 'mapping'])->name('assets.location');
    Route::get('/scrap-history', [AssetsHistoryController::class, 'index'])->name('inventory.history');
    Route::get('/maintenance', [AssetsController::class, 'showEditForm'])->name('assets.maintenance');

    Route::get('/assets/maintenance-needs', [AssetsController::class, 'maintenanceNeeds'])->name('assets.maintenance-needs');
    Route::get('/transaction-history/{assetCode}', [AssetsHistoryController::class, 'historyByAssetCode'])->name('transaction.history.byAssetCode');
    Route::get('/depreciation/{assetCode}', [AssetsHistoryController::class, 'depreciationByAssetCode'])->name('depreciation.byAssetCode');
    Route::get('history-maintenance', [MaintenanceHistoryController::class, 'index'])->name('assets.historymaintenance');


    Route::resource('merk', MerkController::class);
    Route::get('/merks', [MerkController::class, 'index'])->name('merk.index');
    Route::post('/merks', [MerkController::class, 'store'])->name('merk.store');
    Route::get('/merks/create', [MerkController::class, 'create'])->name('merk.create');
    Route::get('/merks/{id}/edit', [MerkController::class, 'edit'])->name('merk.edit');
    Route::put('/merks/{id}', [MerkController::class, 'update'])->name('merk.update');
    Route::delete('/merks/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');

    Route::get('/summary-report', [ReportController::class, 'summaryReport'])->name('summary.report');

});
