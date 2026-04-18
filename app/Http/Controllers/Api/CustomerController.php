<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\Api\CustomerRequest;
use App\Models\User;
use App\Models\ClientDetail;
use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends BaseApiController
{
	public function index(Request $request)
	{
		try {
			$clients = User::where('type', 'client')
				->where(function ($q) {
					$q->where(function ($q2) {
						$q2->where(function ($sub) {
							$sub->where('IsBCToPortalIntegrated', false)
								->where('IsPortalToBCIntegrated', false);
						});
					})
					
					->orWhere(function ($q2) {
						$q2->where(function ($sub) {
							$sub->where('IsBCToPortalIntegrated', true)
								->orWhere('IsPortalToBCIntegrated', true);
						})->where('isModified', true);
					});
				})
				->select([
					'id',
					'email',
					'first_name',
					'last_name',
					'phone_number',
					'type',
					'isModified',
					'country',
					'ccm',
					'gst',
					'client_type',
					'IsBCToPortalIntegrated',
					'BCToPortalIntegratedTime',
					'IsPortalToBCIntegrated',
					'PortalToBCIntegratedTime',
					'isDeleted',
					'is_active',
					'customer_template'
				])
				->with([
					'customertemplate:id,code',
					'clientDetails:user_id,service_address,service_city,service_zip_code'
				])
				->get();

			return $this->sendResponse(CustomerResource::collection($clients), 'Customer data fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function store(CustomerRequest $request)
	{
		DB::beginTransaction();

		try {

			$validated = $request->validated();

			$userData = [
				'first_name' => $validated['first_name'],
				'last_name' => $validated['last_name'],
				'email' => $validated['email'],
				'phone_number' => $validated['phoneno'],
				'country' => $validated['country'],
				'ccm' => $validated['country_code'],
				'gst' => $validated['gstorvatno'] ?? null,
				'client_type' => $validated['client_type'],
				'type' => 'client',
				'is_active' => $validated['status'],
				'customer_template' =>  $validated['customer_template'],
			];

			if (isset($validated['isModified'])) {
				$userData['isModified'] = $validated['isModified'];
			}
			if (isset($validated['IsBCToPortalIntegrated'])) {
				$userData['IsBCToPortalIntegrated'] = $validated['IsBCToPortalIntegrated'];
			}
			if (isset($validated['BCToPortalIntegratedTime'])) {
				$userData['BCToPortalIntegratedTime'] = $validated['BCToPortalIntegratedTime'];
			}
			if (isset($validated['IsPortalToBCIntegrated'])) {
				$userData['IsPortalToBCIntegrated'] = $validated['IsPortalToBCIntegrated'];
			}
			if (isset($validated['PortalToBCIntegratedTime'])) {
				$userData['PortalToBCIntegratedTime'] = $validated['PortalToBCIntegratedTime'];
			}



			$user = User::create($userData);

			ClientDetail::create([
				'user_id' => $user->id,
				'service_address' => $validated['addresses'],
				'service_city' => $validated['city'],
				'service_zip_code' => $validated['post_code'],
			]);


			DB::commit();

			// Return the created user data using CustomerResource
			return response()->json([
				'status' => true,
				'message' => 'User and ClientDetail created successfully',
				'data' => new CustomerResource($user),
			], 201);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => false,
				'message' => 'Failed to create user',
				'error' => $e->getMessage(),
			], 500);
		}
	}


	public function update(Request $request, $id)
	{
		DB::beginTransaction();

		try {


			$user = User::findOrFail($id);

			$userData = [
				'first_name'   => $validated['name'],
				'email'        => $validated['email'],
				'phone_number' => $validated['phoneno'],
				'country'      => $validated['country'],
				'gst'          => $validated['gstorvatno'],
				'isModified'   => true,
			];


			if (array_key_exists('IsBCToPortalIntegrated', $validated)) {
				$userData['IsBCToPortalIntegrated'] = $validated['IsBCToPortalIntegrated'];
			}
			if (array_key_exists('BCToPortalIntegratedTime', $validated)) {
				$userData['BCToPortalIntegratedTime'] = $validated['BCToPortalIntegratedTime'];
			}
			if (array_key_exists('IsPortalToBCIntegrated', $validated)) {
				$userData['IsPortalToBCIntegrated'] = $validated['IsPortalToBCIntegrated'];
			}
			if (array_key_exists('PortalToBCIntegratedTime', $validated)) {
				$userData['PortalToBCIntegratedTime'] = $validated['PortalToBCIntegratedTime'];
			}

			$user->update($userData);

			$clientDetail = ClientDetail::where('client_id', $user->id)->first();
			if ($clientDetail) {
				$clientDetail->update([
					'addresses' => $validated['address'],
				]);
			}

			DB::commit();

			return response()->json([
				'status'  => true,
				'message' => 'User and ClientDetail updated successfully',
				'data'    => $user,
			], 200);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status'  => false,
				'message' => 'Failed to update user',
				'error'   => $e->getMessage(),
			], 500);
		}
	}
	public function updatecustomer(Request $request)
	{
		try {
			$validated = $request->validate([
				'customer_id' => 'required|exists:users,id',
				'first_name' => 'required|string',
				'last_name' => 'nullable|string',
				'email' => 'nullable|email',
				'phoneno' => 'nullable|string',
				'country' => 'nullable|string',
				'country_code' => 'nullable|string',
				'address' => 'nullable|string',
				'client_type' => 'required|in:corporate,individual',
				'gstorvatno' => 'exclude_if:client_type,individual|nullable|string|max:50',
				'IsBCToPortalIntegrated' => 'nullable|boolean',
				'BCToPortalIntegratedTime' => 'nullable|date',
				'IsPortalToBCIntegrated' => 'nullable|boolean',
				'PortalToBCIntegratedTime' => 'nullable|date',
				'status' => 'nullable|boolean',
			]);

			DB::beginTransaction();

			$user = User::findOrFail($validated['customer_id']);

			$userData = [
				'first_name' => $validated['first_name'],
				'last_name' => $validated['last_name'],
				'email' => $validated['email'],
				'phone_number' => $validated['phoneno'],
				'country' => $validated['country'],
				'ccm' => $validated['country_code'],
				'gst' => $validated['gstorvatno'] ?? null,
				'isModified' => $validated['isModified'] ?? null,
				'is_active' => $validated['status']  ?? $user->is_active
			];

			foreach (['IsBCToPortalIntegrated', 'BCToPortalIntegratedTime', 'IsPortalToBCIntegrated', 'PortalToBCIntegratedTime'] as $key) {
				if (array_key_exists($key, $validated)) {
					$userData[$key] = $validated[$key];
				}
			}

			$user->update($userData);

			$clientDetail = ClientDetail::where('client_id', $user->id)->first();
			if ($clientDetail) {
				$clientDetail->update(['addresses' => $validated['address'] ?? $clientDetail->addresses]);
			}

			DB::commit();

			$user->load('clients');

			return response()->json([
				'status' => true,
				'message' => 'User and ClientDetail updated successfully',
				'data' => new CustomerResource($user),
			], 200);
		} catch (ValidationException $e) {
			return response()->json([
				'status' => false,
				'message' => 'Validation Error',
				'errors' => $e->errors()
			], 422);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => false,
				'message' => 'Failed to update user',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	public function destroy($id)
	{

		$user = User::find($id);

		if (!$user) {

			return response()->json([
				'message' => 'User not found.'
			], 404);
		}


		$user->delete();


		return response()->json([
			'message' => 'User successfully deleted.'
		], 200);
	}
}
