<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UOMResource;
use App\Http\Requests\Api\UOMRequest;
use App\Models\UOM;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UOMController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $uom = UOM::all();
            
            return $this->sendResponse(UOMResource::collection($uom), 'UOm fetched successfully');
           
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
   

    public function store(UOMRequest $request)
    {
        try {
          
            $data = $request->validated();
            $servicepart = UOM::create($data);
            return $this->sendResponse(new UOMResource($servicepart), 'UOM created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    

    public function update(UOMRequest $request)
    {
        try {
           
            $itemId = $request->input('uom_id');
    
           
            $servicepart = UOM::where('id', $itemId)->firstOrFail();
    
          
            $data = $request->validated();
    
          
          
            $servicepart->update($data);
    
         
            return $this->sendResponse(new UOMResource($servicepart), 'UOM updated successfully');
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
    