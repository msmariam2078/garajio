<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServicePartAdjustmentResources;
use App\Http\Requests\Api\ServicePartAdjustmentRequest;
use App\Models\ServicePartadjustMent;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicePartAdjustmentController extends BaseApiController
{
    public function index(Request $request)
    
    {
        try {
            $spa = ServicePartadjustMent::with(['warehouse', 'servicePart'])->get();
            return $this->sendResponse(ServicePartAdjustmentResources::collection($spa), 'Service Part Adjustment fetched  successfully');
           
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
   

    public function store(ServicePartAdjustmentRequest $request)
    {
        try {
            $data = $request->validated();
						
            $data['available'] = $request['available'] ?? 0;
            $data['unavailable'] = $request['unavailable'] ?? 0;
            $data['commited'] = $request['commited'] ?? 0;
            $data['onhand'] = $request['onhand'] ?? 0;
           
            $spa = ServicePartadjustMent::create($data);

            return $this->sendResponse(new ServicePartAdjustmentResources($spa), 'Service part adjustment created successfully');
        } catch (\Exception $e) {
           
            \Log::error($e->getMessage());
    
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    
    

    public function update(ServicePartAdjustmentRequest $request)
    {
        try {
           
            $itemId = $request->input('servicepartadjustment_id');
    
           
            $servicepart = ServicePartadjustMent::where('id', $itemId)->firstOrFail();
    
          
            $data = $request->validated();
    
            $data['unavailable'] = $data['unavailable'] ?? 0;
            $data['commited'] = $data['commited'] ?? 0;
            $data['onhand'] = $data['onhand'] ?? 0;
            $data['isModified'] = true;
          
            $servicepart->update($data);
    
         
            return $this->sendResponse(new ServicePartAdjustmentResources($servicepart), 'Service Part Adjustment updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('UOM not found!', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
           
            $servicepart = UOM::findOrFail($id);
           
            $servicepart->delete();
            return $this->sendResponse([], 'UOM deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    

}
    