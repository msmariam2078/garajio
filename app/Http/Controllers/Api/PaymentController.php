<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\UOM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends BaseApiController
{
	public function index(Request $request)
	{
		try {
			$uom = Payment::where('payment_method', '!=', 'credit')
				->whereNotNull('paid_amount')
				->where('paid_amount', '>', 0)
				->get();

			return $this->sendResponse(PaymentResource::collection($uom), 'Payment fetched successfully');
		} catch (\Exception $e) {
			return $this->sendError('Server error!', $e->getMessage(), 500);
		}
	}

	public function update(Request $request)
	{
		try {

			$user = Payment::findOrFail($request->payment_id);
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
			return $this->sendResponse(new PaymentResource($user), 'Payment updated successfully');
		} catch (\Exception $e) {

			DB::rollBack();
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
