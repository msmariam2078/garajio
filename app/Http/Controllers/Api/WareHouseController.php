<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\WareHouseResource;
use App\Http\Requests\Api\WareHouseRequest;
use App\Models\WarHouse;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WareHouseController extends BaseApiController
{
   

    public function store(WareHouseRequest $request)
    {
        try {
          
            $data = $request->validated();
            $servicepart = WarHouse::create($data);
            return $this->sendResponse(new WareHouseResource($servicepart), 'Ware House created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    

    public function update(WareHouseRequest $request)
    {
        try {
           
            $itemId = $request->input('warehouse_id');
    
           
            $servicepart = WarHouse::where('id', $itemId)->firstOrFail();
    
          
            $data = $request->validated();
    
          
          
            $servicepart->update($data);
    
         
            return $this->sendResponse(new WareHouseResource($servicepart), 'Warehouse Data updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Ware House not found!', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    
    public function destroy($id)
    {
        try {
           
            $servicepart = WarHouse::findOrFail($id);
           
            $servicepart->delete();
            return $this->sendResponse([], 'Service part deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    

}
    