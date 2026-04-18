<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemMasterResource;
use App\Http\Requests\Api\ItemMasterRequest;
use App\Models\ServicePart;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemMasterController extends BaseApiController
{

    public function store(ItemMasterRequest $request)
    {
        try {
            $data = $request->validated();
            
            $data['quantity'] = 0;

            $data['reference_type'] = $request->filled('reference_type') ? $request->reference_type : '';
            $data['reference_number'] = $request->filled('reference_number') ? $request->reference_number : '';

            $data['isModified'] = $request->filled('isModified') ? $request->isModified : false;
            $data['isDeleted'] = $request->filled('isDeleted') ? $request->isDeleted : false;
            $data['IsBCToPortalIntegrated'] = $request->filled('IsBCToPortalIntegrated') ? $request->IsBCToPortalIntegrated : false;
            $data['IsPortalToBCIntegrated'] = $request->filled('IsPortalToBCIntegrated') ? $request->IsPortalToBCIntegrated : false;

            $imagePath = null;
            if ($request->has('image')) {
                $base64Image = $request->input('image');
                $imagePath = $this->saveBase64Image($base64Image);

                if ($imagePath) {
                    $data['image'] = $imagePath;
                }
            }

            $servicepart = ServicePart::create($data);

            return $this->sendResponse(new ItemMasterResource($servicepart), 'Service part created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error($e->getMessage());
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    private function saveBase64Image($base64Image)
    {

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $extension = $matches[1];
            $base64Data = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Data);

            if ($imageData !== false) {
                $filename = time() . '.' . $extension;
                $path = 'upload/img/' . $filename;


                if (!file_exists(public_path('upload/img'))) {
                    mkdir(public_path('upload/img'), 0755, true);
                }

                file_put_contents(public_path($path), $imageData);

                return $path;
            }
        }

        return null;
    }

    public function update(ItemMasterRequest $request)
    {
        try {
            $itemId = $request->input('item_id');
            $servicepart = ServicePart::where('id', $itemId)->firstOrFail();

            $data = $request->validated();

            if ($request->has('image')) {
                $base64Image = $request->input('image');

                if ($servicepart->image && file_exists(public_path($servicepart->image))) {
                    unlink(public_path($servicepart->image));
                }

                $imagePath = $this->saveBase64Image($base64Image);
                if ($imagePath) {
                    $data['image'] = $imagePath;
                }
            }
			$data['quantity'] = 0;
			$data['reference_type'] = $request->filled('reference_type') ? $request->reference_type : '';
            $data['reference_number'] = $request->filled('reference_number') ? $request->reference_number : '';
			
            $servicepart->update($data);

            return $this->sendResponse(new ItemMasterResource($servicepart), 'Service part updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Service part not found!', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $servicepart = ServicePart::find($id);
            
            if (!$servicepart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service id not found.',
                ], 404);
            }
            
            if ($servicepart->image && file_exists(public_path($servicepart->image))) {
                unlink(public_path($servicepart->image));
            }

            $servicepart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Service part deleted successfully.',
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