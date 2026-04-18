<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Http\Requests\Api\WareHouseRequest;
use App\Models\Booking;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends BaseApiController
{
    public function index(Request $request)
    {
    try {
        $bookings = Booking::join('service_groups', 'service_groups.id', '=', \DB::raw('JSON_UNQUOTE(JSON_EXTRACT(bookings.service_group, "$[0]"))'))
            ->leftJoin('booking_quotations', 'booking_quotations.booking_id', '=', 'bookings.id')
            ->leftJoin('booking_items', 'booking_items.quotation_id', '=', 'booking_quotations.id')
            ->select(
                'bookings.*',
                'service_groups.id as service_group_id',
                'service_groups.name as service_group_name',
                'booking_quotations.id as quotation_id',
                'booking_quotations.status as quotation_status',
                'booking_items.product_id',
                'booking_items.product_name',
                'booking_items.qty',
                'booking_items.gstprice',
                'booking_items.linetotal',
                'booking_items.warrenty',
                
                'booking_items.isModified as biisModified', 
                'booking_items.isDeleted as biisDeleted',
                'booking_items.IsBCToPortalIntegrated as biIsBCToPortalIntegrated',
                'booking_items.BCToPortalIntegratedTime as biBCToPortalIntegratedTime',
                'booking_items.IsPortalToBCIntegrated as biIsPortalToBCIntegrated',
                'booking_items.PortalToBCIntegratedTime as biPortalToBCIntegratedTime'
            )
            ->get();
        return $this->sendResponse(BookingResource::collection($bookings), 'Booking data fetched successfully');
    } catch (\Exception $e) {
        return $this->sendError('Server error!', $e->getMessage(), 500);
    }
    }

    
    
    





}
    
