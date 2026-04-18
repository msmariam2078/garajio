<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Custom;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Support;
use App\Models\Vehicle;
use App\Models\WORequest;
use App\Models\WorkOrder;
use App\Models\Estimation;
use App\Models\BookingItem;
use App\Models\ServicePart;
use App\Models\ClientDetail;
use App\Models\Subscription;
use App\Models\WarrentyItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\WOServicePart;
use App\Models\Warrenty_extend;
use App\Models\BookingQuotation;
use App\Models\PackageTransaction;
use App\Models\TechnicianLocation;
use Illuminate\Support\Facades\DB;
use App\Models\ServicePartadjustMent;
use App\Models\Warranty_registration;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	public function index()
	{
		if (\Auth::check()) {
			$user = \Auth::user();
			if ($user->type == 'technician') {
				$today = now()->toDateString(); // Get today's date in 'Y-m-d' format
	            $id=(string)$user->id;
				//dd($id);
				$result['totalClient'] = User::where('parent_id', parentId())->where('type', 'client')->count();
				$result['totalWORequest'] = WORequest::where('parent_id', parentId())->count();
				$result['totalWorkorder'] = WorkOrder::where('parent_id', parentId())->count();
				$result['totalInvoice'] = Invoice::where('parent_id', parentId())->count();
	            $result['todaypayments'] =Payment::whereHas('workorder', function ($query) use ($id) {
                                         $query->whereJsonContains('technician',  $id);})
										->whereDate('created_at',\Carbon\Carbon::today())->sum('paid_amount');
				$result['incomeByMonth'] = $this->incomeByMonth();
				$result['settings'] = settings();

				$technicianExist = false;
				$workOrders = WorkOrder::where('allocation_status', 1)
					->whereJsonContains('technician', strval($user->id))
					->whereDate('allocation_date', $today) // Filter today's work orders
					->get();

				$technicianExist = $workOrders->isNotEmpty();

				$acceptedworkOrders = WorkOrder::where('allocation_status', 2)
					->whereJsonContains('technician', strval($user->id))
					->whereDate('created_date', $today)
					->get();

				$pendingworkorder = WorkOrder::where('allocation_status', 1)
					->whereJsonContains('technician', strval($user->id))
					->whereDate('created_date', $today)
					->get();

				$pendingWork = WorkOrder::whereJsonContains('technician', strval($user->id))
					// ->whereDate('created_date', $today)
					->where('status', 'Confirmed')
					->get();

				$status = TechnicianLocation::where('user_id', $user->id)->first();

				return view('dashboard.profile', compact('result', 'technicianExist', 'workOrders', 'acceptedworkOrders', 'pendingworkorder', 'pendingWork', 'status'));
			}

			// Work Order status
			$year = Carbon::now()->year;
			$statuses = ['Confirmed', 'Pending', 'Invoiced', 'Completed', 'Booking', 'canceled'];

			// Fetch data from DB
			$data = WorkOrder::selectRaw('MONTH(created_date) as month, status, COUNT(*) as count')
				->whereYear('created_date', $year)
				->groupBy('month', 'status')
				->orderBy('month')
				->get();

			// Initialize arrays dynamically
			$chartData = [];
			$statusSums = [];

			foreach ($statuses as $status) {
				$chartData[$status] = array_fill(0, 12, 0);
				$statusSums[$status] = 0;
			}

			// Fill data
			foreach (range(1, 12) as $month) {
				foreach ($statuses as $status) {
					$count = $data->where('month', $month)->where('status', $status)->sum('count');

					// Set the monthly count
					$chartData[$status][$month - 1] = $count;

					// Sum total for the status
					$statusSums[$status] += $count;
				}
			}



			$result['recentClient'] = User::where('parent_id', parentId())->where('type', 'client')->latest()->take(6)->get();
			$result['totalClient'] = User::where('parent_id', parentId())->count();
			$result['newClient'] = User::where('parent_id', parentId())->whereDate('created_at', \Carbon\Carbon::today())->count();


			$result['totalWORequest'] = WORequest::where('parent_id', parentId())->count();
			$result['totalWorkOrder'] = WorkOrder::whereMonth('created_date', now()->month)->whereYear('created_date', now()->year)->count();


			$result['totalProduct'] = ServicePart::where('parent_id', parentId())->count();
			$result['totalInvoice'] = Invoice::where('parent_id', parentId())->count();

			$result['totalBooking'] = Booking::whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->count();


			$result['totalRevenue'] = BookingQuotation::leftJoin('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')->where('status', 'confirmed')->sum('totalamount');

			$result['incomeByMonth'] = $this->incomeByMonth();
			$result['settings'] = settings();

			$result['workorders'] = WorkOrder::select(['status', 'created_at'])->get();
			$result['bookings'] = Booking::select(['status'])->get();
			$result['totalWarranty'] = Warranty_registration::whereMonth('warranty_start_date', now()->month)->whereYear('warranty_start_date', now()->year)->count();

			$buyProductLists = ServicePartAdjustment::where('unavailable', '>', 0)->whereDate('updated_at', Carbon::today())->select('service_part_id', DB::raw('sum(unavailable) as total'))
				->groupBy('service_part_id')
				->orderBy('total', 'DESC')
				->take(5)
				->get();
			$result['mostSellingProduct'] = ServicePart::whereIn('id', $buyProductLists->pluck('service_part_id'))
				->get();

			//dd($result['mostSellingProduct']);

			$result['topCities'] = ClientDetail::where('service_city', '!=', '')->select('service_city', DB::raw('count(service_city) as total'))
				->groupBy('service_city')
				->orderBy('total', 'DESC')
				->take(7)
				->get();


			$technicianExist = false;
			$workOrders = [];
			if ($user->type == 'technician') {
				$workOrders = WorkOrder::where('allocation_status', 1)
					->whereJsonContains('technician', strval($user->id))
					->get();
				$technicianExist = $workOrders->isNotEmpty();
			}

			// Technician Dashboard
			$workorderGroup = WorkOrder::select('status', DB::raw('COUNT(id) as total'))
				->whereDate('created_date', date('Y-m-d'))
				->groupBy('status')
				->get();

			// $result['topCities2'] = DB::table('payments as p')
			// 	->join('work_orders as wo', 'wo.id', '=', 'p.workorder')
			// 	->join('bookings as b', DB::raw("REPLACE(REPLACE(wo.booking, '[', ''), ']', '')"), '=', 'b.id')
			// 	->select('b.city', DB::raw('SUM(p.paid_amount) as total_paid'))
			// 	->whereYear('p.payment_date', now()->year)
			// 	->whereMonth('p.payment_date', now()->month)
			// 	->groupBy('b.city')
			// 	->get();

			return view('dashboard.index', compact('result', 'technicianExist', 'workOrders', 'chartData', 'statusSums', 'workorderGroup'));
		} else {
			if (!file_exists(setup())) {
				header('location:install');
				die;
			} else {
				$landingPage = getSettingsValByName('landing_page');
				if ($landingPage == 'on') {
					$subscriptions = Subscription::get();
					return view('layouts.landing', compact('subscriptions'));
				} else {
					return redirect()->route('login');
				}
			}
		}
	}


	public function organizationByMonth()
	{
		$start = strtotime(date('Y-01'));
		$end = strtotime(date('Y-12'));

		$currentdate = $start;

		$organization = [];
		while ($currentdate <= $end) {
			$organization['label'][] = date('M-Y', $currentdate);

			$month = date('m', $currentdate);
			$year = date('Y', $currentdate);
			$organization['data'][] = User::where('type', 'owner')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
			$currentdate = strtotime('+1 month', $currentdate);
		}


		return $organization;
	}

	public function paymentByMonth()
	{
		$start = strtotime(date('Y-01'));
		$end = strtotime(date('Y-12'));

		$currentdate = $start;

		$payment = [];
		while ($currentdate <= $end) {
			$payment['label'][] = date('M-Y', $currentdate);

			$month = date('m', $currentdate);
			$year = date('Y', $currentdate);
			$payment['data'][] = PackageTransaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
			$currentdate = strtotime('+1 month', $currentdate);
		}

		return $payment;
	}

	public function incomeByMonth()
	{
		$start = strtotime(date('Y-01'));
		$end = strtotime(date('Y-12'));

		$currentdate = $start;

		$payment = [];
		while ($currentdate <= $end) {
			$payment['label'][] = date('M-Y', $currentdate);
			$month = date('m', $currentdate);
			$year = date('Y', $currentdate);
			$payment['income'][] = Invoice::where('parent_id', parentId())->whereMonth('invoice_date', $month)->whereYear('invoice_date', $year)->sum('total');
			$currentdate = strtotime('+1 month', $currentdate);
		}

		return $payment;
	}


	public function search(Request $request)
	{
		$query = $request->input('query');
		$html = '<h5 class="text-center">results search for ' . $query . '</h5>';
		if (Auth::user()->type == 'technician') {
			$workorders = WorkOrder::where('id', $query)->where('technician', '["' . Auth::user()->id . '"]')->get();
			if ($workorders->isEmpty()) {
				$html .= '<div class="dropdown-item">No Data found.</div>';
			} else {

				$html .= '<a class="d-flex justify-content-between align-items-center px-4 " data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                          <h5> Work Orders </h5>
                          <p class="text-dark">show all (' . $workorders->count() . ')</p>
                        </a>
						 <div class="collapse show" id="collapseExample">';
				foreach ($workorders as $workorder) {
					$workorderLink = route('workorder.show', $workorder->id);
					$html .= '<div class="dropdown-item  mx-3 mb-3  py-0" ><a class="text-dark" href="' . $workorderLink . '"> <p class="mb-1 text-black-50 h5 font-weight-bold"><span class="font-weight-bold">#WO' . ($workorder->id ?? '') . '</p>
                         <p class="font-weight-bold mb-1 ">Client name: <span class="text-muted">' . ($workorder->client->first_name ?? '') . '</span></p>
                         <p class="font-weight-bold mb-1">Created date: <span class="text-muted">' . ($workorder->created_ar ?? '') . '</span></p></a> </div></div>';
				}
			}
		} else {
			$users = User::where('type', 'client')
				->whereHas('clients')
				->with('clients')
				->where(function ($q) use ($query) {
					$q->where('first_name', 'LIKE', "%{$query}%")
						->orWhere('last_name', 'LIKE', "%{$query}%")
						->orWhere('ccm', 'LIKE', "%{$query}%")
						->orWhere('phone_number', 'LIKE', "%{$query}%")
						->orWhere('email', 'LIKE', "%{$query}%")
						->orWhereRaw("CONCAT('+',ccm, phone_number) LIKE ?", ["%{$query}%"])
						->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$query}%"]);
				})
				->get();

			$vehicals = Vehicle::where('rego', 'LIKE', "%{$query}%")->get();

			$quotations = BookingQuotation::where('id', $query)->get();
			$bookings = Booking::where('id', $query)->get();
			$workorders = WorkOrder::where('id', $query)->get();



			if ($users->isEmpty() && $quotations->isEmpty() && $bookings->isEmpty() && $workorders->isEmpty() && $vehicals->isEmpty() && $vehicals->isEmpty()) {
				$html .= '<div class="dropdown-item">No Data found.</div>';
			} else {

				$html .= '<div class="px-3 border-bottom d-flex justify-content-between align-items-center">
						<h5 class="mb-0">Clients</h5>
						<a class="text-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
							Show All (' . $users->count() . ')
						</a>
					</div>
					<div class="collapse show" id="collapseExample">';

				foreach ($users as $user) {
					$userLink = route('client.fetchvehicle', \Illuminate\Support\Facades\Crypt::encrypt($user->id));
					$html .= '<a href="' . $userLink . '" class="dropdown-item px-3 border-bottom">
						<div class="fw-bold text-dark">' . ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') . '</div>
						<div class="text-muted">Email: <span class="text-dark">' . ($user->email ?? '') . '</span></div>
						<div class="text-muted">Phone: <span class="text-dark">+' . preg_replace('/[^0-9]/', '', $user->ccm) . ($user->phone_number ?? '') . '</span></div>
					</a>';
				}

				$html .= '</div>
            
                <a class="d-flex justify-content-between align-items-center  px-4" data-toggle="collapse" href="#collapseExample1" role="button" aria-expanded="false" aria-controls="collapseExample">
                          <h5> Vehicles </h5>
                          <p class="text-dark">show all (' . $vehicals->count() . ')</p>
                        </a>
						 <div class="collapse" id="collapseExample1">';
				foreach ($vehicals as $vehical) {
					$vehicleLink = route('vehicle.show', \Illuminate\Support\Facades\Crypt::encrypt($vehical->id));
					$html .= '<div class="dropdown-item  mx-3 mb-3  py-0" ><a class="text-dark" href="' . $vehicleLink . '"> <p class="mb-1 text-black-50 h5 font-weight-bold"><span class="font-weight-bold"> ' . ($vehical->name ?? '') . '</p>
                <p class="font-weight-bold mb-1 ">REG: <span class="text-muted">' . ($vehical->rego ?? '') . '</span></p>
                </a> </div>';
				}
				$html .= '</div>
            <div class="dropdown-divider my-3"></div>
            <a class="d-flex justify-content-between align-items-center  px-4" data-toggle="collapse" href="#booking" role="button" aria-expanded="false" aria-controls="collapseExample">
                      <h5> Open Bookings </h5>
                      <p class="text-dark">show all (' . $bookings->count() . ')</p>
                    </a>
                     <div class="collapse" id="booking">';
				foreach ($bookings as $booking) {
					$bookLink = route('booking.show', $booking->id);
					$html .= '<div class="dropdown-item  mx-3 mb-3  py-0" ><a class="text-dark" href="' . $bookLink . '"> <p class="mb-1 text-black-50 h5 font-weight-bold"><span class="font-weight-bold">#BOOK' . ($booking->id ?? '') . '</p>
                        <p class="font-weight-bold mb-1 ">Client name: <span class="text-muted">' . ($booking->user->first_name ?? '') . '</span></p>
                        <p class="font-weight-bold mb-1">Created date: <span class="text-muted">' . ($booking->created_date ?? '') . '</span></p></a> </div>';
				}
				$html .= '</div>
            
            <a class="d-flex justify-content-between align-items-center px-4" data-toggle="collapse" href="#workorder" role="button" aria-expanded="false" aria-controls="collapseExample">
                      <h5> Open Work Orders </h5>
                      <p class="text-dark">show all (' . $workorders->count() . ')</p>
                    </a>
                     <div class="collapse" id="workorder">';
				foreach ($workorders as $workorder) {
					$workorderLink = route('workorder.show', $workorder->id);
					$html .= '<div class="dropdown-item  mx-3 mb-3  py-0" ><a class="text-dark" href="' . $workorderLink . '"> <p class="mb-1 text-black-50 h5 font-weight-bold"><span class="font-weight-bold">#WO' . ($workorder->id ?? '') . '</p>
                         <p class="font-weight-bold mb-1 ">Client name: <span class="text-muted">' . ($workorder->client->first_name ?? '') . '</span></p>
                         <p class="font-weight-bold mb-1">Created date: <span class="text-muted">' . ($workorder->created_ar ?? '') . '</span></p></a> </div>';
				}
				$html .= '</div>
                     
                     <a class="d-flex justify-content-between align-items-center  px-4" data-toggle="collapse" href="#quotation" role="button" aria-expanded="false" aria-controls="collapseExample">
                               <h5> Open Quotations </h5>
                               <p class="text-dark">show all (' . $quotations->count() . ')</p>
                             </a>
                              <div class="collapse" id="quotation">';


				foreach ($quotations as $quotation) {
					//$userLink = route('estimat.show', \Illuminate\Support\Facades\Crypt::encrypt($user->id));

					$html .= '<div class="dropdown-item  mx-3 mb-3  py-0" ><a class="text-dark" href=""> <p class="mb-1 text-black-50 h5 font-weight-bold"><span class="font-weight-bold">#QOT' . ($quotation->id ?? '') . '</p>
                <p class="font-weight-bold mb-1 ">Title: <span class="text-muted">' . ($quotation->title ?? '') . '</span></p>
                <p class="font-weight-bold mb-1">Client name: <span class="text-muted">' . ($quotation->clients->first_name ?? '') . '</span></p></a></div>';
				}
			}
		}
		return response()->json(['html' => $html]);
	}

	public function search2(Request $request)
	{
		$query = $request->input('query');

		$clients = User::where('type', 'client')
			->where(function ($q) use ($query) {
				$q->where('first_name', 'LIKE', "%{$query}%")
					->orWhere('last_name', 'LIKE', "%{$query}%")
					->orWhere('email', 'LIKE', "%{$query}%")
					->orWhere('phone_number', 'LIKE', "%{$query}%")
					->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$query}%"]);
			})
			->get();

		$results = $clients->map(function ($client) {
			return [
				'id' => $client->id,
				'label' => $client->full_name . ' - ' . $client->email . ' - +' . preg_replace('/[^0-9]/', '', $client->ccm) . $client->phone_number

			];
		});

		return response()->json(['data' => $results]);
	}



	public function calendar()
	{

		return view('dashboard.calendar');
	}

	public function ajaxAlertdata()
	{
		$user = \Auth::user();
		$today = now()->toDateString();
		
		$technicianExist = false;
		$workOrders = WorkOrder::where('allocation_status', 1)
			->whereJsonContains('technician', strval($user->id))
			->whereDate('allocation_date', $today) // Filter today's work orders
			->get();

		$technicianExist = $workOrders->isNotEmpty();

		 return response()->json([
			'technicianExist' => $workOrders->isNotEmpty(),
			'workOrders' => $workOrders,
			'count' => $workOrders->count(),

		]);
	}

	public function ajaxAlertdataQuotation(){
		$user = \Auth::user();
		$today = now()->toDateString();
		
		$quotationExist = false;
		$workOrders = Workorder::where('status', 'Inspection Completed')
			->whereJsonContains('technician', strval($user->id))
			->whereDate('allocation_date', $today)
			->get();

		$results = $workOrders->map(function ($workOrder) {
			// Decode booking (since it's stored as JSON)
			$bookingIds = collect(json_decode($workOrder->booking, true))
				->filter()
				->values();

			// Get confirmed quotations for this work order's bookings
			$quotations = BookingQuotation::whereIn('booking_id', $bookingIds)
				->where('status', 'confirmed')
				->get();

			// Only include work orders that actually have quotations
			if ($quotations->isNotEmpty()) {
				return [
					'workorder_id' => $workOrder->id,
					'booking_ids' => $bookingIds,
				];
			}

			return null; // skip if no quotations found
		})
		->filter()
		->values(); 

		$quotationExist = $results->isNotEmpty();

		 return response()->json([
			'quotationExist' => $quotationExist,
			'acceptedQuotations' => $results,
			'count' => $results->count(),
		]);
	}

	
	public function ajaxAlertdataInspectionComplete()
	{
		$user = \Auth::user();
		$today = now()->toDateString();
		
		$inceptionExist = false;
		$inspectionComplete = WorkOrder::where('allocation_status', 2)
			->where('created_by',$user->id)
			->whereDate('allocation_date', $today) // Filter today's work orders
			->where('status','Inspection Completed')
			->get();

		$inceptionExist = $inspectionComplete->isNotEmpty();
		// dd($workOrders);
		 return response()->json([
			'inceptionExist' => $inspectionComplete->isNotEmpty(),
			'inspectionCompleteList' => $inspectionComplete,
			'count' => $inspectionComplete->count(),
		]);
	}
	public function ajaxAlertdataWorkComplete()
	{
		$user = \Auth::user();
		$today = now()->toDateString();
		
		$workExist = false;
		$workComplete = WorkOrder::where('allocation_status', 2)
			->where('created_by',$user->id)
			->whereDate('allocation_date', $today) // Filter today's work orders
			->where('status','Work Completed')
			->get();

		$workExist = $workComplete->isNotEmpty();
		// dd($workOrders);
		 return response()->json([
			'workExist' => $workComplete->isNotEmpty(),
			'workCompleteList' => $workComplete,
			'count' => $workComplete->count(),
		]);
	}
}
