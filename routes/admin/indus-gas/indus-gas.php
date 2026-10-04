<?php

use App\Livewire\Admin\IndusGas\ComingSoon;
use App\Livewire\Admin\IndusGas\Customers\CustomerForm;
use App\Livewire\Admin\IndusGas\Customers\CustomerIndex;
use App\Livewire\Admin\IndusGas\Customers\CustomerShow;
use App\Livewire\Admin\IndusGas\Billing\BillingIndex;
use App\Livewire\Admin\IndusGas\Billing\LedgerShow;
use App\Livewire\Admin\IndusGas\Billing\PaymentForm;
use App\Http\Controllers\Admin\IndusGas\ExpensesController;
use App\Livewire\Admin\IndusGas\Reports\ReportIndex;
use App\Http\Controllers\IndusGasPdfController;
use App\Livewire\Admin\IndusGas\Operations\DeliveryForm;
use App\Livewire\Admin\IndusGas\Operations\OperationsIndex;
use App\Livewire\Admin\IndusGas\Operations\RefillForm;
use App\Livewire\Admin\IndusGas\Operations\StockIndex;
use App\Livewire\Admin\IndusGas\Settings\BusinessProfileForm;
use App\Livewire\Admin\IndusGas\Settings\CylinderTypeForm;
use App\Livewire\Admin\IndusGas\Settings\CylinderTypeIndex;
use App\Livewire\Admin\IndusGas\Settings\SettingsIndex;
use App\Livewire\Admin\IndusGas\Settings\SupplierForm;
use App\Livewire\Admin\IndusGas\Settings\SupplierIndex;
use App\Livewire\Admin\IndusGas\Settings\VehicleForm;
use App\Livewire\Admin\IndusGas\Settings\VehicleIndex;
use App\Livewire\Admin\IndusGas\Staff\StaffForm;
use App\Livewire\Admin\IndusGas\Staff\StaffIndex;
use App\Livewire\Admin\IndusGas\Expenses\ExpenseDayDetails;
use App\Livewire\Admin\IndusGas\Expenses\ExpensePayerBoard;
use App\Livewire\Admin\IndusGas\LpgPlant\LpgPlantForm;
use App\Livewire\Admin\IndusGas\LpgPlant\LpgPlantIndex;
use App\Http\Controllers\IndusGasLpgPlantPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/indus-gas/dashboard', ComingSoon::class)
    ->defaults('feature', 'Dashboard')
    ->name('admin.indus-gas.dashboard');

Route::get('/indus-gas/customers', CustomerIndex::class)->name('admin.indus-gas.customers');
Route::get('/indus-gas/customers/create', CustomerForm::class)->name('admin.indus-gas.customers.create');
Route::get('/indus-gas/customers/{customer}/edit', CustomerForm::class)->name('admin.indus-gas.customers.edit');
Route::get('/indus-gas/customers/{customer}', CustomerShow::class)->name('admin.indus-gas.customers.show');

Route::get('/indus-gas/daily-operations', OperationsIndex::class)->name('admin.indus-gas.daily-operations');
Route::get('/indus-gas/daily-operations/refills/create', RefillForm::class)->name('admin.indus-gas.refills.create');
Route::get('/indus-gas/daily-operations/deliveries/create', DeliveryForm::class)->name('admin.indus-gas.deliveries.create');
Route::get('/indus-gas/daily-operations/stock', StockIndex::class)->name('admin.indus-gas.stock');

Route::get('/indus-gas/lpg-plant', LpgPlantIndex::class)->name('admin.indus-gas.lpg-plant');
Route::get('/indus-gas/lpg-plant/{entry}/edit', LpgPlantForm::class)->name('admin.indus-gas.lpg-plant.edit');
Route::get('/indus-gas/lpg-plant-rate-report', IndusGasLpgPlantPdfController::class)->name('admin.indus-gas.lpg-plant.report');

Route::get('/indus-gas/payments-ledgers', BillingIndex::class)->name('admin.indus-gas.payments-ledgers');
Route::get('/indus-gas/payments-ledgers/payments/create', PaymentForm::class)->name('admin.indus-gas.payments.create');
Route::get('/indus-gas/payments-ledgers/customers/{customer}', LedgerShow::class)->name('admin.indus-gas.ledgers.show');
Route::get('/indus-gas/invoices/{invoice}/pdf', [IndusGasPdfController::class, 'invoice'])->name('admin.indus-gas.invoices.pdf');
Route::get('/indus-gas/customers/{customer}/ledger-pdf', [IndusGasPdfController::class, 'ledger'])->name('admin.indus-gas.ledgers.pdf');

Route::get('/indus-gas/expenses', ExpensesController::class)->name('admin.indus-gas.expenses');
Route::get('/indus-gas/expenses/{expense}/payers', ExpensePayerBoard::class)->whereNumber('expense')->name('admin.indus-gas.expenses.payers');
Route::get('/indus-gas/expenses/{expense}', ExpenseDayDetails::class)->whereNumber('expense')->name('admin.indus-gas.expenses.day');
Route::get('/indus-gas/staff', StaffIndex::class)->name('admin.indus-gas.staff');
Route::get('/indus-gas/staff/create', StaffForm::class)->name('admin.indus-gas.staff.create');
Route::get('/indus-gas/staff/{staff}/edit', StaffForm::class)->name('admin.indus-gas.staff.edit');

Route::get('/indus-gas/reports', ReportIndex::class)->name('admin.indus-gas.reports');

Route::get('/indus-gas/documents', ComingSoon::class)
    ->defaults('feature', 'Documents')
    ->name('admin.indus-gas.documents');

Route::prefix('/indus-gas/settings')->name('admin.indus-gas.settings.')->group(function () {
    Route::get('/', SettingsIndex::class)->name('index');
    Route::get('/business-profile', BusinessProfileForm::class)->name('business-profile');

    Route::get('/suppliers', SupplierIndex::class)->name('suppliers');
    Route::get('/suppliers/create', SupplierForm::class)->name('suppliers.create');
    Route::get('/suppliers/{supplier}/edit', SupplierForm::class)->name('suppliers.edit');

    Route::get('/cylinder-types', CylinderTypeIndex::class)->name('cylinder-types');
    Route::get('/cylinder-types/create', CylinderTypeForm::class)->name('cylinder-types.create');
    Route::get('/cylinder-types/{cylinderType}/edit', CylinderTypeForm::class)->name('cylinder-types.edit');

    Route::get('/vehicles', VehicleIndex::class)->name('vehicles');
    Route::get('/vehicles/create', VehicleForm::class)->name('vehicles.create');
    Route::get('/vehicles/{vehicle}/edit', VehicleForm::class)->name('vehicles.edit');

});
