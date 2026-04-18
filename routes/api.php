<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\VehicleMasterController;
use App\Http\Controllers\Api\ItemMasterController;
use App\Http\Controllers\Api\CustomerTemplateController;
use App\Http\Controllers\Api\WareHouseController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UOMController;
use App\Http\Controllers\Api\ItemCategoryController;
use App\Http\Controllers\Api\ServicePartAdjustmentController;
use App\Http\Controllers\Api\TechnicianController;
use App\Http\Controllers\Api\WorkOrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\OriginController;

/*
|-------------------------------------------------------------------------- 
| API Routes 
|-------------------------------------------------------------------------- 
| 
| Here is where you can register API routes for your application. These 
| routes are loaded by the RouteServiceProvider within a group which 
| is assigned the "api" middleware group. Enjoy building your API! 
| 
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('garajilo')->name('api.')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthApiController::class, 'signup']);
        Route::post('login', [AuthApiController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthApiController::class, 'logout']);
            Route::post('testmessage', [AuthApiController::class, 'testmessage']); 
            Route::get('customers', [CustomerController::class, 'index']);
            Route::post('storecustomers', [CustomerController::class, 'store']);
            Route::put('updatecustomer/{id}', [CustomerController::class, 'update']);
            Route::post('postupdatecustomer', [CustomerController::class, 'updatecustomer']);
            Route::delete('users/{id}', [CustomerController::class, 'destroy']);
            //vehicle make
            Route::get('vehiclemake', [VehicleController::class, 'vehiclemake']);
            Route::post('postvehiclemake', [VehicleController::class, 'postvehiclemake']);
            Route::post('updatevehicle-make', [VehicleController::class, 'updateVehicleMake']);
              Route::delete('deletevehicle-make/{id}', [VehicleController::class, 'deleteVehicleMake']);

            //vehicle transmission
            Route::get('vehicletransmission', [VehicleController::class, 'vehicletransmission']);
            Route::post('postvehicletransmission', [VehicleController::class, 'postvehicletransmission']);

            //Vehicle Body Type
            Route::get('bodytype', [VehicleController::class, 'bodytype']);
            Route::post('postbodytype', [VehicleController::class, 'postbodytype']);

             //Vehicle Color
             Route::get('color', [VehicleController::class, 'color']);
             Route::post('postcolor', [VehicleController::class, 'postcolor']);

             //Vehicle Seating Request
             Route::get('seat', [VehicleController::class, 'seatingcapacity']);
             Route::post('postseatingcapacity', [VehicleController::class, 'postseattingcapacity']);

              //Driver Type
              Route::get('drivingtype', [VehicleController::class, 'drivingtype']);
              Route::post('postdrivingtype', [VehicleController::class, 'postdrivingtype']);

              //Vehicle Model
              Route::get('vehiclemodel', [VehicleController::class, 'vehiclemodel']);
              Route::post('postvehiclemodel', [VehicleController::class, 'postvehiclemodel']);
              Route::post('updatevehicle-model', [VehicleController::class, 'updatevehiclemodel']);
              Route::delete('deletevehicle-model/{id}', [VehicleController::class, 'deletevehiclemodel']);

              //Customer Template
              Route::get('customer-template', [CustomerTemplateController::class, 'customertemplate']);
              Route::post('postcustomer-template', [CustomerTemplateController::class, 'postcustomertemplate']);
              Route::post('updatecustomer-template', [CustomerTemplateController::class, 'updatecustomertemplate']);
              Route::delete('deletecustomer-template/{id}', [CustomerTemplateController::class, 'destroy']);

              //engine specs
              Route::get('engine-specs', [VehicleController::class, 'enginespecs']);
              Route::post('postengine-specs', [VehicleController::class, 'postenginespecs']);
              Route::post('updateengine-specs', [VehicleController::class, 'updateEngineSpecs']);
              Route::delete('deleteengine-specs/{id}', [VehicleController::class, 'deleteenginespecs']);


            //    regional specs
              Route::get('regional-specs', [VehicleController::class, 'regionalspecs']);
              Route::post('postregional-specs', [VehicleController::class, 'postregionalspecs']);
              Route::post('updateregional-specs', [VehicleController::class, 'updateregionalspecs']);
              Route::delete('deleteregional-specs/{id}', [VehicleController::class, 'deleteregionalspecs']);


               //   vehicle sapi
               Route::get('vehicles', [VehicleMasterController::class, 'index']);
               Route::post('postvehicles', [VehicleMasterController::class, 'store']);
               Route::post('updatevehicles', [VehicleMasterController::class, 'update']);
               Route::delete('deletevehicles/{id}', [VehicleMasterController::class, 'destroy']);
                //   item master api
                Route::post('postitemmaster', [ItemMasterController::class, 'store']);
                Route::post('updateitemmaster', [ItemMasterController::class, 'update']);
                Route::delete('deleteitemmaster/{id}', [ItemMasterController::class, 'destroy']);
                //warehouse api

                Route::post('postwarehouse', [WareHouseController::class, 'store']);
                Route::post('updatewarehouse', [WareHouseController::class, 'update']);
                Route::delete('deletewarehouse/{id}', [WareHouseController::class, 'destroy']);


               //   Booking sapi
               Route::get('bookings', [BookingController::class, 'index']);
               Route::post('postvehicles', [VehicleMasterController::class, 'store']);
               Route::post('updatevehicles', [VehicleMasterController::class, 'update']);
               Route::delete('deletevehicles/{id}', [VehicleMasterController::class, 'destroy']);
               //uom
              Route::get('uom', [UOMController::class, 'index']);
              Route::post('postuom', [UOMController::class, 'store']);
              Route::post('updateuom', [UOMController::class, 'update']);
              Route::delete('deleteuom/{id}', [UOMController::class, 'destroy']);

              //categories
              Route::get('itemcategory', [ItemCategoryController::class, 'index']);
              Route::post('postitemcategory', [ItemCategoryController::class, 'store']);
              Route::post('updateitemcategory', [ItemCategoryController::class, 'update']);
              Route::delete('deleteitemcategory/{id}', [ItemCategoryController::class, 'destroy']);


               //service part adjustment
               Route::get('servicepartadjustment', [ServicePartAdjustmentController::class, 'index']);
               Route::post('postservicepartadjustment', [ServicePartAdjustmentController::class, 'store']);
               Route::post('updateservicepartadjustment', [ServicePartAdjustmentController::class, 'update']);
               Route::delete('deleteitemcategory/{id}', [ItemCategoryController::class, 'destroy']);

                //technician controller
                Route::get('technicians', [TechnicianController::class, 'index']);
                Route::post('posttechnicians', [TechnicianController::class, 'store']);
                Route::post('updatetechnician', [TechnicianController::class, 'update']);
                Route::delete('deletetechnician/{id}', [TechnicianController::class, 'destroy']);

                 //technician controller
                Route::get('getworkorder', [WorkOrderController::class, 'index']);
                Route::post('updateworkorder', [WorkOrderController::class, 'update']);

                Route::get('getpayment', [PaymentController::class, 'index']);
                Route::post('updatepayment', [PaymentController::class, 'update']);

                //brand
                Route::get('brand', [BrandController::class, 'index']);
                Route::post('postbrand', [BrandController::class, 'store']);
                Route::post('updatebrand', [BrandController::class, 'update']);
                Route::delete('delete-brand/{id}', [BrandController::class, 'destroy']);


                 //brand
                 Route::get('origin', [OriginController::class, 'index']);
                 Route::post('postorigin', [OriginController::class, 'store']);
                 Route::post('updateorigin', [OriginController::class, 'update']);
                 Route::delete('delete-origin/{id}', [OriginController::class, 'destroy']);
              
        });
    });
});