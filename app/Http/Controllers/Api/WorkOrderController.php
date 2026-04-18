<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkOrderResource;
use App\Http\Requests\Api\WareHouseRequest;
use App\Models\WorkOrder;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends BaseApiController
{
   

    public function index(Request $request)
    {
        try {
            $workorders = WorkOrder::whereHas('inv')
                ->leftJoin('bookings', 'bookings.workorderid', '=', 'work_orders.id')
                ->leftJoin('booking_quotations', 'booking_quotations.booking_id', '=', 'bookings.id')
                ->leftJoin('booking_items', 'booking_items.quotation_id', '=', 'booking_quotations.id')
                ->select(
                    'work_orders.*', 
                    'booking_quotations.id as quotation_id',
                    'booking_quotations.status as quotation_status',
                    'booking_items.product_id',
                    'booking_items.product_name',
                    'booking_items.qty',
                    'booking_items.unit_price',
                    'booking_items.gstprice',
                    'booking_items.linetotal',
                    'booking_items.warrenty',
                    'booking_items.location',
                    'booking_items.taxpercentage',
                    'booking_items.taxamount',
                    'booking_items.totalamount',
                    'booking_items.uom',
                    'booking_items.IsBCToPortalIntegrated as biIsBCToPortalIntegrated',
                    'booking_items.BCToPortalIntegratedTime as biBCToPortalIntegratedTime',
                    'booking_items.IsPortalToBCIntegrated as biIsPortalToBCIntegrated',
                    'booking_items.PortalToBCIntegratedTime as biPortalToBCIntegratedTime'
                )
                ->get()
                ->groupBy('id');
    
            return $this->sendResponse($workorders->map(function ($items) {
                return new WorkOrderResource($items);
            }), 'WorkOrders fetched successfully');
        
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    
    public function update(Request $request)
    {
		try {
			$itemId = $request->input('workorder_id');
		
			$servicepart = WorkOrder::where('id', $itemId)->firstOrFail();

			$servicepart->isModified = $request->has('isModified') ? (bool) $request->input('isModified') : $servicepart->isModified;
			$servicepart->isDeleted = $request->has('isDeleted') ? (bool) $request->input('isDeleted') : $servicepart->isDeleted;
			$servicepart->IsBCToPortalIntegrated = $request->has('isBCToPortalIntegrated') ? (bool) $request->input('isBCToPortalIntegrated') : $servicepart->IsBCToPortalIntegrated;
			$servicepart->BCToPortalIntegratedTime = $request->has('isBCToPortalIntegratedTime') ? $request->input('isBCToPortalIntegratedTime') : $servicepart->BCToPortalIntegratedTime;
			$servicepart->IsPortalToBCIntegrated = $request->has('isPortalToBCIntegrated') ? (bool) $request->input('isPortalToBCIntegrated') : $servicepart->IsPortalToBCIntegrated;
			$servicepart->PortalToBCIntegratedTime = $request->has('isPortalToBCIntegratedTime') ? $request->input('isPortalToBCIntegratedTime') : $servicepart->PortalToBCIntegratedTime;

			$servicepart->save();
		
			$updatedWorkOrder = WorkOrder::with('inv')
				->whereHas('inv')
				->leftJoin('bookings', 'bookings.workorderid', '=', 'work_orders.id')
				->leftJoin('booking_quotations', 'booking_quotations.booking_id', '=', 'bookings.id')
				->leftJoin('booking_items', 'booking_items.quotation_id', '=', 'booking_quotations.id')
				->where('work_orders.id', $itemId)
				->select(
					'work_orders.*', 
					'booking_quotations.id as quotation_id',
					'booking_quotations.status as quotation_status',
					'booking_items.product_id',
					'booking_items.product_name',
					'booking_items.qty',
					'booking_items.gstprice',
					'booking_items.linetotal',
					'booking_items.unit_price',
					'booking_items.warrenty',
					'booking_items.location',
					'booking_items.taxpercentage',
					'booking_items.taxamount',
					'booking_items.totalamount',
					'booking_items.uom',
					'booking_items.isModified as biisModified',
					'booking_items.isDeleted as biisDeleted',
					'booking_items.IsBCToPortalIntegrated as biIsBCToPortalIntegrated',
					'booking_items.BCToPortalIntegratedTime as biBCToPortalIntegratedTime',
					'booking_items.IsPortalToBCIntegrated as biIsPortalToBCIntegrated',
					'booking_items.PortalToBCIntegratedTime as biPortalToBCIntegratedTime'
				)
				->get();

			return $this->sendResponse(new WorkOrderResource($updatedWorkOrder), 'WorkOrder updated successfully');

		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return $this->sendError('Work Order not found!', $e->getMessage(), 404);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
    }
}

    
    
    

   
    
   
    


    