<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemCategoryResource;
use App\Http\Requests\Api\ItemCategoryRequest;
use App\Models\Category;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemCategoryController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $categories = Category::all();

            return $this->sendResponse(ItemCategoryResource::collection($categories), 'Categories Data fetched successfully');
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }


    public function store(ItemCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            
            $servicepart = Category::create([
                'description'              => $request->description,
                'isModified'               => $request->isModified ?? false,
                'IsBCToPortalIntegrated'   => $request->IsBCToPortalIntegrated ?? false,
                'BCToPortalIntegratedTime' => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
                'IsPortalToBCIntegrated'   => $request->IsPortalToBCIntegrated ?? false,
                'PortalToBCIntegratedTime' => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
            ]);

            return $this->sendResponse(new ItemCategoryResource($servicepart), 'Category created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }


    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id'              => 'required|exists:categories,id',
                'description'              => 'required|string|max:255',
                'isModified'               => 'nullable|boolean',
                'isDeleted'                => 'nullable|boolean',
                'IsBCToPortalIntegrated'   => 'nullable|boolean',
                'BCToPortalIntegratedTime' => 'nullable|date',
                'IsPortalToBCIntegrated'   => 'nullable|boolean',
                'PortalToBCIntegratedTime' => 'nullable|date'
            ]);
            
            $servicepart = Category::where('id', $request->category_id)->firstOrFail();
            
            $servicepart->update(
                collect($request->only([
                    'description',
                    'isModified',
                    'isDeleted',
                    'IsBCToPortalIntegrated',
                    'BCToPortalIntegratedTime',
                    'IsPortalToBCIntegrated',
                    'PortalToBCIntegratedTime'
                ]))->filter(fn($value) => !is_null($value))->toArray()
            );

            return $this->sendResponse(new ItemCategoryResource($servicepart), 'Category updated successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Category not found!', $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }  

    public function destroy($id)
    {
        try {
            $item = Category::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categories id not found.',
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categories deleted successfully.',
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