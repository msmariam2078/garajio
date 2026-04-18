<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UOMController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\ScrapController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WOTypeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WareHouseController;
use App\Http\Controllers\WORequestController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SkillGroupController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\EngineSpecsController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\ServicePartController;
use App\Http\Controllers\ShiftMasterController;
use App\Http\Controllers\VehicleMakeController;
use App\Http\Controllers\WhatsAppApiController;
use App\Http\Controllers\BookingItemsController;
use App\Http\Controllers\ItemCatagoryController;
use App\Http\Controllers\ItemcategoryController;
use App\Http\Controllers\ServiceGroupController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\WarrentyItemController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\RegionalSpecsController;
use App\Http\Controllers\ServiceMasterController;
use App\Http\Controllers\TechnicianLogController;
use App\Http\Controllers\AdjustmentItemController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\WarrentyExtendController;
use App\Http\Controllers\AddWarrantyItemsController;
use App\Http\Controllers\CustomerTemplateController;
use App\Http\Controllers\ServicePartwithVController;
use App\Http\Controllers\TechnicianWorkHoursController;
use App\Http\Controllers\WarrantyRegistrationController;
use App\Http\Controllers\ServicePartadjustMentController;
use App\Http\Controllers\BookingItemWarrentyIssuedController;
use App\Http\Controllers\TechnicianBookingAppointmentController;
use App\Http\Controllers\TransferController;

Route::get('/clear', function () {
	Artisan::call('optimize:clear');
	return back()->with('status', 'All caches cleared!');
})->name('clear');

Route::get('/git', [SettingController::class, 'gitInfo']);

require __DIR__ . '/auth.php';


Route::get('techcalender', [TechnicianWorkHoursController::class, 'index'])->name('calender')->middleware(
	[

		'XSS',
	]
);
Route::get('techevents', [TechnicianBookingAppointmentController::class, 'getEvents'])->name('events')->middleware(
	[

		'XSS',
	]
);
Route::get('home', [HomeController::class, 'index'])->name('home')->middleware(
	[

		'XSS',
	]
);
Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(
	[

		'XSS',
	]
);
Route::get('profile', [UserController::class, 'show'])->name('personalinfo')->middleware(
	[

		'XSS',
	]
);
Route::get('calend', [HomeController::class, 'calendar'])->name('calendar')->middleware(
	[

		'XSS',
	]
);

//-------------------------------User-------------------------------------------

Route::resource('users', UserController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);
Route::get('country', [UserController::class, 'getCountry'])->middleware(
	[
		'auth',
		'XSS',
	]

)->name('users.country');


Route::get('country', [UserController::class, 'getCountry'])->middleware(
	[
		'auth',
		'XSS',
	]

)->name('users.country');

//-------------------------------Subscription-------------------------------------------


Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {

		Route::resource('subscriptions', SubscriptionController::class);
		Route::get('coupons/history', [CouponController::class, 'history'])->name('coupons.history');
		Route::delete('coupons/history/{id}/destroy', [CouponController::class, 'historyDestroy'])->name('coupons.history.destroy');
		Route::get('coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
		Route::resource('coupons', CouponController::class);
		Route::get('subscription/transaction', [SubscriptionController::class, 'transaction'])->name('subscription.transaction');
	}
);

//-------------------------------Subscription Payment-------------------------------------------

Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {

		Route::post('subscription/{id}/stripe/payment', [SubscriptionController::class, 'stripePayment'])->name('subscription.stripe.payment');
	}
);
//-------------------------------Settings-------------------------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::get('settings/account', [SettingController::class, 'account'])->name('setting.account');
		Route::post('settings/account', [SettingController::class, 'accountData'])->name('setting.account.store');
		Route::delete('settings/account/delete', [SettingController::class, 'accountDelete'])->name('setting.account.delete');

		Route::get('settings/password', [SettingController::class, 'password'])->name('setting.password');
		Route::post('settings/password', [SettingController::class, 'passwordData'])->name('setting.password.store');

		Route::get('settings/general', [SettingController::class, 'general'])->name('setting.general');
		Route::post('settings/general', [SettingController::class, 'generalData'])->name('setting.general.store');

		Route::get('settings/smtp', [SettingController::class, 'smtp'])->name('setting.smtp');
		Route::post('settings/smtp', [SettingController::class, 'smtpData'])->name('setting.smtp.store');

		Route::get('settings/payment', [SettingController::class, 'payment'])->name('setting.payment');
		Route::post('settings/payment', [SettingController::class, 'paymentData'])->name('setting.payment.store');

		Route::get('settings/company', [SettingController::class, 'company'])->name('setting.company');
		Route::post('settings/company', [SettingController::class, 'companyData'])->name('setting.company.store');

		Route::get('language/{lang}', [SettingController::class, 'lanquageChange'])->name('language.change');
		Route::post('theme/settings', [SettingController::class, 'themeSettings'])->name('theme.settings');

		Route::get('settings/site-seo', [SettingController::class, 'siteSEO'])->name('setting.site.seo');
		Route::post('settings/site-seo', [SettingController::class, 'siteSEOData'])->name('setting.site.seo.store');

		Route::get('settings/google-recaptcha', [SettingController::class, 'googleRecaptcha'])->name('setting.google.recaptcha');
		Route::post('settings/google-recaptcha', [SettingController::class, 'googleRecaptchaData'])->name('setting.google.recaptcha.store');

		Route::get('settings/technician', [SettingController::class, 'technician'])->name('setting.technician');
		Route::post('settings/technician', [SettingController::class, 'technicianData'])->name('setting.technician.store');

		Route::get('settings/module/index', [ScrapController::class, 'moduleIndex'])->name('setting.module.index');
		Route::post('settings/module/store', [ScrapController::class, 'moduleStore'])->name('sales.invoices.store');

		Route::get('settings/scrap/index', [ScrapController::class, 'scrapIndex'])->name('setting.scrap.index');
		Route::post('/check-scrap', [ScrapController::class, 'checkScrap'])->name('check.scrap');
		Route::post('/check-scrap-stock', [ScrapController::class, 'checkScrapStock'])->name('check.scrap.stock');
		Route::post('/check-scrap-default', [ScrapController::class, 'checkScrapDefault'])->name('check.scrap.default');
	}
);


//-------------------------------Role & Permissions-------------------------------------------
Route::resource('permission', PermissionController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);

// Reset password
Route::get('reset-password', [PermissionController::class, 'resetPassword'])->name('reset-password');
Route::get('/get-email', [PermissionController::class, 'getEmail']);
Route::post('reset-password-now', [PermissionController::class, 'resetPasswordNow']);


Route::resource('role', RoleController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);


//-------------------------------Note-------------------------------------------
Route::resource('note', NoticeBoardController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);

//-------------------------------Contact-------------------------------------------
Route::resource('contact', ContactController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);


//-------------------------------transfer-------------------------------------------
Route::resource('transfer', TransferController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);
//-------------------------------logged History-------------------------------------------

Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {

		Route::get('logged/history', [UserController::class, 'loggedHistory'])->name('logged.history');
		Route::get('logged/{id}/history/show', [UserController::class, 'loggedHistoryShow'])->name('logged.history.show');
		Route::delete('logged/{id}/history', [UserController::class, 'loggedHistoryDestroy'])->name('logged.history.destroy');
	}
);


//-------------------------------Plan Payment-------------------------------------------

Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::post('subscription/{id}/bank-transfer', [PaymentController::class, 'subscriptionBankTransfer'])->name('subscription.bank.transfer');
		Route::get('subscription/{id}/bank-transfer/action/{status}', [PaymentController::class, 'subscriptionBankTransferAction'])->name('subscription.bank.transfer.action');
		Route::post('subscription/{id}/paypal', [PaymentController::class, 'subscriptionPaypal'])->name('subscription.paypal');
		Route::get('subscription/{id}/paypal/{status}', [PaymentController::class, 'subscriptionPaypalStatus'])->name('subscription.paypal.status');
	}
);

//-------------------------------Client-------------------------------------------
Route::get('client/new', [ClientController::class, 'direct'])->name('client.direct.create');
Route::get('/clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::get('/clients/searchcustomer', [ClientController::class, 'searchCustomer']);
Route::get('/get-vehicle-details/{id}', [VehicleController::class, 'getVehicleDetails']);

Route::get('/fetch-place', [ClientController::class, 'fetchPlace'])->middleware(['auth', 'XSS',]);
Route::get('/fetch-vehicle/{id}', [ClientController::class, 'fetchVehicle'])->name('client.fetchvehicle')->middleware(['auth', 'XSS',]);
Route::get('/fetch-bookinghistory/{id}', [ClientController::class, 'fetchBookingHistory'])->name('client.fetchBookingHistory')->middleware(['auth', 'XSS',]);
Route::get('/fetch-workorderhistory/{id}', [ClientController::class, 'fetchWorkOrderHistory'])->name('client.fetchWorkOrderHistory')->middleware(['auth', 'XSS',]);
Route::get('/validate', [ClientController::class, 'emailvalidate'])->name('validate')->middleware(['auth', 'XSS',]);

Route::resource('client', ClientController::class);
Route::get('clients-list', [ClientController::class, 'clientsList'])->name('clients-list');


Route::post('/update-status/{id}', [ClientController::class, 'updateStatus'])->name('client.updateStatus')->middleware(['auth', 'XSS',]);
Route::post('/update-status-technician/{id}', [TechnicianController::class, 'updateStatus'])->name('technician.updateStatus')->middleware(['auth', 'XSS',]);
Route::post('/update-status-vehicle/{id}', [VehicleController::class, 'updateStatus'])->name('vehicle.updateStatus')->middleware(['auth', 'XSS',]);

Route::post('/update-status-fleet/{id}', [FleetController::class, 'updateStatus'])->name('fleet.updateStatus')->middleware(['auth', 'XSS',]);


//-------------------------------Services & Parts-------------------------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::delete('services/tasks', [ServicePartController::class, 'taskDestroy'])->name('service.task.destroy');
		Route::resource('services-parts', ServicePartController::class);
		Route::post('services/masters', [ServicePartController::class, 'masters'])->name('service.task.masters');
		Route::get('/searchproducts', [ServicePartController::class, 'search']);
		Route::get('/searchproducts2', [ServicePartController::class, 'search2']);
		Route::get('/get-warehouse', [ServicePartController::class, 'getwarehouse'])->name('servicepart.warehouse');
		Route::get('/getwarehouseproducts', [ServicePartController::class, 'getwarehouseproduct']);
	}
);
//-------------------------------adjustment items----------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::resource('adjustment_item', AdjustmentItemController::class);
		Route::get('post/adjustment_item', [AdjustmentItemController::class, 'post'])->name('adjustment_item.post');
		Route::get('post_all/adjustment_item', [AdjustmentItemController::class, 'post_all'])->name('adjustment_item.post_all');
	}

);
//-----------------------------Inventory-------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::resource('inventory', InventoryController::class);
		Route::get('setup/inventory', [InventoryController::class, 'setup'])->name('inventory.setup');
		Route::post('setup/inventory', [InventoryController::class, 'savesetup'])->name('inventory.savesetup');
		Route::get('inventory-search', [InventoryController::class, 'inventorySearch']);
	}

);

//-------------------------------Asset-------------------------------------------
//-------------------------------WO Request-------------------------------------------
Route::resource('wo-request', WORequestController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);
//-------------------------------Estimation-------------------------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::get('estimation/{id}/status', [EstimationController::class, 'estimationStatus'])->name('estimation.status');
		Route::delete('estimation/service/part/destroy', [EstimationController::class, 'servicePartDestroy'])->name('estimation.service.part.destroy');
		Route::get('estimation/service/part', [EstimationController::class, 'getServicePart'])->name('estimation.service.part');
		Route::resource('estimation', EstimationController::class);
		Route::get('estimation.email/{id}', [EstimationController::class, 'estimationEmail']);
	}
);

Route::get('c', function () {

	$workorders = App\Models\WorkOrder::has('inv')->get();
	return $workorders;
});
//-------------------------------Work Order-------------------------------------------
Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::get('workorder-accepted/{accepted}', [WorkOrderController::class, 'accepted'])->name('workorder.accepted');
		Route::get('workorder/{id}/service/task/create', [WorkOrderController::class, 'serviceTaskCreate'])->name('workorder.service.task.create');
		Route::post('workorder/{id}/service/task/store', [WorkOrderController::class, 'serviceTaskStore'])->name('workorder.service.task.store');
		Route::get('workorder/{id}/service/task/{tid}/edit', [WorkOrderController::class, 'serviceTaskEdit'])->name('workorder.service.task.edit');
		Route::put('workorder/{id}/service/task/{tid}/update', [WorkOrderController::class, 'serviceTaskUpdate'])->name('workorder.service.task.update');
		Route::delete('workorder/{id}/service/task/{tid}/delete', [WorkOrderController::class, 'serviceTaskDestroy'])->name('workorder.service.task.destroy');

		Route::get('workorder/{id}/service/appointment', [WorkOrderController::class, 'serviceAppointment'])->name('workorder.service.appointment');
		Route::put('workorder/{id}/service/appointment/store', [WorkOrderController::class, 'serviceAppointmentStore'])->name('workorder.service.appointment.store');
		Route::delete('workorder/{id}/service/appointment/delete', [WorkOrderController::class, 'serviceAppointmentDestroy'])->name('workorder.service.appointment.destroy');
		Route::get('workorder/invoice', [WorkOrderController::class, 'getinvoice']);
		Route::post('workorder/{id}/status', [WorkOrderController::class, 'workorderStatus'])->name('workorder.status');
		Route::delete('workorder/service/part/destroy', [WorkOrderController::class, 'servicePartDestroy'])->name('workorder.service.part.destroy');
		Route::get('workorder/service/part', [WorkOrderController::class, 'getServicePart'])->name('workorder.service.part');
		Route::get('workorder/scrap/{id}/{source}', [WorkOrderController::class, 'workorderscrap']);
		Route::get('workorder/delete/scrap/{id}', [WorkOrderController::class, 'deletescrap'])->name('workorderscrap.delete');
		Route::post('workorder/scrap/save', [WorkOrderController::class, 'saveworkorderscrap'])->name('workorder.scrap');

		Route::get('workorder-search/', [WorkOrderController::class, 'workorderSearch']);

		Route::resource('warehouse', WareHouseController::class);

		Route::resource('servicepartwithvehicle', ServicePartwithVController::class);
		Route::get('servicePartsList', [ServicePartwithVController::class, 'servicePartsList'])->name('servicePartsList');

		Route::post('get_warehouse', [WareHouseController::class, 'getWarehouse'])->name('get.warehouse');
		Route::resource('workorder', WorkOrderController::class);
		Route::get('workorders-data', [WorkOrderController::class, 'getWorkOrders'])->name('workorders.data');

		Route::get('workorder-client/fetch/api/{id}', [WorkOrderController::class, 'clientfetch']);

		Route::post('workorder-search/tabs/fetch/api/{id}', [WorkOrderController::class, 'searchtabs'])->name('workorder.search-tabs');
		Route::get('workorder-search/tabs/technician/api/{id}', [WorkOrderController::class, 'techniciansearchtabs'])->name('workorder.search-tabs-technician');
		Route::post('/assign-technician-to-workorder',  [WorkOrderController::class, 'assigntechnicianwo'])->name('workorder.assigntechnicianwo');
		Route::post('/workorder-updateStatus/{id}',  [WORequestController::class, 'updateStatus'])->name('woRequest.updateStatus');
		Route::delete('/workorder/{workorder}/vehicle/{vehicle}', [WorkOrderController::class, 'vehicledestroy'])->name('workorder.vehicledestroy');
		Route::delete('/workorder/{workorder}/booking/{booking}', [WorkOrderController::class, 'bookingdestroy'])->name('workorder.bookingdestroy');
		Route::delete('/workorder/{workorder}/payment/{payment}', [WorkOrderController::class, 'paymentdestroy'])->name('workorder.paymentdestroy');
		Route::get('getone/workorder', [WorkOrderController::class, 'getOne'])->name('workorder.getOne');
		Route::get('getquotation/workorder', [WorkOrderController::class, 'getWorkorderQuotatons']);

		Route::post('vehiclesDetailsEdit', [WorkOrderController::class, 'vehiclesDetailsEdit'])->name('vehiclesDetailsEdit');
		Route::post('bookingDetailsEdit', [WorkOrderController::class, 'bookingDetailsEdit'])->name('bookingDetailsEdit');
		Route::post('workOrderImage', [WorkOrderController::class, 'workOrderImage'])->name('workOrderImage');
		Route::get('workOrderImageDelete/{id}', [WorkOrderController::class, 'workOrderImageDelete'])->name('workOrderImageDelete');
	}
);


//-------------------------------WO Type-------------------------------------------
Route::resource('wo-type', WOTypeController::class)->middleware(
	[
		'auth',
		'XSS',
	]
);

//-------------------------------Invoice-------------------------------------------

Route::group(
	[
		'middleware' => [
			'auth',
			'XSS',
		],
	],
	function () {
		Route::get('client/workorder/list', [InvoiceController::class, 'getWorkorder'])->name('client.workorder');
		Route::get('workorders/details', [InvoiceController::class, 'getWorkorderDetails'])->name('workorder.details');
		Route::resource('invoice', InvoiceController::class);
	}
);




//-------------------------------Inquiry-------------------------------------------
Route::resource('inquiry', InquiryController::class)->names('inquiry')->middleware(['auth', 'XSS',]);
Route::post('client-vehical', [InquiryController::class, 'getClientVehical'])->name('client.vehical')->middleware(['auth', 'XSS']);
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/search-user2', [HomeController::class, 'search2'])->name('search2');

//-------------------------------Vehicle-------------------------------------------
Route::get('vehicle/new', [VehicleController::class, 'direct'])->name('vehicle.direct.create');

Route::get('/get-vehicle-models/{makeId}', [VehicleController::class, 'getModels'])->name('get.vehicle.models')->middleware(['auth', 'XSS',]);

Route::get('/get-engine-specification', [VehicleController::class, 'getEngineSpecification'])->name('get.engine.specification')->middleware(['auth', 'XSS',]);

Route::resource('vehicle', VehicleController::class)->names('vehicle')->middleware(['auth', 'XSS',]);
Route::get('vehicles-list', [VehicleController::class, 'vehiclesList'])->name('vehicles-list');

Route::resource('vehiclemake', VehicleMakeController::class)->names('vehiclemake')->middleware(['auth', 'XSS',]);

Route::resource('customergroup', CustomerGroupController::class)->names('customergroup')->middleware(['auth', 'XSS',]);

Route::resource('vehiclemodel', VehicleModelController::class)->names('vehiclemodel')->middleware(['auth', 'XSS',]);
//-------------------------------Equipment-------------------------------------------
Route::resource('equipment', EquipmentController::class);

//-------------------------------Equipment-------------------------------------------
Route::resource('skill', SkillController::class);

Route::resource('technicianlog', TechnicianLogController::class);
//-------------------------------Service Master-------------------------------------------
Route::resource('service', ServiceMasterController::class);

Route::resource('enginespecification', EngineSpecsController::class);

Route::resource('regionalspecs', RegionalSpecsController::class);

Route::resource('itemcategory', ItemCatagoryController::class);

Route::resource('shiftmasters', ShiftMasterController::class);

//-------------------------------Booking-------------------------------------------
Route::resource('booking', BookingController::class);
Route::post('bookedinvoice', [BookingController::class, 'invoiceandpayment'])->name('booking.invoice');
Route::post('startbooking', [BookingController::class, 'start'])->name('booking.start');
Route::post('storeinvoice', [InvoiceController::class, 'invoicestore'])->name('store.invoice');
Route::post('storefinalinvoice', [InvoiceController::class, 'invoicestorefinal'])->name('invoice.finalstore');
Route::post('storepayment', [InvoicePaymentController::class, 'storepayment'])->name('store.payment');
Route::get('/get-vehicle', [BookingController::class, 'getVehicleByClient'])->name('clientVehicles');
Route::get('/bookinginvoice/{id}', [BookingController::class, 'bookinginvoice']);
Route::get('/technicianbookinginvoice/{id}', [BookingController::class, 'bookinginvoicetechnician']);
Route::get('/transferpaymentinvoice/{id}', [BookingController::class, 'paymentinvoice'])->name('workorder.payment');
Route::get('booking/service/remvoe/{id}', [BookingController::class, 'removeservice']);
Route::get('cancelquotation/{id}/{source}', [BookingController::class, 'cancelquotation'])->name('quotation.cancel');
Route::get('acceptquotation/{id}', [BookingController::class, 'acceptquotation'])->name('quotation.accept');
Route::get('deletequotation/{id}', [BookingController::class, 'deletequotation'])->name('quotation.delete');
Route::get('confirmquotation/{id}', [BookingController::class, 'confirmquotation'])->name('quotation.confirm');
Route::post('quotationupdate', [BookingController::class, 'updatequotation'])->name('quotation.update');
Route::post('booking-edit', [BookingController::class, 'bookingEdit'])->name('bookingEdit');

Route::get('/transferpaymentinvoicetechnician/{id}', [BookingController::class, 'paymentinvoicetechnician']);

// Alert Start 
//------------------------------Technician Dashboard-------------------------------------
Route::get('/booking-alert', [HomeController::class, 'ajaxAlertdata'])->name('ajax.alert');
Route::get('/quatation-accept-alert', [HomeController::class, 'ajaxAlertdataQuotation'])->name('ajax.alertQuotation');

// ------------- Dashboard Alerts -------------------------------------------------------
Route::get('/inspection-accept-alert', [HomeController::class, 'ajaxAlertdataInspectionComplete'])->name('ajax.alertInspectionCompleted');
Route::get('/work-complete-alert', [HomeController::class, 'ajaxAlertdataWorkComplete'])->name('ajax.alertWorkCompleted');

// Alert End 

//-------------------------------Service Group-------------------------------------------
Route::resource('service-group', ServiceGroupController::class);

Route::resource('engine-specification', ServiceGroupController::class);

Route::resource('customer-template', CustomerTemplateController::class);
//-------------------------------Vehicle Details-------------------------------------------
Route::get('/vehicle-info/{id}', [WorkOrderController::class, 'getVehicleInfo']);
//-------------------------------SkillGroup---------------------------------------

Route::get('quotations/{filename}', function ($filename) {
	$filePath = public_path('quotations/' . $filename);
	if (file_exists($filePath)) {
		return response()->download($filePath);
	} else {
		abort(404, 'File not found');
	}
});


Route::get('/testpage', [WorkOrderController::class, 'getTestPage']);

Route::get('/invoicetemplate', [BookingController::class, 'getinvoice']);

Route::get('booking-quotation/{id}', [BookingController::class, 'generatequotation'])->name('booking.generatequotation');

Route::get('/generate-invoice-pdf/{bookingId}', [BookingController::class, 'generateInvoicePDF']);

Route::get('/generate-invoice-pdf2/{id}', [InvoiceController::class, 'print'])->name('invoice_pdf');

Route::post('/send-invoice-email/{quoteIdId}', [BookingController::class, 'sendInvoiceEmail']);
Route::post('/send-invoice-email2/{id}', [InvoiceController::class, 'sendInvoiceEmail']);
Route::get('/get-vehicle-models/{makeId}', [VehicleController::class, 'getModels'])->name('get.vehicle.models');

Route::get('/get-engine-specification', [VehicleController::class, 'getEngineSpecification'])->name('get.engine.specification');


//-------------------------------Inspection-------------------------------------------
Route::resource('inspection', InspectionController::class);

Route::get('inspectionconfirm/{id}', [InspectionController::class, 'confirminspection'])->name('inspection.confirm');

Route::post('/send-inspection-email/{id}', [InspectionController::class, 'sendInspectEmail']);
Route::get('groups/inspection', [InspectionController::class, 'groupindex'])->name('inspection.groupindex');
Route::get('groupcreate/inspection', [InspectionController::class, 'groupcreate'])->name('inspection.groupcreate');
Route::post('groupstore/inspection', [InspectionController::class, 'groupstore'])->name('inspection.groupstore');
Route::get('groupedit/inspection/{id}', [InspectionController::class, 'groupedit'])->name('inspection.groupedit');
Route::post('groupupdate/inspection/{id}', [InspectionController::class, 'groupupdate'])->name('inspection.groupupdate');
Route::delete('groupdestroy/inspection/{id}', [InspectionController::class, 'groupdestroy'])->name('inspection.groupdestroy');
Route::get('grouppoint/inspection/{id}', [InspectionController::class, 'grouppoints'])->name('inspection.grouppoints');
Route::get('grouppoint2/inspection/{id}', [InspectionController::class, 'grouppoints2'])->name('inspection.grouppoints2');

Route::get('groupmainpoint/inspection/{id}', [InspectionController::class, 'groupmainpoints'])->name('inspection.groupmainpoints');

Route::post('groupmainpoint/save/inspection', [InspectionController::class, 'storemainpoints'])->name('inspection.groupmainpointsstore');

Route::post('grouppoint/save/inspection/{id}', [InspectionController::class, 'storegrouppoints'])->name('inspection.grouppointsstore');

Route::get('templates/inspection', [InspectionController::class, 'templatesindex'])->name('inspection.templatesindex');
Route::get('templatescreate/inspection', [InspectionController::class, 'templatescreate'])->name('inspection.templatescreate');
Route::post('templatesstore/inspection', [InspectionController::class, 'templatesstore'])->name('inspection.templatesstore');
Route::get('templatesedit/inspection/{id}', [InspectionController::class, 'templatesedit'])->name('inspection.templatesedit');
Route::post('templatesupdate/inspection/{id}', [InspectionController::class, 'templatesupdate'])->name('inspection.templatesupdate');
Route::delete('templatesdestroy/inspection/{id}', [InspectionController::class, 'templatesdestroy'])->name('inspection.templatesdestroy');

Route::get('/bookings/{customerId}', [InspectionController::class, 'getBookings']);
Route::get('/vehicles/{bookingId}', [InspectionController::class, 'getVehicleInfo']);
Route::get('/templates/{templateId}', [InspectionController::class, 'getTemplateData']);
Route::get('/fetch-template-details', [InspectionController::class, 'getTemplateDetails']);
Route::post('/update-status-inspection/{id}', [InspectionController::class, 'updateStatus'])->name('inspection.updateStatus')->middleware(['auth', 'XSS',]);

Route::get('cancelbooking/{id}', [BookingController::class, 'cancelbooking'])->name('booking.cancel');
Route::get('cancelworkorder/{id}', [Workorder::class, 'cancelworkorder'])->name('workorder.cancel');
Route::get('addwarrenty/{id}', [WarrentyItemController::class, 'show'])->name('productitems.addwarrenty');
Route::get('cancelworkorder/{id}', [WorkOrderController::class, 'cancel'])->name('workorder.cancel');
Route::post('/booking/cancel', [BookingController::class, 'postCancelBooking'])->name('booking.postcancelbooking');
Route::post('/workorder/cancel', [WorkOrderController::class, 'cancelworkorder'])->name('cancel.postworkordercancel');
Route::post('/quotation/cancel', [BookingController::class, 'postquotationcancel'])->name('booking.postcancelquotation');
Route::get('booktechnicians/{technicianId}/{workOrderId}', [TechnicianController::class, 'bookTechnicians'])->name('book.technicians');
Route::get('allocatetechnician', [BookingController::class, 'softallocatetechnician'])->name('soft.allocatetechnician');
Route::post('/bookquotation', [BookingController::class, 'storebookingquotation'])->name('bookingquotation.store');
Route::get('/bookings/{customerId}', [BookingController::class, 'getBookingId']);
Route::post('/workorder/removeTechnician', [WorkOrderController::class, 'removeTechnician'])->name('workorder.removeTechnician');
Route::get('inspectionbooking/{id}', [BookingController::class, 'getbookinginspection']);
Route::post('/bookinspection', [BookingController::class, 'storebookininspection'])->name('bookinginspection.store');
Route::post('/updatebookinspection', [BookingController::class, 'updatebookininspection'])->name('updatebookinginspection.store');

//-------------------------------warranty-------------------------------------------


Route::resource('bookingitems-warrenty', BookingItemWarrentyIssuedController::class);
Route::resource('warrentyitems', WarrentyItemController::class);

Route::resource('warrentyextend', WarrentyExtendController::class);
Route::resource('warrentyRegistration', WarrantyRegistrationController::class);

Route::get('warrenty-registration-list', [WarrantyRegistrationController::class, 'index'])->name('warrentyRegistrationList');


Route::resource('bookingitems', BookingItemsController::class);
//Route::resource('get-product', [WarrantyRegistrationController::class,"getproduct"]);


//-------------------------------Payment-------------------------------------------
Route::resource('payment', InvoicePaymentController::class);
Route::get('pdf/{id}', [InvoicePaymentController::class, 'pdfview'])->name('pdf');
Route::get('/download/{id}', [InvoicePaymentController::class, 'download'])->name('download');
Route::get('/send-email/{id}', [InvoicePaymentController::class, 'sendemail'])->name('sendemail');

Route::resource('uom', UOMController::class);

Route::resource('brand', BrandController::class);

Route::resource('origin', OriginController::class);

Route::resource('itemcategory', WarrentyExtendController::class);

Route::resource('servicepartadjustment', ServicePartadjustMentController::class);
Route::get('adjustment-search', [ServicePartadjustMentController::class, 'adjustmentSearch']);


Route::resource('categories', ItemcategoryController::class);


Route::get('getWarehouseByServicePart', [AdjustmentItemController::class, 'getWarehouseByServicePart'])->name('getWarehouseByServicePart');


Route::get('/get-invoices', [InvoicePaymentController::class, 'getInvoicesByClient']);
//-------------------------------SkillGroup---------------------------------------
Route::resource('skill-group', SkillGroupController::class);

//-------------------------------Technician-------------------------------------------
Route::resource('technician', TechnicianController::class)->middleware(['auth', 'XSS',]);
Route::get('technician/editprofile/{id}', [TechnicianController::class, 'editprofile'])->name('edittechnician.editprofile');
// Route::get('technician/destroy/{id}', [TechnicianController::class, 'destroy'])->name('technician.destroy');
Route::post('work-order/change-status/{id}', [TechnicianController::class, 'changeWorkOrderStatus'])->name('change.workorder.status');

Route::get('whatsapp/chat', [WhatsAppApiController::class, 'index'])->name('whatsapp.chat.index')->middleware(['auth', 'XSS',]);
Route::post('/send-message/{client_id}', [WhatsAppApiController::class, 'sendMessage'])->name('whatsapp.send.message')->middleware(['auth', 'XSS',]);
Route::post('search-client', [WhatsAppApiController::class, 'searchClient'])->name('whatsapp.search.client')->middleware(['auth', 'XSS']);

Route::post('/checkin', [TechnicianController::class, 'checkIn'])->name('checkin')->middleware(['auth', 'XSS',]);
Route::post('/checkout', [TechnicianController::class, 'checkOut'])->name('checkout')->middleware(['auth', 'XSS',]);
Route::resource('fleet', FleetController::class)->middleware(['auth', 'XSS',]);

//-------------------------------Technician TimeSlot-------------------------------------------
Route::put('/technician/update-hours/{technician}', [TechnicianController::class, 'updateWorkHours'])->name('technician.update-hours');
Route::get('/technician-availability', [TechnicianController::class, 'getTechnicianAvailability'])->name('technician.availability');
Route::post('technicianallocate', [TechnicianController::class, 'allocatetechnician'])->name('allocate.technician');
Route::get('gettechnicianallocate/{id}', [WorkOrderController::class, 'getallocatetechnician'])->name('allocate.gettechnician');
Route::post('accepttechnicianallocate', [TechnicianController::class, 'acceptallocatetechnician'])->name('allocate.accepttechnician');
Route::get('/invoice/newcreate/{workorder}/{customer}', [InvoiceController::class, 'newcreate'])->name('invoice.newcreate');

Route::post('vehiclemakeajax', [VehicleMakeController::class, 'storeajax'])->name('vehiclemake.ajax');
Route::post('validate-phone-number', [ClientController::class, 'validatePhoneNumber'])->name('validatenumber');
Route::get('/workorder/{workorderid}/edit', [WorkOrderController::class, 'edit']);
Route::get('/getworkorder', [WorkOrderController::class, 'getOne'])->name('warranty_registration.getOne');

Route::get('/editdetails/{id}', [BookingController::class, 'editdetails'])->name('booking.editdetails');
Route::put('/updatedetails/{id}', [BookingController::class, 'updatedetails'])->name('booking.updatedetails');
Route::get('/workorder/details/{id}', [WorkOrderController::class, 'getDetails'])->name('workorder.techdetails');



Route::get('/technicianinvoice', [TechnicianController::class, 'invoice'])->name('invoice.technicianindex');
Route::get('/invoice-search', [TechnicianController::class, 'invoiceSearch']);

Route::get('/technicianpayment', [TechnicianController::class, 'payment'])->name('payment.technicianindex');
Route::get('/payment-search', [TechnicianController::class, 'paymentSearch']);

Route::get('/workorders', [TechnicianController::class, 'workOrders'])->name('workorders.technicianindex');
Route::get('/workorderstechnicians/{id}', [TechnicianController::class, 'wofortechnician'])->name('workorder.technicianview');

Route::get('/getvehicleedit/{id}', [VehicleController::class, 'gettechvehiclemodal'])->name('vehicle.technicianedit');

Route::post('updatevehicledetails', [VehicleController::class, 'updateVehicleDetails'])->name('technician.updatevehicledetails');


Route::get('/updatestatus/{id}', [TechnicianController::class, 'updateworkorder'])->name('workorders.updatestatus');
Route::post('/workorder/updatetechstatus', [WorkOrderController::class, 'updateTechStatus'])->name('workorder.updatetechstatus');

Route::get('/toggle-warranty/{customer_id}/{vehicle_id}', [WarrantyRegistrationController::class, 'getWarrantyRegistration']);
Route::get('/toggle-warranty2/{customer_id}/{vehicle_id}', [WarrantyRegistrationController::class, 'getWarrantyRegistrationone']);

Route::post('/warrantyclaim/{id}', [WarrantyRegistrationController::class, 'warrantyclaim'])->name('warranty.claim');


Route::post('/jumpstart/{id}', [WarrantyRegistrationController::class, 'jumpstart'])->name('jump.start');


Route::post('/journal/store', [BookingController::class, 'storecomment'])->name('journal.store');
Route::get('/journal/fetch', [BookingController::class, 'fetchcomment'])->name('journal.fetch');
Route::post('/journal/updatecount', [BookingController::class, 'updatecount'])->name('journal.update-count');


Route::get('/bookingclientcreate', [BookingController::class, 'clientcreate'])->name('booking.createclient');

Route::get('/clients/getcustomer', [ClientController::class, 'getCustomer']);


Route::get('/clear-session', function (Request $request) {

	$request->session()->forget('customer_id');
	$request->session()->forget('vehicle_id');

	return response()->json(['status' => 'success']);
});
Route::get('/check-rego', [VehicleController::class, 'checkRego'])->name('check.rego');

// admin chat
Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/fetch-users/{userId}', [ChatController::class, 'fetchUsers']);
Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::get('/fetch-messages/{userId}', [ChatController::class, 'fetchMessages']);
Route::post('/get-messages', [ChatController::class, 'getMessages'])->name('get.messages');
Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');
Route::get('/search-user', [ChatController::class, 'searchUser']);


// technician chat
Route::get('/tax-invoice', [ReportController::class, 'taxinvoice'])->name('taxinvoice');
Route::get('/paymentreport', [ReportController::class, 'paymentreport'])->name('paymentreport');

Route::get('/add-warranty/{product_id}/{workorder_id}', [WorkOrderController::class, 'addwarranty'])->name('technician.warranty');

Route::post('/technician/addwarranty/store', [AddWarrantyItemsController::class, 'store'])->name('technician.addwarranty.store');

Route::get('/tech-chat', [ChatController::class, 'index'])->name('tech.chat');

// Send email
Route::get('mail', [ChatController::class, 'mail']);
Route::post('make-email', [ChatController::class, 'makeEmail'])->name('makeEmail');

Route::post('edit.user', [UserController::class, 'editUser']);
