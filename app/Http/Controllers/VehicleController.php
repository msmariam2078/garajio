<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\EngineSpecs;
use App\Models\vehicle_make;
use App\Models\vehicle_seat;
use App\Models\vehicle_tran;
use Illuminate\Http\Request;
use App\Models\RegionalSpecs;
use App\Models\vehicle_model;
use Yajra\DataTables\DataTables;
use App\Models\vehicle_body_type;
use App\Models\vehicle_fuel_type;
use App\Models\vehicle_colour_new;
use App\Models\vehicle_model_code;
use App\Models\vehicle_driving_type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
	public function index()
	{
		return view('vehicle.index');
	}

	public function vehiclesList(Request $request)
	{
		$query = Vehicle::with(['vehicle_models', 'vehicle_makes', 'clientInfo'])
			->where(function ($q) {
				$q->where('isDeleted', false)->orWhereNull('isDeleted');
			})
			->orderByDesc('id');

		return DataTables::of($query)
			->addIndexColumn()   // Adds DT_RowIndex (serial)

			->addColumn('id', fn($v) => '#E-' .$v->id) // actual DB ID
			->addColumn('sl', fn() => '') // placeholder, can be used in JS for serial if needed
			->addColumn('model', fn($v) => optional($v->vehicle_models)->model_name ?? 'N/A')
			->addColumn('make',  fn($v) => optional($v->vehicle_makes)->make_name  ?? 'N/A')
			->addColumn('customer', function ($v) {
				return $v->clientInfo
					? $v->clientInfo->first_name . ' ' . $v->clientInfo->last_name
					: '-';
			})
			->filterColumn('customer', function ($query, $keyword) {
				$query->whereHas('clientInfo', function ($q) use ($keyword) {
					$q->where('first_name', 'like', "%{$keyword}%")
						->orWhere('last_name', 'like', "%{$keyword}%");
				});
			})
			->addColumn('status', function ($v) {
				$checked = $v->status ? 'checked' : '';
				return '
            <form action="' . route('vehicle.updateStatus', $v->id) . '" method="POST" class="status-form">
                ' . csrf_field() . '
                <label class="custom-switch">
                    <input type="checkbox" name="status" class="toggle-status"
                           onchange="this.form.submit()" ' . $checked . '>
                    <span class="slider round"></span>
                </label>
            </form>';
			})
			->addColumn('action', function ($v) {
				$btns = '';
				if (auth()->user()->can('view vehicle')) {
					$btns .= '<a class="text-warning" href="' . route('vehicle.show', Crypt::encrypt($v->id)) . '">
                         <img src="' . asset('assets/img/icons/eye.svg') . '" style="width:25px;height:25px;">
                     </a>';
				}
				if (auth()->user()->can('edit vehicle')) {
					$btns .= '<a class="text-success customModal px-2" data-url="' . route('vehicle.edit', $v->id) . '"
                         data-title="Edit Equipment" style="cursor: pointer;">
                         <img src="' . asset('assets/img/icons/edit.svg') . '" style="width:25px;height:25px;">
                     </a>';
				}
				if (auth()->user()->can('delete vehicle')) {
					$btns .= '<form method="POST" action="' . route('vehicle.destroy', $v->id) . '" class="d-inline delete-form">
                          ' . method_field('DELETE') . csrf_field() . '
                          <a class="text-danger confirm_dialog" href="#">
                             <img src="' . asset('assets/img/icons/trash.svg') . '"
                                  style="width:25px;height:25px;">
                          </a>
                      </form>';
				}
				return $btns;
			})
			->rawColumns(['status', 'action']) // allow HTML rendering for these columns
			->make(true);
	}

	public function create()
	{
		//if (Auth::user()->can('create vehicle')) {

			// $clients = User::where('type', 'client')->where('isDeleted', false)->orWhereNull('isDeleted')->orderBy('id', 'desc')->get()

			// 	->mapWithKeys(function ($client) {
			// 		return [

			// 			$client->id => $client->full_name . ' - ' . $client->email . ' - ' . '+' . preg_replace('/[^0-9]/', '',  $client->ccm) . $client->phone_number
			// 		];
			// 	});

			$country = User::$country;
			// $clients->prepend(__('Select Client'), '');

			$brand = Vehicle::$brand;
			$uom = UOM::where('isDeleted', false)
				->orWhereNull('isDeleted')
				->get();

			$emirates = Vehicle::$emirates;

			$vm = vehicle_make::orderBy('make_name', 'asc')->get();



			$vmod = vehicle_model::orderBy('model_name', 'asc')->get();


			$vmc = vehicle_model_code::all()->pluck('model_code', 'id');
			$vmc->prepend(__('Select Vehicle Model Code'), '');

			$vcn = vehicle_colour_new::all()->pluck('colour', 'id');
			$vcn->prepend(__('Select Vehicle Colour'), '');

			$vbt = vehicle_body_type::all()->pluck('body_type', 'id');
			$vbt->prepend(__('Select Vehicle Body Type'), '');

			$vdt = vehicle_driving_type::all()->pluck('driving_type', 'id');
			$vdt->prepend(__('Select Vehicle Driving Type'), '');

			$vft = vehicle_fuel_type::all()->pluck('fuel_type', 'id');
			$vft->prepend(__('Select Vehicle Fuel Type'), '');

			$vsc = vehicle_seat::all()->pluck('seating_capacity', 'id');
			$vsc->prepend(__('Select Vehicle Seating Capacity'), '');

			$vt = vehicle_tran::all()->pluck('transmission', 'id');
			$vt->prepend(__('Select Vehicle Transmission'), '');



			$es = EngineSpecs::all();


			$rs = RegionalSpecs::all();

			$country = User::$country;
			$country_code_s = settings()['country_code'];
			$country_s = settings()['country'];
			return view('vehicle.create', compact('country', 'country_code_s', 'country_s', 'brand', 'uom', 'emirates', 'vm', 'vmod', 'vmc', 'vcn', 'vbt', 'vdt', 'vft', 'vsc', 'vt', 'country', 'es', 'rs'));
		// } else {
		// 	return redirect()->route('vehicle.index')->with('error', __('Permission Denied!'));
		// }
	}

	public function updateStatus(Request $request, $id)
	{
		$user = Vehicle::find($id);
		$user->status = $request->has('status') ? 1 : 0;
		$user->save();

		if ($user->status) {
			return redirect()->route('vehicle.index')->with('success', __('Vehicle successfully activated.'));
		} else {
			return redirect()->route('vehicle.index')->with('success', __('Vehicle successfully deactivated.'));
		}
	}


	public function direct()
	{
		if (Auth::user()->can('create vehicle')) {
			$clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('name', 'id');
			$clients->prepend(__('Select Client'), '');
			return view('vehicle.direct_create', compact('clients'));
		} else {
			return redirect()->route('vehicle.index')->with('error', __('Permission Denied!'));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		// if (Auth::user()->can('create vehicle')) {
			$vehicle = Vehicle::create([
				'rego' => strtoupper($request->input('rego')),
				'name' => $request->input('name'),
				'client' => $request->input('client'),
				'state' => $request->input('state'),
				'v_make' => $request->input('v_make'),
				'vm' => $request->input('vm'),
				'vmc' => $request->input('vmc'),
				'model_series' => $request->input('model_series'),
				'vin' => $request->input('vin'),
				'body_type' => $request->input('body_type'),
				'drive_type' => $request->input('drive_type'),
				'engine_number' => $request->input('engine_number'),
				'engine_code' => $request->input('engine_code'),
				'vt' => $request->input('vt'),
				'vdt' => $request->input('vdt'),
				'vft' => $request->input('vft'),
				'a_c' => $request->input('a_c'),
				'vbt' => $request->input('vbt'),
				'vcn' => $request->input('vcn'),
				'vsc' => $request->input('vsc'),
				'es_id' => $request->filled('engine_specs') ? $request->input('engine_specs') : null,
				'rs_id' => $request->input('regional_specs'),
				'odometer' => $request->input('odometer'),
				'parent_id' => parentId(),
				'isModified' => false,
				'IsBCToPortalIntegrated' => false,
				'IsPortalToBCIntegrated' => false,
				'uom' => $request->input('unit'),
			]);


			session([
				'vehicle_id' => $vehicle->id,
				'customer_id' => $request->input('client'),
			]);

			if ($request->workorder_id) {
				$woids = $request->workorder_id;
				$workOrders = WorkOrder::where('id', $woids)->get();
				foreach ($workOrders as $workOrder) {
					$existingVehicles = json_decode($workOrder->vehicle, true);
					$existingVehicles = $existingVehicles ?? [];
					$existingVehicles[] = $vehicle->id;
					$uniqueVehicles = array_unique($existingVehicles);
					$workOrder->vehicle = json_encode($uniqueVehicles);
					$workOrder->save();
				}
				return redirect()->route('workorder.edit', ['workorder' => $request->workorder_id]);
			}

			if ($request->direct_create) {
				if (!empty($vehicle)) {
					$client = Vehicle::find($vehicle->id)->Client()->select('id', 'name')->first();
					$response['status'] = true;
					$response['message'] = __('Vehicle successfully created');
					$response['data'] = Vehicle::where('parent_id', parentId())->orderBy('created_at', 'desc')->get()->pluck('name', 'id');
					$response['client'] = $client;
				} else {
					$response['status'] = false;
					$response['message'] = __('Vehicle creation failed');
					$response['data'] = [];
				}
				return json_encode($response);
			} else {
				return redirect()->back()->with('success', __('Vehicle successfully created.'));
			}
		// } else {
		// 	return redirect()->back()->with('error', __('Permission Denied!'));
		// }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$vehicle = Vehicle::with('client')->find(Crypt::decrypt($id));
		return view('vehicle.show', compact('vehicle'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if (Auth::user()->can('edit vehicle')) {

			$vehicle = Vehicle::with('clientInfo')->find($id);
			
			$selectedClientName = $vehicle->clientInfo->full_name . ' - ' . $vehicle->clientInfo->email . ' - +' . preg_replace('/[^0-9]/', '', $vehicle->clientInfo->ccm) . $vehicle->clientInfo->phone_number;
			$selectedClientId = $vehicle->clientInfo->id;


			if (!$vehicle) {
				return redirect()->route('vehicle.index')->with('error', __('Vehicle not found!'));
			}

			$brand = Vehicle::$brand;
			$uom = UOM::where('isDeleted', false)
				->orWhereNull('isDeleted')
				->get();

			$emirates = Vehicle::$emirates;

			$vm = vehicle_make::all();
			$vmod = vehicle_model::all()->pluck('model_name', 'id');
			$vmod->prepend(__('Select Vehicle Model'), '');


			$vmc = vehicle_model_code::all()->pluck('model_code', 'id');
			$vmc->prepend(__('Select Vehicle Model Code'), '');

			$vcn = vehicle_colour_new::all()->pluck('colour', 'id');
			$vcn->prepend(__('Select Vehicle Colour'), '');

			$vbt = vehicle_body_type::all()->pluck('body_type', 'id');
			$vbt->prepend(__('Select Vehicle Body Type'), '');

			$vdt = vehicle_driving_type::all()->pluck('driving_type', 'id');
			$vdt->prepend(__('Select Vehicle Driving Type'), '');

			$vft = vehicle_fuel_type::all()->pluck('fuel_type', 'id');
			$vft->prepend(__('Select Vehicle Fuel Type'), '');

			$vsc = vehicle_seat::all()->pluck('seating_capacity', 'id');
			$vsc->prepend(__('Select Vehicle Seating Capacity'), '');

			$vt = vehicle_tran::all()->pluck('transmission', 'id');
			$vt->prepend(__('Select Vehicle Transmission'), '');

			$es = EngineSpecs::all();
			$rs = RegionalSpecs::all();

			$country = User::$country;
			$country_code_s = settings()['country_code'];
			$country_s = settings()['country'];

			return view('vehicle.edit', compact(
				'selectedClientName',
				'selectedClientId',
				'es',
				'rs',
				'vehicle',
				'brand',
				'uom',
				'emirates',
				'vm',
				'vmod',
				'vmc',
				'vcn',
				'vbt',
				'vdt',
				'vft',
				'vsc',
				'vt'
			));
		} else {
			return redirect()->route('vehicle.index')->with('error', __('Permission Denied!'));
		}
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $encryptedId)
	{
		if (Auth::user()->can('edit vehicle')) {
			//    $id = Crypt::decrypt($encryptedId);
			$vehicle = Vehicle::findOrFail($encryptedId);

			$vehicle->update([
				'rego' => $request->input('rego'),
				'name' => $request->input('name'),
				'client' => $request->input('client'),
				'state' => $request->input('state'),
				'v_make' => $request->input('v_make'),
				'vm' => $request->input('vm'),
				'vmc' => $request->input('vmc'),
				'model_series' => $request->input('model_series'),
				'vin' => $request->input('vin'),
				'body_type' => $request->input('body_type'),
				'drive_type' => $request->input('drive_type'),
				// 'rego_due_date' => $request->input('rego_due_date'),
				// 'last_in_date' => $request->input('last_in_date'),
				// 'service_interval' => $request->input('service_interval'),
				// 'radio_pin' => $request->input('radio_pin'),
				// 'key_code' => $request->input('key_code'),
				// 'note' => $request->input('note'),
				'engine_number' => $request->input('engine_number'),
				// 'fleet_code' => $request->input('fleet_code'),
				'vt' => $request->input('vt'),
				'vdt' => $request->input('vdt'),
				'vft' => $request->input('vft'),
				'a_c' => $request->input('a_c'),
				'vbt' => $request->input('vbt'),
				'vcn' => $request->input('vcn'),
				'vsc' => $request->input('vsc'),
				'odometer' => $request->input('odometer'),
				// 'hours' => $request->input('hours'),
				'engine_code' => $request->input('engine_code'),
				// 'chassis_no' => $request->input('chassis_no'),
				// 'build_date' => $request->input('build_date'),
				// 'prod_date' => $request->input('prod_date'),
				// 'last_service' => $request->input('last_service'),
				// 'next_service' => $request->input('next_service'),
				// 'cylinders' => $request->input('cylinders'),
				// 'liters' => $request->input('liters'),
				// 'fuel_induction' => $request->input('fuel_induction'),
				// 'fuel_type' => $request->input('fuel_type'),
				// 'tare_mass' => $request->input('tare_mass'),
				// 'tire_size' => $request->input('tire_size'),
				// 'imported_id' => $request->input('imported_id'),
				'parent_id' => parentId(),
				'isModified' => 1,
				'uom' => $request->input('unit'),
			]);

			return redirect()->back()->with('success', __('Vehicle successfully Updated.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied!'));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if (Auth::user()->can('delete vehicle')) {

			$vehicle = Vehicle::findOrFail($id);
			$vehicle->isDeleted = 1;
			$vehicle->save();

			return redirect()->back()->with('success', __('Vehicle successfully Deleted.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied!'));
		}
	}

	public function getVehicleDetails($id)
	{
		try {

			$vehicle = Vehicle::with(['vehicle_makes', 'vehicle_models'])
				->where('id', $id)
				->firstOrFail();

			$vehicleDetails = [
				'id' => $vehicle->id,
				'rego' => $vehicle->rego,
				'name' => $vehicle->name,
				'client' => $vehicle->client,
				'make_name' => $vehicle->vehicle_makes->make_name ?? null,
				'model_name' => $vehicle->vehicle_models->model_name ?? null,
				'model_series' => $vehicle->model_series,
				'engine_number' => $vehicle->engine_number,

			];


			return response()->json($vehicleDetails, 200);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return response()->json(['message' => 'Vehicle not found'], 404);
		} catch (\Exception $e) {
			return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
		}
	}

	public function getModels($makeId)
	{
		$models = vehicle_model::where('make_id', $makeId)->orderBy('model_name', 'asc')->get(['id', 'model_name']);

		return response()->json($models);
	}

	public function getEngineSpecification(Request $request)
	{
		$makeId = $request->input('make_id');
		$modelId = $request->input('model_id');


		$specifications = EngineSpecs::where('make_id', $makeId)
			->where('model_id', $modelId)
			->get(['id', 'enginespecs']);

		return response()->json($specifications);
	}

	public function gettechvehiclemodal($id)
	{
		$vehicle = Vehicle::findOrFail($id);
		return view('techdashboard.editvehicle', compact('vehicle'));
	}
	public function updateVehicleDetails(Request $request)
	{



		$vehicle = Vehicle::findOrFail($request->vehicle_id);

		$vehicle->rego = $request->rego;
		$vehicle->odometer = $request->odometer;


		$vehicle->save();


		return redirect()->back()->with('success', 'Vehicle details updated successfully!');
	}

	public function checkRego(Request $request)
	{
		$exists = Vehicle::where('rego', $request->rego)->exists();
		return response()->json(['exists' => $exists]);
	}
}
