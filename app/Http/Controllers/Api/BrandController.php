<?php
namespace App\Http\Controllers\Api;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Requests\Api\BrandRequest;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\BrandRequestPost;

class BrandController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $brand = Brand::all();
            
            return $this->sendResponse(BrandResource::collection($brand), 'Brand data fetched successfully');
           
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    public function store(BrandRequestPost $request)
    {
        $data = $request->validated();
        try { 
            $brand = Brand::create([
                'description'              => $request->description,
                'isModified'               => $request->isModified ?? false,
                'isDeleted'                => $request->isDeleted ?? false,
                'IsBCToPortalIntegrated'   => $request->IsBCToPortalIntegrated ?? false,
                'BCToPortalIntegratedTime' => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
                'IsPortalToBCIntegrated'   => $request->IsPortalToBCIntegrated ?? false,
                'PortalToBCIntegratedTime' => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
            ]);
            
            return $this->sendResponse(new BrandResource($brand), 'Brand created successfully');
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    public function update(BrandRequest $request)
    {
    try {
        $data = $request->validate([
            'brand_id'                => 'required|exists:brands,id',
            'description'              => 'required|string|max:255',
            'isModified'               => 'nullable|boolean',
            'isDeleted'                => 'nullable|boolean',
            'IsBCToPortalIntegrated'   => 'nullable|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated'   => 'nullable|boolean',
            'PortalToBCIntegratedTime' => 'nullable|date'
        ]);
        
        $brand = Brand::findOrFail($data['brand_id']);

        $brand->update([
            'description'              => $data['description'],
            'isModified'               => $data['isModified'] ?? true, 
            'isDeleted'                => $data['isDeleted'] ?? $brand->isDeleted,
            'IsBCToPortalIntegrated'   => $data['IsBCToPortalIntegrated'] ?? $brand->IsBCToPortalIntegrated,
            'BCToPortalIntegratedTime' => $data['BCToPortalIntegratedTime'] ?? $brand->BCToPortalIntegratedTime,
            'IsPortalToBCIntegrated'   => $data['IsPortalToBCIntegrated'] ?? $brand->IsPortalToBCIntegrated,
            'PortalToBCIntegratedTime' => $data['PortalToBCIntegratedTime'] ?? $brand->PortalToBCIntegratedTime,
        ]);

        return $this->sendResponse(new BrandResource($brand), 'Brand updated successfully');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->sendError('Brand not found!', $e->getMessage(), 404);
    } catch (\Exception $e) {
        return $this->sendError('Server error!', $e->getMessage(), 500);
    }
    }
    
    public function destroy($id)
    {
        try {
            $item = Brand::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand id not found.',
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
    