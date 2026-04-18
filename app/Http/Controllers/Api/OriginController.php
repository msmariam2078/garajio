<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OriginResource;
use App\Http\Requests\Api\OriginRequest;
use App\Models\Origin;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OriginController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $origin = Origin::all();

            return $this->sendResponse(OriginResource::collection($origin), 'Origin data fetched successfully');
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    public function store(OriginRequest $request)
    {
        $data = $request->validated();
        try {
            $brand = Origin::create([
                'description'              => $request->description,
                'isModified'               => $request->isModified ?? false,
                'isDeleted'                => $request->isDeleted ?? false,
                'IsBCToPortalIntegrated'   => $request->IsBCToPortalIntegrated ?? false,
                'BCToPortalIntegratedTime' => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
                'IsPortalToBCIntegrated'   => $request->IsPortalToBCIntegrated ?? false,
                'PortalToBCIntegratedTime' => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
            ]);

            return $this->sendResponse(new OriginResource($brand), 'Origin created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }


    public function update(Request $request)
    {
        $data = $request->validate([
            'origin_id'                => 'required|exists:origins,id',
            'description'              => 'required|string|max:255',
            'isModified'               => 'nullable|boolean',
            'isDeleted'                => 'nullable|boolean',
            'IsBCToPortalIntegrated'   => 'nullable|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated'   => 'nullable|boolean',
            'PortalToBCIntegratedTime' => 'nullable|date'
        ]);
    
        try {
            $origin = Origin::findOrFail($data['origin_id']);
    
            $origin->update([
                'description'              => $data['description'],
                'isModified'               => $data['isModified'] ?? true,
                'isDeleted'                => $data['isDeleted'] ?? $origin->isDeleted,
                'IsBCToPortalIntegrated'   => $data['IsBCToPortalIntegrated'] ?? $origin->IsBCToPortalIntegrated,
                'BCToPortalIntegratedTime' => $data['BCToPortalIntegratedTime'] ?? $origin->BCToPortalIntegratedTime,
                'IsPortalToBCIntegrated'   => $data['IsPortalToBCIntegrated'] ?? $origin->IsPortalToBCIntegrated,
                'PortalToBCIntegratedTime' => $data['PortalToBCIntegratedTime'] ?? $origin->PortalToBCIntegratedTime,
            ]);
    
            return $this->sendResponse(new OriginResource($origin), 'Origin updated successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
    
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $item = Origin::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Origin id not found.',
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Origin deleted successfully.',
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