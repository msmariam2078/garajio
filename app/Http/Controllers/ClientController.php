<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\WarHouse;
use App\Models\WorkOrder;
use Illuminate\Support\Str;
use App\Models\ClientDetail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CustomerTemplate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Models\Warranty_registration;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
	public function index()
	{
		return view('client.index');
	}

	public function clientsList(Request $request)
	{
		if ($request->ajax()) {
			$clients = User::where('type', 'client')
				->with('invoice')
				->where(function ($query) {
					$query->where('isDeleted', false)->orWhereNull('isDeleted');
				})
				->orderByDesc('id');

			return DataTables::of($clients)
				->addIndexColumn()
				->addColumn('sl', function ($row) {
					return ''; // hidden
				})
				->addColumn('client_id', function ($row) {
					return 'CLI-' . $row->id;
				})
				->addColumn('name', function ($row) {
					return $row->first_name . ' ' . $row->last_name;
				})
				->addColumn('phone', function ($row) {
					$ccm = preg_replace('/[^0-9]/', '', $row->ccm);
					return '+' . $ccm . ($row->phone_number ?? '-');
				})
				->addColumn('type', function ($row) {
					return $row->client_type ? strtoupper($row->client_type) : '-';
				})
				->addColumn('invoice', function ($row) {
					return $row->invoice ? '#INV-' . $row->invoice->id : '';
				})
				->addColumn('status', function ($row) {
					$checked = $row->is_active ? 'checked' : '';
					return '
                    <form action="' . route('client.updateStatus', $row->id) . '" method="POST" class="status-form">
                        ' . csrf_field() . '
                        <label class="custom-switch">
                            <input type="checkbox" name="is_active" class="toggle-status" onchange="this.form.submit()" ' . $checked . '>
                            <span class="slider round"></span>
                        </label>
                    </form>';
				})
				->addColumn('action', function ($row) {
					$idEncrypted = Crypt::encrypt($row->id);

					// View Button
					$viewBtn = '';
					if (auth()->user()->can('show customer')) {
						$viewBtn = '<a href="' . route('client.fetchvehicle', $idEncrypted) . '">
							<img src="' . asset('assets/img/icons/eye.svg') . '" style="width:25px;height:25px;">
						</a>';
					}

					// Edit Button
					$editBtn = '';
					if (auth()->user()->can('edit customer')) {
						$editBtn = '<a class="text-success customModal mx-2" data-bs-toggle="tooltip" data-size="lg"
							data-bs-original-title="Edit" href="#"
							data-url="' . route('client.edit', $row->id) . '"
							data-title="Edit Client">
							<img src="' . asset('assets/img/icons/edit.svg') . '" style="width:25px;height:25px;">
						</a>';
					}

					// Delete Button
					$deleteBtn = '';
					if (auth()->user()->can('delete customer')) {
						$deleteBtn = '<form method="POST" action="' . route('client.destroy', $row->id) . '" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\')">
							' . method_field('DELETE') . csrf_field() . '
							<a class="text-danger" href="#" onclick="$(this).closest(\'form\').submit(); return false;">
								<img src="' . asset('assets/img/icons/trash.svg') . '" style="width:25px;height:25px;">
							</a>
						</form>';
					}

					// Combine and return buttons
					return $viewBtn . $editBtn . $deleteBtn;
				})
				->rawColumns(['status', 'action']) // allow HTML
				->make(true);
		}
	}

	public function fetchPlace(Request $request)
	{
		$address = $request->input('address');
		$apiKey = 'AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc'; // Replace with your actual Google API key

		$response = Http::get("https://maps.googleapis.com/maps/api/place/findplacefromtext/json", [
			'input' => $address,
			'inputtype' => 'textquery',
			'fields' => 'formatted_address,name,geometry',
			'key' => $apiKey,
		]);
		return $response->json();
	}
	public function create()
	{

		$country_code = User::$country_code;
		$title = User::$title;
		$country = User::$country;
		$country_code_s = settings()['country_code'];
		$country_s = settings()['country'];
		$currency_s = settings()['CURRENCY_SYMBOL'];
		$company_name = settings()['company_name'];
		$timezone = settings()['timezone'];
		$customertemplate = CustomerTemplate::all();
		$companies = ClientDetail::distinct('company')->pluck('company', 'company')->filter()->toArray();
		$companies = ['' => 'Select a company'] + $companies;

		return view('client.create', compact(
			'companies',
			'country_code',
			'title',
			'country',
			'country_code_s',
			'country_s',
			'currency_s',
			'company_name',
			'timezone',
			'customertemplate'
		));
	}

	public function clientcreate()
	{


		$country_code = User::$country_code;
		$title = User::$title;
		$country = User::$country;
		$country_code_s = settings()['country_code'];
		$country_s = settings()['country'];
		$currency_s = settings()['CURRENCY_SYMBOL'];
		$company_name = settings()['company_name'];
		$timezone = settings()['timezone'];
		$customertemplate = CustomerTemplate::all();


		$companies = ClientDetail::distinct('company')->pluck('company', 'company')->filter()->toArray();
		$companies = ['' => 'Select a company'] + $companies;

		return view('booking.clientcreate', compact(
			'companies',
			'country_code',
			'title',
			'country',
			'country_code_s',
			'country_s',
			'currency_s',
			'company_name',
			'timezone',
			'customertemplate'
		));
	}

	public function direct()
	{
		$wareHouses = WarHouse::where('parent_id', parentId())->get()->pluck('name', 'id');
		$wareHouses->prepend("Select Ware House", null);
		return view('client.direct_create', compact('wareHouses'));
	}

	public function updateStatus(Request $request, $id)
	{
		$user = User::find($id);
		$user->is_active = $request->has('is_active') ? 1 : 0;
		$user->save();

		if ($user->is_active) {
			return redirect()->route('client.index')->with('success', __('Client successfully activated.'));
		} else {
			return redirect()->route('client.index')->with('success', __('Client successfully deactivated.'));
		}
	}
	public function store(Request $request)
	{
		$url = \URL::previous();

		$validatedData = $request->validate([
			'addresses.*.address' => 'nullable|string',
			'addresses.*.city' => 'nullable|string',
			'addresses.*.state' => 'nullable|string',
			'addresses.*.country' => 'nullable|string',
			'addresses.*.zip_code' => 'nullable|string',
			//  'email' => 'email|unique:users,email',
			// 'phone_number' => 'required|numeric',
			// 'mobile' => 'numeric',
			//  'whatsapp_number' => 'numeric',
			'm_cc' => 'required|string',
		]);

		$m_cc = $request->m_cc;
		$phone_number = $request->phone_number;
		$p_cc = $request->p_cc;
		$mobile = $request->mobile;
		$ids = parentId();

		// if($m_cc=='India (+91)'&& strlen($phone_number)!=10)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }
		// if($m_cc=='Qatar (+974)'||$m_cc=='Oman (+968)'||$m_cc='Kuwait (+965)'&& strlen($phone_number)!=8)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }
		// if($m_cc=='Bahrain (+973)'||$m_cc=='Yemen (+967)'&& strlen($phone_number)!=7)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }



		// if($p_cc=='India (+91)'&& strlen($mobile)!=10)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }
		// if($p_cc=='Qatar (+974)'||$p_cc=='Oman (+968)'||$p_cc='Kuwait (+965)'&& strlen($mobile)!=8)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }
		// if($p_cc=='Bahrain (+973)'||$p_cc=='Yemen (+967)'&& strlen($mobile)!=7)
		// {
		//     return redirect()->back()->with('error', __('invalid phone number'));
		// }   
		$authUser = \App\Models\User::find($ids);
		$totalClient = $authUser->totalClient();
		$subscription = Subscription::find($authUser->subscription);
		// $user_count = User::where('email', $request->email)->orWhere('phone_number', $request->phone_number)->count();
		// if (!empty($user_count)) {
		//     return redirect()->back()->with('error', __('Email or Mobile already exist.' . $request->email . " " . $request->mobile . " " . $user_count));
		// }

		$userRole = Role::where('name', 'client')->first();
		$user = new User();
		$user->client_type = $request->client_type;
		$user->first_name = Str::title($request->firstname);
		$user->last_name = Str::title($request->last_name);
		$user->email = $request->email;
		$user->phone_number = $request->phone_number;
		$user->ccp = $request->m_cc;
		$user->fax = $request->fax;
		$user->po_box = $request->po_box;
		$user->mobile = $request->mobile;
		// $user->whatsapp_number = $request->whatapps_number;
		$user->landmark = $request->landmark;
		$user->ccm =  $request->p_cc;


		$user->country = $request->country;
		$user->password = \Hash::make(123456);
		$user->type = $userRole->name;
		$user->profile = 'avatar.png';
		$user->lang = 'english';
		$user->parent_id = parentId();
		$user->gst = $request->gst_number;
		$user->isModified = false;
		$user->IsBCToPortalIntegrated = false;
		$user->IsPortalToBCIntegrated = false;

		$user->customer_template = $request->customer_template;
		$user->save();
		$user->assignRole($userRole);
		$addressesData = $validatedData['addresses'];
		if (!empty($user)) {
			$client = new ClientDetail();
			$client->client_id = $this->clientNumber();
			$client->user_id = $user->id;

			$client->business_name = $request->business_name;

			$client->note_customer = $request->note_customer;
			$client->note_contact = $request->note_contact;

			$client->company = $request->company ? $request->company : $request->business_name;

			$client->type = $request->client_type;

			$client->service_address = $request->service_address;
			$client->service_city = $request->service_city;
			$client->service_state = $request->service_state;
			$client->service_country = $request->service_country;
			$client->service_zip_code = $request->service_zip_code;

			$client->billing_address = $request->billing_address;
			$client->billing_city = $request->billing_city;
			$client->billing_state = $request->billing_state;
			$client->billing_country = $request->billing_country;
			$client->billing_zip_code = $request->billing_zip_code;

			$client->virtual = "Fixed";
			$client->addresses = json_encode($addressesData);
			$client->parent_id = parentId();
			$client->save();
		}

		return redirect()->back()->with([
			'success' => __('Client successfully created.'),
			'customer_id' => $user->id,
		]);
	}

	public function show($ids)
	{
		$id = Crypt::decrypt($ids);
		$client = User::find($id);
		return view('client.show', compact('client'));
	}

	public function edit($id)
	{
		$user = User::with('clients')->find($id);

		$wareHouses = WarHouse::where('parent_id', parentId())->get()->pluck('name', 'id');
		$wareHouses->prepend("Select Ware House", null);

		$country_code = User::$country_code;
		$title = User::$title;
		$country = User::$country;
		$companies = ClientDetail::distinct('company')->pluck('company', 'company')->prepend('Select a company', '')->filter()->all();
		$customertemplate = CustomerTemplate::all();
		return view('client.edit', compact('companies', 'user', 'wareHouses', 'country_code', 'title', 'country', 'customertemplate'));
	}

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'email' => [
				'nullable',
				'email',
				Rule::unique('users')->ignore($id),
			],
			'phone_number' => [
				'nullable',
				'string',
				Rule::unique('users')->ignore($id),
			],
			'addresses.*.address' => 'nullable|string',
			'addresses.*.city' => 'nullable|string',
			'addresses.*.state' => 'nullable|string',
			'addresses.*.country' => 'nullable|string',
			'addresses.*.zip_code' => 'nullable|string',
		]);

		if ($validator->fails()) {
			return redirect()->back()
				->withInput()
				->with('error', implode(' ', $validator->errors()->all()));
		}

		$validatedData = $validator->validated();
		$addressesData = $validatedData['addresses'] ?? null;

		$user = User::find($id);
		$user->client_type = $request->client_type;
		$user->first_name = $request->firstname;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->phone_number = $request->phone_number;
		$user->ccm = $request->m_cc;
		$user->fax = $request->fax;
		$user->po_box = $request->po_box;
		$user->mobile = $request->mobile;
		$user->ccp = $request->p_cc;
		$user->country = $request->country;
		$user->gst = $request->gst_number;
		$user->isModified = true;
		$user->save();

		if (!empty($user)) {
			$client = ClientDetail::where('user_id', $user->id)->first();
			$client->business_name = $request->business_name;
			$client->note_customer = $request->note_customer;
			$client->note_contact = $request->note_contact;
			$client->company = $request->company ? $request->company : $request->business_name;
			$client->type = $request->client_type;
			$client->service_address = $request->service_address;
			$client->service_city = $request->service_city;
			$client->service_state = $request->service_state;
			$client->service_country = $request->service_country;
			$client->service_zip_code = $request->service_zip_code;

			$client->billing_address = $request->billing_address;
			$client->billing_city = $request->billing_city;
			$client->billing_state = $request->billing_state;
			$client->billing_country = $request->billing_country;
			$client->billing_zip_code = $request->billing_zip_code;
			$client->addresses = json_encode($addressesData);
			$client->virtual = "Fixed";

			$client->save();
		}
		return redirect()->route('client.index')->with('success', __('Client successfully updated.'));
	}

	public function destroy($id)
	{
		$user = User::find($id);
		$user->isDeleted = 1;
		$user->save();

		return redirect()->route('client.index')->with('success', __('Client successfully deleted.'));
	}

	public function clientNumber()
	{
		$lastClient = ClientDetail::where('parent_id', parentId())->latest()->first();
		if ($lastClient == null) {
			return 1;
		} else {
			return $lastClient->client_id + 1;
		}
	}

	public function fetchVehicle($id)
	{

		$userid = Crypt::decrypt($id);

		$user = User::with('clients')->find($userid);

		$vehicles = Vehicle::where('client', $user->id)
			->has('vehicle_makes')->has('vehicle_models')->get();

		$bookings = Booking::where('client', $user->id)->get();

		$workorders = WorkOrder::where('customer_id', $user->id)->get();

		$invoices = Invoice::where('client', $user->id)->get();

		$warrentyRegistrations = Warranty_registration::has('product')->where('customer_id', $user->id)->get();
		$client = User::find($user->id);
		return view('client.alldetails', compact('vehicles', 'bookings', 'workorders', 'client', 'invoices', 'warrentyRegistrations'));
	}

	public function fetchBookingHistory($id)
	{
		$userid = Crypt::decrypt($id);

		$user = User::with('clients')->find($userid);
		$bookings = Booking::where('client', $user->id)->get();
		return view('client.fetchbookinghistory', compact('bookings'));
	}

	public function fetchWorkOrderHistory($id)
	{


		$userid = Crypt::decrypt($id);
		$user = User::with('clients')->find($userid);
		$workorders = WorkOrder::where('customer_id', $user->id)->get();
		return view('client.fetchworkorder', compact('workorders'));
	}

	public function search(Request $request)
	{
		$query = $request->input('query');


		$clients = User::where('type', 'client')
			->where(function ($q) use ($query) {
				$q->where('first_name', 'LIKE', "%{$query}%")
					->orWhere('email', 'LIKE', "%{$query}%")
					->orWhere('phone_number', 'LIKE', "%{$query}%");
			})
			->get();


		if ($clients->count()) {
			$output = '';
			foreach ($clients as $client) {

				$encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($client->id);


				$output .= '<li class="dropdown-item">'
					. '<a href="' . route('client.show', $encryptedId) . '" data-id="' . $encryptedId . '" data-name="' . $client->first_name . '" data-email="' . $client->email . '" data-phone="' . $client->phone_number . '">'
					. '<strong>' . htmlspecialchars($client->first_name) . '</strong></a><br>'
					. '<small>Email: ' . htmlspecialchars($client->email) . '</small><br>'
					. '<small>Phone: +' . htmlspecialchars($client->ccm) . htmlspecialchars($client->phone_number) . '</small>'
					. '</li>';
			}
		} else {
			$output = '<li class="dropdown-item">' . __('No client found.') . '</li>';
		}


		return response()->json($output);
	}

	public function searchCustomer(Request $request)
	{
		$searchQuery = $request->query('query');

		$customers = User::with(['clients' => function ($query) {
			$query->select('user_id', 'service_country', 'service_city', 'service_address');
		}])
			->where('type', 'client')
			->where(function ($query) {
				$query->where('isDeleted', false)
					->orWhereNull('isDeleted');
			})
			->where(function ($query) use ($searchQuery) {
				$query->where('first_name', 'LIKE', "%$searchQuery%")
					->orWhere('last_name', 'LIKE', "%$searchQuery%")
					->orWhere('email', 'LIKE', "%$searchQuery%")
					->orWhere('phone_number', 'LIKE', "%$searchQuery%");
			})
			->get(['id', 'first_name', 'last_name', 'email', 'ccm', 'phone_number', 'client_type']);

		return response()->json($customers); // Returning filtered customers as JSON response
	}


	public function validatePhoneNumber(Request $request)
	{
		// Get the phone number from the request and trim any extra spaces
		//$phoneNumber = trim($request->input('phone_number'));

		$validator = \Validator::make($request->all(), [

			"phone_number" => "numeric|digits_between:7,10|unique:users,phone_number"
		]);
		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return response()->json(['status' => 'error', 'data' => $messages]);
		} else {
			return response()->json(['status' => 'success', 'data' => []]);
		}

		// Check if the phone number starts with 971 and is followed by 7 or 8 digits
		// if (preg_match('/^971[0-9]{7,8}$/', $phoneNumber)) {

		//     return response()->json([
		//         'status' => 'success',
		//         'message' => __('The phone number is a valid UAE number'),
		//     ]);
		// } else {
		//     // Phone number doesn't match UAE format
		//     return response()->json([
		//         'status' => 'error',
		//         'message' => __('The phone number is not a valid UAE number'),
		//     ], 422);
		// }
	}

	public function emailvalidate(Request $request)
	{
		$validator = \Validator::make($request->all(), [
			"email" => 'email|unique:users,email',
			"phone_number" => "numeric|unique:users,phone_number|min:7"
		]);
		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return response()->json(['status' => 'error', 'data' => $messages]);
		} else {
			return response()->json(['status' => 'success', 'data' => []]);
		}
	}


	public function getCustomer(Request $request)
	{
		$customer = User::find($request->id);

		if (!$customer) {
			return response()->json(['error' => 'Customer not found'], 404);
		}

		return response()->json($customer);
	}
}
