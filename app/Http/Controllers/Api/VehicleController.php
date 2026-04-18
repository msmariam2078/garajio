<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\vehicle_make;
use App\Models\vehicle_tran;
use App\Models\vehicle_body_type;
use App\Models\vehicle_colour_new;
use App\Models\vehicle_seat;
use App\Models\vehicle_driving_type;
use App\Models\vehicle_model;
use App\Models\EngineSpecs;
use App\Models\RegionalSpecs;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\VehicleMakeRequest;
use App\Http\Requests\Api\EngineSpecsRequest;
use App\Http\Requests\Api\VehicleTransmissionRequest;
use App\Http\Requests\Api\VehicleBodyTypeRequest;
use App\Http\Requests\Api\VehicleColorRequest;
use App\Http\Requests\Api\RegionalSpecsRequest;
use App\Http\Requests\Api\VehicleSeatRequest;
use App\Http\Requests\Api\VehicleDrivingTypeRequest;
use App\Http\Requests\Api\VehicleModelRequest;
use App\Http\Resources\VehicleMakeResource;
use App\Http\Resources\VehicleModelResource;
use App\Http\Resources\EngineSpecsResource;
use App\Http\Resources\RegionalSpecsResource;

class VehicleController extends BaseApiController
{

	public function vehiclemake(Request $request)
	{
		try {
			$vm = vehicle_make::all();

			return $this->sendResponse(VehicleMakeResource::collection($vm), 'Vehicle Make fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postvehiclemake(VehicleMakeRequest $request)
	{
		$validated = $request->validated();

		try {
			$vehicleMake = vehicle_make::create([
				'make_name'                  => $request->make_name,
				'isModified'                 => $request->isModified ?? false,
				'isDeleted'                 => $request->isDeleted ?? false,
				'IsBCToPortalIntegrated'     => $request->IsBCToPortalIntegrated ?? false,
				'BCToPortalIntegratedTime'   => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
				'IsPortalToBCIntegrated'     => $request->IsPortalToBCIntegrated ?? false,
				'PortalToBCIntegratedTime'   => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
			]);

			return $this->sendResponse(new VehicleMakeResource($vehicleMake), 'Vehicle make created successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function updateVehicleMake(Request $request)
	{
		try {
			$validated = $request->validate([
				'vehicle_make_id'          => 'required|exists:vehicle_makes,id',
				'make_name'                => 'required|string|max:255',
				'isModified'               => 'nullable|boolean',
				'isDeleted'                => 'nullable|boolean',
				'IsBCToPortalIntegrated'   => 'nullable|boolean',
				'BCToPortalIntegratedTime' => 'nullable|date',
				'IsPortalToBCIntegrated'   => 'nullable|boolean',
				'PortalToBCIntegratedTime' => 'nullable|date',
			]);

			$vehicleMake = vehicle_make::findOrFail($request->vehicle_make_id);

			$vehicleMake->update([
				'make_name'                    => $request->make_name,
				'isModified'                   => $request->isModified ?? true,
				'IsBCToPortalIntegrated'       => $request->IsBCToPortalIntegrated ?? null,
				'BCToPortalIntegratedTime'     => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
				'IsPortalToBCIntegrated'       => $request->IsPortalToBCIntegrated ?? null,
				'PortalToBCIntegratedTime'     => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
			]);


			return response()->json([
				'success' => true,
				'message' => 'Vehicle make updated successfully.',
				'data'    => new VehicleMakeResource($vehicleMake),
			], 200);
		} catch (\Illuminate\Validation\ValidationException $e) {

			return response()->json([
				'success' => false,
				'message' => 'Validation error!',
				'errors'  => $e->errors(),
			], 422);
		} catch (\Exception $e) {

			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function deleteVehicleMake($id)
	{
		try {
			$item = vehicle_make::find($id);

			if (!$item) {
				return response()->json([
					'success' => false,
					'message' => 'Vehicle make id not found.',
				], 404);
			}

			$item->delete();

			return response()->json([
				'success' => true,
				'message' => 'Vehicle make deleted successfully.',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	public function vehicletransmission(Request $request)
	{
		try {
			$vm = vehicle_tran::all()->pluck('transmission', 'id');

			return response()->json([
				'success' => true,
				'data' => $vm,
			], 200);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postvehicletransmission(VehicleTransmissionRequest $request)
	{
		$validated = $request->validated();

		try {
			vehicle_tran::insert(['transmission' => $request->transmission]);

			return response()->json([
				'success' => true,
				'message' => 'Vehicle Transmission created successfully.',
			], 201);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function bodytype(Request $request)
	{
		try {
			$vm = vehicle_body_type::all()->pluck('body_type', 'id');

			return response()->json([
				'success' => true,
				'data' => $vm,
			], 200);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postbodytype(VehicleBodyTypeRequest $request)
	{
		$validated = $request->validated();

		try {
			vehicle_body_type::insert(['body_type' => $request->body_type]);

			return response()->json([
				'success' => true,
				'message' => 'Vehicle Body Type created successfully.',
			], 201);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}
	public function color(Request $request)
	{
		try {
			$vm = vehicle_colour_new::all()->pluck('colour', 'id');

			return response()->json([
				'success' => true,
				'data' => $vm,
			], 200);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postcolor(VehicleColorRequest $request)
	{
		$validated = $request->validated();

		try {
			vehicle_colour_new::insert(['colour' => $request->color]);

			return response()->json([
				'success' => true,
				'message' => 'Vehicle Color created successfully.',
			], 201);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function seatingcapacity(Request $request)
	{
		try {
			$vm = vehicle_seat::all()->pluck('seating_capacity', 'id');

			return response()->json([
				'success' => true,
				'data' => $vm,
			], 200);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postseattingcapacity(VehicleSeatRequest $request)
	{
		$validated = $request->validated();

		try {
			vehicle_seat::insert(['seating_capacity' => $request->seating_capacity]);

			return response()->json([
				'success' => true,
				'message' => 'Vehicle Seating Capacity created successfully.',
			], 201);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function drivingtype(Request $request)
	{
		try {
			$vm = vehicle_driving_type::all()->pluck('driving_type', 'id');

			return response()->json([
				'success' => true,
				'data' => $vm,
			], 200);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postdrivingtype(VehicleDrivingTypeRequest $request)
	{
		$validated = $request->validated();

		try {
			vehicle_driving_type::insert(['driving_type' => $request->driving_type]);

			return response()->json([
				'success' => true,
				'message' => 'Vehicle Driving Type created successfully.',
			], 201);
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	// Vehicle Model
	public function vehiclemodel(Request $request)
	{
		try {
			$vm = vehicle_model::whereHas('make')->with('make')->get();
			return $this->sendResponse(VehicleModelResource::collection($vm), 'Vehicle Model fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postvehiclemodel(VehicleModelRequest $request)
	{
		try {
			$validated = $request->validated();

			$vehicleModel = new vehicle_model();
			$vehicleModel->make_id = $request->make_id;
			$vehicleModel->model_name = $request->model_name;

			$vehicleModel->isModified = $request->isModified ?? false;
			$vehicleModel->isDeleted = $request->isDeleted ?? false;
			$vehicleModel->IsBCToPortalIntegrated = $request->IsBCToPortalIntegrated ?? false;
			$vehicleModel->BCToPortalIntegratedTime = $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00';
			$vehicleModel->IsPortalToBCIntegrated = $request->IsPortalToBCIntegrated ?? false;
			$vehicleModel->PortalToBCIntegratedTime = $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00';

			$vehicleModel->save();

			return response()->json([
				'success' => true,
				'data' => new VehicleModelResource($vehicleModel),
				'message' => 'Vehicle model created successfully!'
			], 201);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Server error!',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function updateVehicleModel(Request $request)
	{
		try {
			$validated = $request->validate([
				'vehicle_model_id'         => 'required|exists:vehicle_models,id',
				'make_id'                  => 'required|exists:vehicle_makes,id',
				'model_name'               => 'required|string|max:255|unique:vehicle_models,model_name,' . $request->vehicle_model_id,
				'isModified'               => 'nullable|boolean',
				'isDeleted'                => 'nullable|boolean',
				'IsBCToPortalIntegrated'   => 'nullable|boolean',
				'BCToPortalIntegratedTime' => 'nullable|date',
				'IsPortalToBCIntegrated'   => 'nullable|boolean',
				'PortalToBCIntegratedTime' => 'nullable|date',
			]);

			$vehicleModel = vehicle_model::findOrFail($request->vehicle_model_id);
			$vehicleModel->update([
				'make_id'                  => $request->make_id,
				'model_name'               => $request->model_name,
				'isModified'               => $request->isModified ?? true,
				'isDeleted'                => $request->isDeleted ?? false,
				'IsBCToPortalIntegrated'   => $request->IsBCToPortalIntegrated ?? null,
				'BCToPortalIntegratedTime' => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
				'IsPortalToBCIntegrated'   => $request->IsPortalToBCIntegrated ?? null,
				'PortalToBCIntegratedTime' => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
			]);

			return response()->json([
				'success' => true,
				'data' => new VehicleModelResource($vehicleModel),
				'message' => 'Vehicle model updated successfully!',
			], 200);
		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Validation error!',
				'errors'  => $e->errors(),
			], 422);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Server error!',
				'error'   => $e->getMessage(),
			], 500);
		}
	}

	public function deletevehiclemodel($id)
	{
		try {
			$item = vehicle_model::find($id);
			if (!$item) {
				return response()->json([
					'success' => false,
					'message' => 'Vehicle model id not found.',
				], 404);
			}

			$item->delete();

			return response()->json([
				'success' => true,
				'message' => 'Vehicle model deleted successfully!',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Server error!',
				'error'   => $e->getMessage(),
			], 500);
		}
	}

	// Engine Specs
	public function enginespecs(Request $request)
	{
		try {
			$es = EngineSpecs::with(['make', 'model'])->get();

			return $this->sendResponse(EngineSpecsResource::collection($es), 'Engine Specs fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postenginespecs(EngineSpecsRequest $request)
	{
		$validated = $request->validated();

		try {
			$engineSpec = EngineSpecs::create([
				'make_id'                    => $request->make_id,
				'model_id'                   => $request->model_id,
				'enginespecs'                => $request->enginespecs,
				'isModified'                 => $request->isModified ?? false,
				'isDeleted'                 => $request->isDeleted ?? false,
				'IsBCToPortalIntegrated'     => $request->IsBCToPortalIntegrated ?? false,
				'BCToPortalIntegratedTime'   => $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00',
				'IsPortalToBCIntegrated'     => $request->IsPortalToBCIntegrated ?? false,
				'PortalToBCIntegratedTime'   => $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00',
			]);

			return $this->sendResponse(new EngineSpecsResource($engineSpec), 'Engine Spec created successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function updateEngineSpecs(EngineSpecsRequest $request)
	{
		try {
			$engineSpec = EngineSpecs::findOrFail($request->engine_specs_id);

			$engineSpec->update(
				collect($request->only([
					'make_id',
					'model_id',
					'enginespecs',
					'isModified',
					'isDeleted',
					'IsBCToPortalIntegrated',
					'BCToPortalIntegratedTime',
					'IsPortalToBCIntegrated',
					'PortalToBCIntegratedTime'
				]))->filter(fn($value) => !is_null($value))->toArray()
			);

			return $this->sendResponse(new EngineSpecsResource($engineSpec), 'Engine Spec updated successfully');
		} catch (\Exception $e) {
			dd($e->getMessage());
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function deleteenginespecs($id)
	{
		try {
			$item = EngineSpecs::find($id);

			if (!$item) {
				return response()->json([
					'success' => false,
					'message' => 'Engine Specs id not found.',
				], 404);
			}

			$item->delete();

			return response()->json([
				'success' => true,
				'message' => 'Engine Specs deleted successfully.',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	// Regional Specs
	public function regionalspecs(Request $request)
	{
		try {
			$vm = RegionalSpecs::get();
			return $this->sendResponse(RegionalSpecsResource::collection($vm), 'Regional Specs fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function postregionalspecs(RegionalSpecsRequest $request)
	{
		try {
			$validated = $request->validated();

			$rs = new RegionalSpecs();
			$rs->title = $request->title;
			$rs->isModified = $request->isModified ?? false;
			$rs->isDeleted = $request->isDeleted ?? false;
			$rs->IsBCToPortalIntegrated = $request->IsBCToPortalIntegrated ?? false;
			$rs->BCToPortalIntegratedTime = $request->BCToPortalIntegratedTime ?? '1900-01-01 00:00:00';
			$rs->IsPortalToBCIntegrated = $request->IsPortalToBCIntegrated ?? false;
			$rs->PortalToBCIntegratedTime = $request->PortalToBCIntegratedTime ?? '1900-01-01 00:00:00';
			$rs->save();
			return response()->json([
				'success' => true,
				'data' => new RegionalSpecsResource($rs),
				'message' => 'Regional Specs created successfully!'
			], 201);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Server error!',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function updateregionalspecs(RegionalSpecsRequest $request)
	{
		try {
			$validated = $request->validate([
				'regional_specs_id'        => 'required|exists:regional_specs,id',
				'title'                    => 'required|string|max:255',
				'isModified'               => 'nullable|boolean',
				'isDeleted'                => 'nullable|boolean',
				'IsBCToPortalIntegrated'   => 'nullable|boolean',
				'BCToPortalIntegratedTime' => 'nullable|date',
				'IsPortalToBCIntegrated'   => 'nullable|boolean',
				'PortalToBCIntegratedTime' => 'nullable|date'
			]);

			$rs = RegionalSpecs::where('id', $request->regional_specs_id)->firstOrFail();

			$rs->update(
				collect($request->only([
					'title',
					'isModified',
					'isDeleted',
					'IsBCToPortalIntegrated',
					'BCToPortalIntegratedTime',
					'IsPortalToBCIntegrated',
					'PortalToBCIntegratedTime'
				]))->filter(fn($value) => !is_null($value))->toArray()
			);

			return response()->json([
				'success' => true,
				'data'    => new RegionalSpecsResource($rs),
				'message' => 'Regional Specs updated successfully!',
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Server error!',
				'error'   => $e->getMessage(),
			], 500);
		}
	}

	public function deleteregionalspecs($id)
	{
		try {
			$item = RegionalSpecs::find($id);

			if (!$item) {
				return response()->json([
					'success' => false,
					'message' => 'Regional Specs id not found.',
				], 404);
			}

			$item->delete();

			return response()->json([
				'success' => true,
				'message' => 'Regional Specs deleted successfully.',
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
