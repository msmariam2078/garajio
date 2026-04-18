<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use App\Models\SkillGroup;
use App\Models\Subscription;
use App\Models\TechnicianLocation;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\WarHouse;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\ServiceMaster;
use App\Models\ShiftMaster;
use App\Models\ServiceGroups;
use App\Models\TechnicianWorkHours;
use App\Models\TechnicianBookingAppointment;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Services\FirebaseService;

use DB;
use Pusher\Pusher;

class TechnicianController extends Controller
{
	protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

	public function index()
	{
		if (Auth::user()->can('show technician')) {
			$technicians = User::with('clients')
				//->where('parent_id', parentId())
				->whereIn('type', ['technician','supervisor'])->where(function ($query) {
					$query->where('isDeleted', false)
						->orWhereNull('isDeleted');
				})->get();
			return view('technician.index', compact('technicians'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function create()
	{
		if (!Auth::user()->can('create technician')) {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}

		$country_code = User::$country_code;
		$country = User::$country;

		$country_code_s = settings()['country_code'];
		$country_s = settings()['country'];
		$warehouse = WarHouse::select('id', 'name')->get();
		$service = ServiceGroups::all();
		$skillGroups = SkillGroup::all();

		$shift = ShiftMaster::all();

		return view('technician.create', compact('country_code', 'country', 'warehouse', 'country_code_s', 'country_s', 'service', 'skillGroups', 'shift'));
	}

	public function updateStatus(Request $request, $id)
	{
		$user = User::find($id);
		$user->is_active = $request->has('is_active') ? 1 : 0;
		$user->save();

		if ($user->is_active) {
			return redirect()->route('technician.index')->with('success', __('Technician successfully activated.'));
		} else {
			return redirect()->route('technician.index')->with('success', __('Technician successfully deactivated.'));
		}
	}

	public function store(Request $request)
	{
		//dd($request->all());
		if (!Auth::user()->can('create technician')) {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}

		try {
			$request->validate([
				'firstname' => 'required|string|max:255',
				'last_name' => 'string|max:255',
				'email' => 'required|email|unique:users,email',
				'phone_number' => 'required|numeric',
				'm_cc' => 'required|string',
				'mobile' => 'numeric',
				'p_cc' => 'string',
				// 'sGroup_name' => 'required|array',
				'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			]);
			$m_cc = $request->m_cc;
			$phone_number = $request->phone_number;
			$p_cc = $request->p_cc;
			$mobile = $request->mobile;
			$ids = parentId();



			$user_count = User::where('email', $request->email)->orWhere('phone_number', $request->phone_number)->count();
			if (!empty($user_count)) {
				return redirect()->back()->with('error', __('Email or Mobile already exist.' . $request->email . " " . $request->mobile . " " . $user_count));
			}
			$user = new User();
			$user->first_name = $request->firstname;
			$user->last_name = $request->last_name;
			$user->email = $request->email;
			$user->phone_number = $request->phone_number;
			$user->ccm = $request->m_cc;
			$user->fax = $request->fax;
			$user->po_box = $request->po_box;
			$user->mobile = $request->mobile;
			$user->ccp = $request->p_cc;
			$user->shift = json_encode($request->shift);
			$user->country = $request->country;
			$user->password = \Hash::make(123456);
			$user->type = $request->type;
			$user->profile = 'avatar.png';
			$user->lang = 'english';
			$user->parent_id = parentId();

			if ($request->hasFile('profile_picture')) {
				$file = $request->file('profile_picture');
				$extension = $file->getClientOriginalExtension();
				$filename = time() . '.' . $extension;
				$file->move('upload/img/', $filename);
				$user->profile = 'upload/img/' . $filename;
			}
			$user->skills = json_encode($request->sGroup_name);
			$user->save();

			// $user->skillgroups()->attach($request->sGroup_name);
			if ($request->workorder_id) {

				$woids = $request->workorder_id;
				$workOrders = WorkOrder::where('id', $woids)->get();
				foreach ($workOrders as $workOrder) {
					$existingTechnicians = json_decode($workOrder->technician, true) ?? [];
					$existingTechnicians[] = $user->id;
					$uniqueTechnicians = array_unique($existingTechnicians);
					$workOrder->technician = json_encode($uniqueTechnicians);
					$workOrder->save();
				}


				return redirect()->route('workorder.edit', ['workorder' => $request->workorder_id]);
			}

			// Handling warehouse assignments
			if ($request->has('warehouse_name')) {
				$user->warehouses()->sync($request->warehouse_name);
			}

			// Handling ClientDetail creation
			if (!empty($user)) {
				$client = new ClientDetail();
				$client->user_id = $user->id;
				$client->service_address = $request->service_address;
				$client->service_city = $request->service_city;
				$client->service_state = $request->service_state;
				$client->service_country = $request->service_country;
				$client->service_zip_code = $request->service_zip_code;
				$client->parent_id = parentId();
				$client->save();
			}


			$userRole = Role::findByName($request->type);
			$user->syncRoles($userRole);

			return redirect()->back()->with('success', __('Technician successfully created.'));
		} catch (\Exception $e) {
			// dd($e->getMessage());
			return redirect()->back()->with('error', __('An error occurred: ') . $e->getMessage());
		}
	}

	public function allocatetechnician(Request $request)
	{

        //dd($request->all());
		$workOrderId = $request->workOrder_id;
		$technicianId = $request->technician_id;
		$fromDate = $request->from_date;
		$fromTime = $request->from_time;
		$toDate = $request->to_date;
		$toTime = $request->to_time;


		$workOrder = WorkOrder::find($workOrderId);
		if ($workOrder) {

			$existingTechnicians = json_decode($workOrder->technician, true) ?? [];
			$existingTechnicians[] = $technicianId;
			$uniqueTechnicians = array_unique($existingTechnicians);
			$workOrder->technician = json_encode($uniqueTechnicians);
			$workOrder->allocation_status = 1;
			$workOrder->allocation_date = now();
			$workOrder->status = "Pending";
			$workOrder->save();


			DB::table('technician_booking_appointments')->insert([
				'workorder_id' => $workOrderId,
				'technician_id' => $technicianId,
				'from_date' => $fromDate,
				'from_time' => $fromTime,
				'to_date' => $toDate,
				'to_time' => $toTime,
				'status' => 0,
				'created_at' => now(),
				'updated_at' => now()
			]);

			//firebase code start 
			$this->firebase->sendNotification([
				'title' => "Alert For workorder ID: ".$workOrderId,
				'type' => 'technican_alert',
				'authId' => $technicianId,
			]);
			//firebase code end 

			// $booking=json_decode($workOrder->booking,true);
			// return Redirect::to('booking/'.$booking[0].'/edit');
	
		return response()->json(['message' => 'Technician allocated  successfully.'], 200);
			//return redirect()->back()->with('success', __('Technician allocated  successfully.'));
		}

		return response()->json(['message' => 'Work order not found.'], 404);
	}

	public function show($ids)
	{
		if (!Auth::user()->can('show technician')) {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
		$id = Crypt::decrypt($ids);
		$technician = User::find($id);
  
		$workOrders = WorkOrder::where('technician', $technician->id)->get();
		return view('technician.show', compact('technician', 'workOrders'));
	}

	public function edit($id)
	{
		if (!Auth::user()->can('edit technician')) {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
		$technician = User::with('clients')->find($id);
		$country_code = User::$country_code;
		$country = User::$country;
		$warehouse = WarHouse::select('id', 'name')->get();
		$skills = SkillGroup::all();
		$shifts = ShiftMaster::all();
		$service = ServiceGroups::all();



		// $serviceOptions = $service->pluck('name', 'id')->toArray();

		//  $selectedServices = $technician->services->pluck('id')->toArray();
		return view('technician.edit', compact('technician', 'country_code', 'country', 'warehouse', 'skills', 'shifts'));
	}

	public function update(Request $request, $id)
	{

		if (Auth::user()->can('edit technician')) {
			// $validator = Validator::make(
			//     $request->all(),
			//     [
			//         'name' => 'required',
			//         'email' => 'required|email|unique:users,email,' . $id,
			//         'phone_number' => 'required',
			//         'skill' => 'required|max:255',
			//         'home_location' => 'required|max:255',
			//     ]
			// );
			// if ($validator->fails()) {
			//     $messages = $validator->getMessageBag();
			//     return redirect()->back()->with('error', $messages->first());
			// }

			$user = User::find($id);
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->email = $request->email;
			$user->type=$request->type;
			$user->phone_number = $request->phone_number;
			$user->ccm = $request->m_cc;
			$user->fax = $request->fax;
			$user->po_box = $request->po_box;
			$user->mobile = $request->mobile;
			$user->skills = $request->skill_group;
			$user->shift = $request->shift;
			$user->ccp = $request->p_cc;
			$user->country = $request->country;
			if ($request->hasFile('profile_picture')) {
				$file = $request->file('profile_picture');
				$extension = $file->getClientOriginalExtension();
				$filename = time() . '.' . $extension;
				$file->move('upload/img/', $filename);
				$user->profile = 'upload/img/' . $filename;
			}
			$user->isModified = 1;

			$user->save();
			$userRole = Role::findByName($request->type);
			$user->syncRoles($userRole);
			if ($request->has('warehouse_name')) {
				$user->warehouses()->sync($request->warehouse_name);
			}
			if (!empty($user)) {
				$client = ClientDetail::where('user_id', $user->id)->first();
				if ($client) {
					$client->service_address = $request->service_address;
					$client->service_city = $request->service_city;
					$client->service_state = $request->service_state;
					$client->service_country = $request->service_country;
					$client->service_zip_code = $request->service_zip_code;
					$client->save();
				}
			}

			// Skill::updateOrCreate(['user_id' => $id], ['skill' => $request->skill]);

			// TechnicianLocation::updateOrCreate(
			//     ['user_id' => $id], [
			//         'user_id' => $id,
			//         'home_location' => $request->home_location,
			//     ]
			// );

			return redirect()->back()->with('success', __('Technician successfully updated.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function destroy($id)
	{
		$user = User::find($id);
		$user->isDeleted = 1;
		$user->save();

		return redirect()->back()->with('success', __('Technician successfully deleted.'));
	}

	public function changeWorkOrderStatus(Request $request, $id)
	{
		if (Auth::user()->type != 'technician') {
			return redirect()->back()->with('error', __('Permission denied.'));
		}

		$validator = Validator::make(
			$request->all(),
			[
				'type' => 'required|in:approved,rejected',
			]
		);
		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return redirect()->back()->with('error', $messages->first());
		}

		$id = Crypt::decrypt($id);
		$workorder = WorkOrder::where('parent_id', parentId())->find($id);
		if (!$workorder) {
			return redirect()->back()->with('error', __('Work Order Not found!.'));
		}
		$workorder->status = $request->type;
		$workorder->save();

		return redirect()->back()->with('success', __('Status Change Successfully.'));
	}

	public function checkIn(Request $request)
	{
		// Validate request inputs
		$request->validate([
			'lat' => 'required|numeric',
			'long' => 'required|numeric',
			'working_address' => 'required|string|max:255',
		]);

		// Get the authenticated user
		$user = Auth::user();
		$currentDateTime = now();
		$currentDay = $currentDateTime->format('l'); // E.g., "Monday"




		$shiftMaster = $user->shiftmaster;

		// Ensure shift days are properly formatted
		$workingDays = [];
		if ($shiftMaster && !empty($shiftMaster->day)) {
			$decodedDays = json_decode($shiftMaster->day, true);
			if (is_array($decodedDays)) {
				$workingDays = array_map('trim', array_map('ucfirst', $decodedDays)); // Standardizing format
			}
		}



		// // Debugging: Log to check shift details
		// \Log::info("User Shift Days: " . json_encode($workingDays));
		// \Log::info("Today's Day: " . $currentDay);

		// // If shift days are empty or today is not in the assigned shift, deny check-in
		// if (empty($workingDays) || !in_array($currentDay, $workingDays)) {
		//     return response()->json([
		//         'message' => 'You cannot check in today as it is not in your assigned shift.',
		//         'debug_shift_days' => $workingDays, // Helps debugging
		//         'debug_today' => $currentDay
		//     ], 403);
		// }

		try {
			DB::beginTransaction(); // Start DB transaction

			// Update or create technician location
			$technicianLocation = TechnicianLocation::where('user_id', $user->id)->first();

			if ($technicianLocation) {
				$technicianLocation->update([
					'lat' => $request->lat,
					'long' => $request->long,
					'working_address' => $request->working_address,
				]);
			} else {
				TechnicianLocation::create([
					'user_id' => $user->id,
					'lat' => $request->lat,
					'long' => $request->long,
					'working_address' => $request->working_address,
				]);
			}
			// Update or create technician work hours for today
			TechnicianWorkHours::updateOrCreate(
				['technician_id' => $user->id, 'start_date' => $currentDateTime->toDateString()],
				[
					'workingday' => $currentDay,
					'status' => 1 // Mark as checked in
				]
			);

			// Insert check-in log
			DB::table('technician_logs')->insert([
				'user_id' => $user->id,
				'log' => 'Checked in at ' . $currentDateTime->toDateTimeString(),
				'created_dateandtime' => $currentDateTime->toDateTimeString(),
				'sessionid' => session()->getId(),
				'checkin' => 1,
				'checkout' => false,
				'requested_time' => null,
				'accepted_time' => null,
				'en_rout_time' => null,
				'start_time' => null,
				'finish_time' => null,
				'date' => $currentDateTime->toDateString(),
			]);

			DB::commit(); // Commit the transaction

			return response()->json([
				'message' => 'Checked in successfully',
				'shift_master_link' => $shiftMaster->link ?? null,
			]);
		} catch (\Exception $e) {
			DB::rollBack(); // Rollback the transaction in case of an error

			return response()->json([
				'message' => 'An error occurred while checking in.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function checkOut()
	{
		$user = Auth::user();
		$currentDateTime = now();

		try {
			DB::beginTransaction();

			TechnicianLocation::updateOrCreate(
				['user_id' => $user->id],
				[
					'lat' => null,
					'long' => null,
					'working_address' => null,
				]
			);

			$workHours = TechnicianWorkHours::where('technician_id', $user->id)
				->where('start_date', $currentDateTime->toDateString())
				->first();

			if ($workHours == null) {
				DB::commit(); // Commit transaction
				return response()->json(['message' => 'Checked out successfully']);
			} else {
				$workHours->status = 0; // Mark as 'completed'
				$workHours->save();

				// Insert check-out log
				DB::table('technician_logs')->insert([
					'user_id' => $user->id,
					'log' => 'Checked out at ' . $currentDateTime->toDateTimeString(),
					'created_dateandtime' => $currentDateTime->toDateTimeString(),
					'sessionid' => session()->getId(),
					'checkin' => false,
					'checkout' => 1,
					'requested_time' => null,
					'accepted_time' => null,
					'en_rout_time' => null,
					'start_time' => $workHours->start_time,
					'finish_time' => $workHours->end_time,
					'date' => $currentDateTime->toDateString(),
				]);
			}

			DB::commit(); // Commit transaction

			return response()->json(['message' => 'Checked out successfully']);
		} catch (\Exception $e) {
			DB::rollBack(); // Rollback on error

			return response()->json([
				'message' => 'An error occurred while checking out.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function editprofile(Request $request, $id)
	{
		$userid = Crypt::decrypt($id);
		$technician = User::find($userid);
		$workHours = TechnicianWorkHours::where('technician_id', $userid)->get();

		return view('technician.editprofile', compact('technician', 'workHours'));
	}

	public function updateWorkHours(Request $request, $technicianId)
	{
		$validatedData = $request->validate([
			'days' => 'required|string',
			'from_time' => 'required',
			'to_time' => 'required',
		]);


		TechnicianWorkHours::updateOrCreate(
			['technician_id' => $technicianId, 'workingday' => $validatedData['days']],
			[
				'start_time' => $validatedData['from_time'],
				'end_time' => $validatedData['to_time'],
				'status' => 1,
			]
		);

		return redirect()->back()->with('success', 'Work hours updated successfully!');
	}

	public function getTechnicianAvailability(Request $request)
	{
		$date = Carbon::parse($request->date);
		$dayOfWeek = strtolower($date->format('l'));

		$workOrderId = $request->workOrderId;
		$workOrder = WorkOrder::findOrFail($workOrderId);

		$workOrderServiceGroup = is_array($workOrder->service_group)
			? $workOrder->service_group
			: json_decode($workOrder->service_group, true);

		$serviceAddress = $workOrder->service_location;
		$serviceCoordinates = $this->getCoordinatesFromAddress($serviceAddress);

		if (!$serviceCoordinates) {
			return response()->json(['error' => 'Unable to retrieve service address coordinates.'], 400);
		}


		$availableTechnicians = User::where('type', 'technician')
			->whereHas('technicianlocation', function ($query) {
				$query->whereNotNull('user_id');
			})

			->where(function ($query) use ($workOrderServiceGroup) {
				foreach ($workOrderServiceGroup as $service) {
					$query->orWhereRaw('FIND_IN_SET(?, services)', [$service]);
				}
			})

			->with([
				'workHours' => function ($query) use ($dayOfWeek) {
					$query->where('workingday', $dayOfWeek)
						->where('status', 1);
				},
				'technicianlocation'
			])
			->get()

			->map(function ($technician) use ($serviceCoordinates) {
				$workingAddress = $technician->technicianlocation->working_address;
				$technicianCoordinates = $this->getCoordinatesFromAddress($workingAddress);

				if ($technicianCoordinates) {
					$distance = $this->calculateDistance(
						$serviceCoordinates['lat'],
						$serviceCoordinates['lng'],
						$technicianCoordinates['lat'],
						$technicianCoordinates['lng']
					);
					$technician->distance = $distance;
				} else {
					$technician->distance = null;
				}

				return $technician;
			})
			->filter(function ($technician) {
				return $technician->distance !== null && $technician->workHours->isNotEmpty();
			})
			->sortBy('distance');

		$html = view('workorder.partials.technician_table_body', compact('availableTechnicians', 'workOrderId'))->render();

		return response()->json(['html' => $html]);
	}

	private function getCoordinatesFromAddress($address)
	{
		$apiKey = "AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc"; // Make sure to set this in your .env file
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

		$response = file_get_contents($url);
		$response = json_decode($response, true);

		if (isset($response['results'][0])) {
			$location = $response['results'][0]['geometry']['location'];
			return [
				'lat' => $location['lat'],
				'lng' => $location['lng']
			];
		}

		return null;
	}

	private function calculateDistance($lat1, $lng1, $lat2, $lng2)
	{
		$earthRadius = 6371; // Radius of the Earth in kilometers

		$latDelta = deg2rad($lat2 - $lat1);
		$lngDelta = deg2rad($lng2 - $lng1);

		$a = sin($latDelta / 2) * sin($latDelta / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($lngDelta / 2) * sin($lngDelta / 2);

		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $earthRadius * $c;
	}

	public function acceptallocatetechnician(Request $request)
	{
		$workOrderId = $request->input('workOrderId');
		$accept = $request->input('accept');

		$workOrder = WorkOrder::find($workOrderId);

		if ($workOrder) {
			$user = auth()->user();
			$currentDateTime = now();

			if ($accept == 1) {
				$workOrder->allocation_status = 2;
				$workOrder->status = "Confirmed";
				$workOrder->save();

				// Log the technician acceptance with nullable fields
				DB::table('technician_logs')->insert([
					'user_id' => $user->id,
					'log' => 'Technician accepted for work order ' . $workOrderId . ' at ' . $currentDateTime->toDateTimeString(),
					'created_dateandtime' => $currentDateTime->toDateTimeString(),
					'sessionid' => session()->getId(),
					'checkin' => null,  // Set checkin value
					'checkout' => null,
					'workorderid' => $workOrderId,
					'requested_time' => '',
					'accepted_time' => $currentDateTime->toTimeString(),
					'en_rout_time' => '',
					'start_time' => '',
					'finish_time' => '',
					'date' => $currentDateTime->toDateString(),
					'created_at' => $currentDateTime,
					'updated_at' => $currentDateTime,
				]);

				return redirect()->back()->with('success', __('You have accepted successfully.'));
			} elseif ($accept == 0) {
				$technicians = json_decode($workOrder->technician, true);
				$userId = strval(auth()->user()->id);

				if (($key = array_search($userId, $technicians)) !== false) {
					unset($technicians[$key]);
				}

				$workOrder->technician = json_encode(array_values($technicians));
				$workOrder->status = "Rejected";
				$workOrder->technician = null;
				$workOrder->allocation_status = 0;
				$workOrder->save();

				DB::table('technician_logs')->insert([
					'user_id' => $user->id,
					'log' => 'Technician rejected from work order ' . $workOrderId . ' at ' . $currentDateTime->toDateTimeString(),
					'created_dateandtime' => $currentDateTime->toDateTimeString(),
					'sessionid' => session()->getId(),
					'checkin' => null,
					'checkout' => null,
					'workorderid' => $workOrderId,
					'requested_time' => '',
					'accepted_time' => $currentDateTime->toTimeString(),
					'en_rout_time' => '',
					'start_time' => '',
					'finish_time' => '',
					'date' => null,
					'created_at' => $currentDateTime,
					'updated_at' => $currentDateTime,
				]);

				return redirect()->route('home')->with('success', __('Technician removed successfully.'));
			}
		}

		return response()->json(['message' => 'Failed to update Work Order.'], 400);
	}

	public function bookTechnicians($technicianId, $workOrderId)
	{
		return view('workorder.bookingmodal', compact('technicianId', 'workOrderId'));
	}

	public function invoice(Request $request)
	{
		$userId = auth()->user()->id;

		$startDate = $request->has('start_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
			: now()->subDays(7)->startOfDay();

		$endDate = $request->has('end_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
			: now()->endOfDay();

		$workOrders = WorkOrder::whereJsonContains('technician', (string)$userId)->pluck('id');


		$invoices = Invoice::whereIn('wo_id', $workOrders)
			->whereBetween('invoice_date', [$startDate, $endDate])
			->get();

		return view('techdashboard.invoice', compact('invoices', 'startDate', 'endDate'));
	}

	public function invoiceSearch(Request $request)
	{
		$userId = auth()->user()->id;

		// Get start date or default to 7 days ago
		$startDate = $request->input('from_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('from_date'))->startOfDay()
			: now()->subDays(7)->startOfDay();

		// Get end date or default to now
		$endDate = $request->input('to_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('to_date'))->endOfDay()
			: now()->endOfDay();

		// Get workorders assigned to this user (assuming technician stored as JSON array)
		$workOrders = WorkOrder::whereJsonContains('technician', (string)$userId)->pluck('id');

		// Get invoices within the date range and workorders
		$invoices = Invoice::whereIn('wo_id', $workOrders)
			->whereBetween('invoice_date', [$startDate, $endDate])
			->get();

		return view('techdashboard.invoice', compact('invoices', 'startDate', 'endDate'));
	}

	public function payment(Request $request)
	{
		$userId = auth()->user()->id;

		$startDate = $request->has('start_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
			: now()->subDays(7)->startOfDay();

		$endDate = $request->has('end_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
			: now()->endOfDay();


		$workOrders = WorkOrder::whereJsonContains('technician', (string)$userId)
			->pluck('payment')
			->filter()
			->flatMap(function ($item) {
				return json_decode($item, true);
			})
			->unique();


		$payments = Payment::whereIn('id', $workOrders)
			->whereBetween('payment_date', [$startDate, $endDate])
			->get();

		return view('techdashboard.payment', compact('payments', 'startDate', 'endDate'));
	}

	public function paymentSearch(Request $request)
	{
		$userId = auth()->user()->id;

		// Use 'from_date' and 'to_date' (not start_date/end_date, to match form)
		$startDate = $request->has('from_date') && $request->input('from_date') != ''
			? Carbon::createFromFormat('Y-m-d', $request->input('from_date'))->startOfDay()
			: now()->subDays(7)->startOfDay();

		$endDate = $request->has('to_date') && $request->input('to_date') != ''
			? Carbon::createFromFormat('Y-m-d', $request->input('to_date'))->endOfDay()
			: now()->endOfDay();

		// Find workorders where current technician is assigned
		$workOrderPayments = WorkOrder::whereJsonContains('technician', (string)$userId)
			->pluck('payment') // get JSON payments arrays
			->filter() // remove null/empty
			->flatMap(function ($item) {
				return json_decode($item, true);
			})
			->unique()
			->toArray();

		// Query payments filtered by IDs and payment date range
		$payments = Payment::whereIn('id', $workOrderPayments)
			->whereBetween('payment_date', [$startDate, $endDate])
			->with('user') // eager load user relation for name, phone etc
			->get();

		return view('techdashboard.payment', compact('payments', 'startDate', 'endDate'));
	}

	public function workOrders(Request $request)
	{
		$userId = auth()->user()->id;

		// Date range: default last 7 days
		$startDate = $request->has('start_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
			: now()->subDays(7)->startOfDay();

		$endDate = $request->has('end_date')
			? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
			: now()->endOfDay();

		$workorders = WorkOrder::whereJsonContains('technician', (string) $userId)
			->whereBetween('created_date', [$startDate, $endDate])
			->get();

		return view('techdashboard.workorders', compact('workorders', 'startDate', 'endDate'));
	}


	public function wofortechnician($id)
	{
		$workOrder = WorkOrder::with('client', 'vehicl')->find($id);

		$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
		$vehicles = Vehicle::whereIn('id', $vehicleIds)->get();

		$bookingIds = json_decode($workOrder->booking, true) ?? [];

		$servicegroup_id = json_decode($workOrder->service_group, true);
		$servicegroup = ServiceGroups::find($servicegroup_id);

		$quotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->whereIn('booking_quotations.booking_id', $bookingIds)
			->where('status', 'confirmed')
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.location',
				'booking_items.taxamount',
				'booking_items.totalamount',
				'booking_items.uom',
				'booking_items.uom_name',
			)
			->get()
			->groupBy('quotation_id');


		return view('techdashboard.individualw', [
			'workOrder' => $workOrder,
			'vehicles' => $vehicles,
			'servicegroup' => $servicegroup,
			'quotations' => $quotations,
		]);
	}

	public function updateworkorder($id)
	{
		$workorderid = $id;
		return view('techdashboard.updatestatus', compact('workorderid'));
	}
}
