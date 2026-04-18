<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleMasterResource;
use App\Http\Requests\Api\VehicleMasterRequest;
use App\Models\Vehicle;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleMasterController extends BaseApiController
{
	public function index(Request $request)
	{
		try {
			$vehicles = Vehicle::where(function ($q) {
				$q->where(function ($sub) {
					$sub->where('IsBCToPortalIntegrated', false)
						->where('IsPortalToBCIntegrated', false);
				})

				->orWhere(function ($sub) {
					$sub->where(function ($inner) {
						$inner->where('IsBCToPortalIntegrated', true)
							->orWhere('IsPortalToBCIntegrated', true);
					})
					->where('isModified', true);
				});
			})
			->get();

			return $this->sendResponse(VehicleMasterResource::collection($vehicles), 'Vehicle Data fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}


	public function store(VehicleMasterRequest $request)
	{
		try {
			$vehicle = Vehicle::create($request->validated());

			return $this->sendResponse(new VehicleMasterResource($vehicle), 'Vehicle created successfully');
		} catch (\Exception $e) {

			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}
	public function update(VehicleMasterRequest $request)
	{
		try {
			$vehicleId = $request->input('vehicle_id');
			$vehicle = Vehicle::where('id', $vehicleId)->firstOrFail();
			$vehicle->update($request->validated());
			return $this->sendResponse(new VehicleMasterResource($vehicle), 'Vehicle updated successfully');
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return $this->sendError('Vehicle not found!', $e->getMessage(), 404);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}
	public function destroy($id)
	{
		try {
			$item = Vehicle::find($id);

			if (!$item) {
				return response()->json([
					'success' => false,
					'message' => 'Vehicle id not found.',
				], 404);
			}

			$item->delete();

			return response()->json([
				'success' => true,
				'message' => 'Vehicle deleted successfully.',
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
