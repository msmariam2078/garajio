<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TechnicianResource;
use App\Http\Requests\Api\TechnicianRequest;
use App\Models\User;
use App\Models\ClientDetail;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicianController extends BaseApiController
{
	public function index(Request $request)
	{
		try {
			$technicians = User::with('clients')->where('type', 'technician')->get();

			return $this->sendResponse(TechnicianResource::collection($technicians), 'Technicians fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function store(TechnicianRequest $request)
	{
		try {
			$validated = $request->validated();
			DB::beginTransaction();

			$user = User::create([
				'first_name' => $validated['first_name'],
				'last_name' => $validated['last_name'],
				'email' => $validated['email'],
				'phone_number' => $validated['phone_number'],
				'type' => 'technician',
				'employee_title' => $validated['employee_title'],
				'job_title' => $validated['job_title'],
				'country' => $validated['country'],
				'isModified' => $validated['isModified'],
				'is_deleted' => $validated['isDeleted'],
				'IsBCToPortalIntegrated' => $validated['IsBCToPortalIntegrated'],
				'BCToPortalIntegratedTime' => $validated['BCToPortalIntegratedTime'],
				'IsPortalToBCIntegrated' => $validated['IsPortalToBCIntegrated'],
				'PortalToBCIntegratedTime' => $validated['PortalToBCIntegratedTime'],
				'profile' => $validated['profile_picture'],
			]);

			ClientDetail::create([
				'user_id' => $user->id,
				'service_address' => $validated['addresses'],
				'service_city' => $validated['city'],
				'service_zip_code' => $validated['post_code'],
			]);

			DB::commit();

			return $this->sendResponse(new TechnicianResource($user), 'Technician created successfully');
		} catch (\Exception $e) {

			DB::rollBack();
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function update(Request $request)
	{
		try {
			$user = User::findOrFail($request->user_id);
			DB::beginTransaction();
			$user->update([
				'isModified' => $request->isModified,
				'isDeleted' => $request->isDeleted,
				'IsBCToPortalIntegrated' => $request->IsBCToPortalIntegrated,
				'BCToPortalIntegratedTime' => $request->BCToPortalIntegratedTime,
				'IsPortalToBCIntegrated' => $request->IsPortalToBCIntegrated,
				'PortalToBCIntegratedTime' => $request->PortalToBCIntegratedTime,
			]);
			DB::commit();
			return $this->sendResponse(new TechnicianResource($user), 'Technician updated successfully');
		} catch (\Exception $e) {

			DB::rollBack();
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function destroy($id)
	{
		try {
			DB::beginTransaction();
			$user = User::findOrFail($id);
			ClientDetail::where('user_id', $user->id)->delete();
			$user->delete();
			DB::commit();

			return $this->sendResponse([], 'Technician deleted successfully');
		} catch (\Exception $e) {
			DB::rollBack();
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}
}
