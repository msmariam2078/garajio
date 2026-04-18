<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ClientDetail;
use App\Models\Estimation;
use App\Models\EstimationServicePart;
use App\Models\Inquiry;
use App\Models\ServicePart;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WarHouse;
use App\Models\WORequest;
use App\Models\WorkOrder;
use App\Models\WOServicePart;
use App\Models\WOServiceTask;
use App\Models\WOType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('manage inquiry')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        $inquiries = Inquiry::with('estimation')->where('parent_id', parentId())->get();
        return view('inquiry.index', compact('inquiries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create inquiry')) {
            $clients = User::where('parent_id', parentId())->where('type', 'client')->get();

            $assets = Asset::where('parent_id', parentId())->get()->pluck('name', 'id');
            $assets->prepend(__('Select Parent Asset'), '');

            $parts = ServicePart::where('parent_id', parentId())->get()->pluck('title', 'id');
            $parts->prepend(__('Select Part'), '');

            $vehicles = Vehicle::where('parent_id', parentId())->get();

            $estimation = new EstimationController();
            $estimationNumber = $estimation->estimationNumber();

            $inquiryNumber = $this->inquiryNumber();

            $woTypes = WOType::where('parent_id', parentId())->get()->pluck('type', 'id');
            $woTypes->prepend(__('Select Type'), '');

            $users = User::where('parent_id', parentId())->where('type', '=', 'technician')->get()->pluck('name', 'id');
            $users->prepend(__('Select User'), '');

            $warehouse = WarHouse::where('parent_id', parentId())->get()->pluck('name', 'id');

            $priority = WORequest::$priority;
            $status = Inquiry::$status;
            $location = Inquiry::$location;
            $workOrderNumber = $this->workOrderNumber();
            $time = WORequest::$time;

            return view('inquiry.create', compact('assets', 'estimationNumber', 'parts', 'vehicles', 'inquiryNumber', 'users', 'clients', 'priority', 'status', 'woTypes', 'workOrderNumber', 'time', 'location', 'warehouse'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function inquiryNumber()
    {
        $lastInquiry = Inquiry::where('parent_id', parentId())->latest()->first();
        if ($lastInquiry == null) {
            return 1;
        } else {
            return $lastInquiry->inquiry_id + 1;
        }
    }

    public function workOrderNumber()
    {
        $lastWorkorder = WorkOrder::where('parent_id', parentId())->latest()->first();
        if ($lastWorkorder == null) {
            return 1;
        } else {
            return $lastWorkorder->wo_id + 1;
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
        if (Auth::user()->can('create inquiry')) {
            // validation
            // $validator = Validator::make($request->all(), [
            //     'date' => 'required',
            //     'client' => 'required',
            // ]);
            // if ($validator->fails()) {
            //     $messages = $validator->getMessageBag();
            //     return redirect()->back()->with('error', $messages->first());
            // }

            $EstimationController = new EstimationController();
            $Inquiry = new Inquiry();
            // $Inquiry->inquiry_id = $request->inquiry_id;
            $Inquiry->date = $request->date;
            $Inquiry->description = $request->description;
            $Inquiry->vehicle = $request->vehicle != "" ? $request->vehicle : 0;
            // $Inquiry->appointment_date = $request->appointment_date != "" ? $request->appointment_date : null;
            // $Inquiry->appointment_time = $request->appointment_time != "" ? $request->appointment_time : null;
            // $Inquiry->appointment_note = $request->appointment_note != "" ? $request->appointment_note : null;
            // $Inquiry->address = $request->address ?? null;
            $Inquiry->customer = $request->client != "" ? $request->client : 0;
            $Inquiry->status = $request->status;
            $Inquiry->note = e($request->notes_value);
            $Inquiry->requires_followup = $request->require_followup ? 1 : 0;
            $Inquiry->representative = 0;
            $Inquiry->location = $request->location;
            if ($request->location == "fixed") {
                $Inquiry->location_text = $request->location_fixed;
            } else {
                $Inquiry->location_text = $request->location_mobile;
            }

            $Inquiry->parent_id = parentId();

            // $estimationId = 0;
            // if (!empty($request->estimation_id) && !empty($request->due_date)) {

            //     $estimation = new Estimation();
            //     $estimation->estimation_id = $request->estimation_id;
            //     $estimation->title = $request->title;
            //     $estimation->client = $request->client;
            //     $estimation->due_date = $request->due_date;
            //     $estimation->notes = $request->notes;
            //     $estimation->status = 'pending';
            //     $estimation->parent_id = parentId();
            //     $estimation->save();

            //     $estimationId = $estimation->id;
            //     $parts = !empty($request->parts) ? $request->parts : [];

            //     if (!empty($parts)) {
            //         for ($i = 0; $i < count($parts); $i++) {
            //             $service = ServicePart::find($parts[$i]['service_part_id']);
            //             $estimationPart = new EstimationServicePart();
            //             $estimationPart->estimation_id = $estimation->id;
            //             $estimationPart->service_part_id = $parts[$i]['service_part_id'];
            //             $estimationPart->quantity = $parts[$i]['quantity'];
            //             $estimationPart->amount = $parts[$i]['amount'];
            //             $estimationPart->description = $parts[$i]['description'];
            //             $estimationPart->type = $service->type ?? 'part';
            //             $estimationPart->save();
            //         }
            //     }
            // }

            // $workoredrId = 0;
            // if (!empty($request->wo_id)
            //     && !empty($request->wo_detail)
            //     && !empty($request->type)
            //     && !empty($request->workorder_due_date)
            //     && !empty($request->priority)
            //     && !empty($request->assign)) {
            //     $workOrder = new WorkOrder();
            //     $workOrder->wo_id = $request->wo_id;
            //     $workOrder->wo_detail = $request->wo_detail;
            //     $workOrder->type = $request->type;
            //     $workOrder->client = $request->client;
            //     $workOrder->priority = $request->priority;
            //     $workOrder->due_date = $request->workorder_due_date;
            //     $workOrder->status = 'pending';
            //     $workOrder->assign = $request->assign;
            //     $workOrder->notes = !empty($request->notes) ? $request->notes : null;
            //     $workOrder->preferred_date = !empty($request->preferred_date) ? $request->preferred_date : null;
            //     $workOrder->preferred_time = !empty($request->preferred_time) ? $request->preferred_time : null;
            //     $workOrder->preferred_note = !empty($request->preferred_note) ? $request->preferred_note : null;
            //     $workOrder->parent_id = parentId();
            //     $workOrder->save();

            //     $workoredrId = $workOrder->id;

            //     $parts = !empty($request->parts) ? $request->parts : [];

            //     if (!empty($parts)) {
            //         for ($i = 0; $i < count($parts); $i++) {
            //             $service = ServicePart::find($parts[$i]['service_part_id']);

            //             $woService = new WOServicePart();
            //             $woService->wo_id = $workOrder->id;
            //             $woService->service_part_id = $parts[$i]['service_part_id'];
            //             $woService->quantity = $parts[$i]['quantity'];
            //             $woService->amount = $parts[$i]['amount'];
            //             $woService->description = $parts[$i]['description'];
            //             $woService->type = $service->type ?? 'part';
            //             $woService->save();

            //             foreach ($service->serviceTasks as $task) {
            //                 $WOServiceTask = new WOServiceTask();
            //                 $WOServiceTask->wo_id = $workOrder->id;
            //                 $WOServiceTask->service_part_id = $task->service_id;
            //                 $WOServiceTask->service_task = $task->task;
            //                 $WOServiceTask->duration = $task->duration;
            //                 $WOServiceTask->description = $task->description;
            //                 $WOServiceTask->status = 'pending';
            //                 $WOServiceTask->save();
            //             }
            //         }
            //     }
            // }
            // $Inquiry->estimation_id = $estimationId;
            // $Inquiry->work_order_id = $workoredrId;
            $Inquiry->save();
            return redirect()->route('inquiry.index')->with('success', __('Inquiry successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('show inquiry')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $inquiry = Inquiry::where('parent_id', parentId())->where('id', Crypt::decrypt($id))->first();

        if ($inquiry->estimation) {

            $estimation = Estimation::find($inquiry->estimation->id);
        }

        if ($inquiry->work_order) {
            $workOrder = WorkOrder::find($inquiry->work_order_id);
        }

        $estimationParts = [];
        if ($inquiry->estimation) {
            $estimationPartData = $estimation->serviceParts;
            foreach ($estimationPartData as $estimationPart) {
                $servicePart = ServicePart::find($estimationPart->service_part_id);
                $estimationPart['id'] = $estimationPart->id;
                $estimationPart['estimation_id'] = $estimationPart->estimation_id;
                $estimationPart['service_part_id'] = $servicePart->title;
                $estimationPart['quantity'] = $estimationPart->quantity;
                $estimationPart['amount'] = $estimationPart->amount;
                $estimationPart['unit'] = !empty($estimationPart->serviceParts) ? $estimationPart->serviceParts->unit : '';
                $estimationParts[] = $estimationPart;
            }
        } elseif ($inquiry->work_order) {
            $workOrderPartData = $workOrder->serviceParts;
            foreach ($workOrderPartData as $woPart) {
                $servicePart = ServicePart::find($woPart->service_part_id);
                $woPart['id'] = $woPart->id;
                $woPart['wo_id'] = $woPart->wo_id;
                $woPart['service_part_id'] = $servicePart->title;
                $woPart['quantity'] = $woPart->quantity;
                $woPart['amount'] = $woPart->amount;
                $woPart['unit'] = !empty($woPart->serviceParts) ? $woPart->serviceParts->unit : '';
                $estimationParts[] = $woPart;
            }
        }

        return view('inquiry.show', compact('inquiry', 'estimationParts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit inquiry')) {

            $inquiry = Inquiry::with('estimation')->where('parent_id', parentId())->find($id);
            if (!$inquiry) {
                return redirect()->back()->with('error', __('Inquiry Not Found!!'));
            }
            if ($inquiry->estimation) {

                $estimation = Estimation::find($inquiry->estimation->id);
            }

            if ($inquiry->work_order) {
                $workOrder = WorkOrder::find($inquiry->work_order_id);
            }

            $clients = User::where('parent_id', parentId())->where('type', 'client')->get();

            $assets = Asset::where('parent_id', parentId())->get()->pluck('name', 'id');
            $assets->prepend(__('Select Parent Asset'), '');

            $parts = ServicePart::where('parent_id', parentId())->get()->pluck('title', 'id');
            $parts->prepend(__('Select Part'), '');

            $vehicles = Vehicle::where('parent_id', parentId())->get();

            $estimationParts = [];
            if ($inquiry->estimation) {
                $estimationPartData = $estimation->serviceParts;
                foreach ($estimationPartData as $estimationPart) {
                    $estimationPart['id'] = $estimationPart->id;
                    $estimationPart['estimation_id'] = $estimationPart->estimation_id;
                    $estimationPart['service_part_id'] = $estimationPart->service_part_id;
                    $estimationPart['quantity'] = $estimationPart->quantity;
                    $estimationPart['amount'] = $estimationPart->amount;
                    $estimationPart['unit'] = !empty($estimationPart->serviceParts) ? $estimationPart->serviceParts->unit : '';
                    $estimationParts[] = $estimationPart;
                }
            } elseif ($inquiry->work_order) {
                $workOrderPartData = $workOrder->serviceParts;
                foreach ($workOrderPartData as $woPart) {
                    $woPart['id'] = $woPart->id;
                    $woPart['wo_id'] = $woPart->wo_id;
                    $woPart['service_part_id'] = $woPart->service_part_id;
                    $woPart['quantity'] = $woPart->quantity;
                    $woPart['amount'] = $woPart->amount;
                    $woPart['unit'] = !empty($woPart->serviceParts) ? $woPart->serviceParts->unit : '';
                    $estimationParts[] = $woPart;
                }
            }
            $estimation = new EstimationController();
            $estimationNumber = $estimation->estimationNumber();

            $woTypes = WOType::where('parent_id', parentId())->get()->pluck('type', 'id');
            $woTypes->prepend(__('Select Type'), '');

            $users = User::where('parent_id', parentId())->where('type', '=', 'technician')->get()->pluck('name', 'id');
            $users->prepend(__('Select User'), '');

            $priority = WORequest::$priority;
            $status = WORequest::$status;
            $workOrderNumber = $this->workOrderNumber();
            $time = WORequest::$time;

            return view('inquiry.edit', compact('inquiry', 'clients', 'assets', 'estimation', 'parts', 'estimationParts', 'estimationNumber', 'vehicles', 'priority', 'status', 'woTypes', 'workOrderNumber', 'time', 'users'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit inquiry')) {
            $id = Crypt::decrypt($id);
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'client' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $EstimationController = new EstimationController();
            $Inquiry = Inquiry::with('estimation')->find($id);
            if (!$Inquiry) {
                return redirect()->back()->with('error', __('Inquiry Not Found!!'));
            }
            $Inquiry->inquiry_id = $request->inquiry_id;
            $Inquiry->date = $request->date;
            $Inquiry->description = $request->description;
            $Inquiry->vehicle = $request->vehicle != "" ? $request->vehicle : 0;
            $Inquiry->appointment_date = $request->appointment_date != "" ? $request->appointment_date : null;
            $Inquiry->appointment_time = $request->appointment_time != "" ? $request->appointment_time : null;
            $Inquiry->appointment_note = $request->appointment_note != "" ? $request->appointment_note : null;
            $Inquiry->work_order_id = $request->work_order ?? 0;
            $Inquiry->address = $request->address ?? null;
            $Inquiry->parent_id = parentId();

            $estimationId = 0;
            if (!empty($request->estimation_id) && !empty($request->due_date)) {
                $estimation = Estimation::find($Inquiry->estimation_id);
                if ($estimation) {
                    $estimation->estimation_id = $request->estimation_id;
                    $estimation->title = $request->title;
                    $estimation->client = $request->client;
                    $estimation->due_date = $request->due_date;
                    $estimation->notes = $request->notes;
                    $estimation->save();
                    $services = !empty($request->services) ? $request->services : [];
                    $parts = !empty($request->parts) ? $request->parts : [];

                    for ($i = 0; $i < count($services); $i++) {
                        $estimationService = EstimationServicePart::find(isset($services[$i]['id']) ? $services[$i]['id'] : 0);
                        if ($estimationService == null) {
                            $estimationService = new EstimationServicePart();
                            $estimationService->estimation_id = $estimation->id;
                        }
                        $estimationService->service_part_id = $services[$i]['service_part_id'];
                        $estimationService->quantity = $services[$i]['quantity'];
                        $estimationService->amount = $services[$i]['amount'];
                        $estimationService->description = $services[$i]['description'];
                        $estimationService->type = 'service';
                        $estimationService->save();
                    }

                    for ($i = 0; $i < count($parts); $i++) {
                        $estimationPart = EstimationServicePart::find(isset($parts[$i]['id']) ? $parts[$i]['id'] : 0);
                        if ($estimationPart == null) {
                            $estimationPart = new EstimationServicePart();
                            $estimationPart->estimation_id = $estimation->id;
                        }
                        $estimationPart->service_part_id = $parts[$i]['service_part_id'];
                        $estimationPart->quantity = $parts[$i]['quantity'];
                        $estimationPart->amount = $parts[$i]['amount'];
                        $estimationPart->description = $parts[$i]['description'];
                        $estimationPart->type = 'part';
                        $estimationPart->save();
                    }
                } else {
                    $estimation = new Estimation();
                    $estimation->estimation_id = $request->estimation_id;
                    $estimation->title = $request->title;
                    $estimation->client = $request->client;
                    $estimation->due_date = $request->due_date;
                    $estimation->notes = $request->notes;
                    $estimation->status = 'pending';
                    $estimation->parent_id = parentId();
                    $estimation->save();

                    $parts = !empty($request->parts) ? $request->parts : [];

                    if (!empty($parts)) {
                        for ($i = 0; $i < count($parts); $i++) {
                            $estimationPart = new EstimationServicePart();
                            $estimationPart->estimation_id = $estimation->id;
                            $estimationPart->service_part_id = $parts[$i]['service_part_id'];
                            $estimationPart->quantity = $parts[$i]['quantity'];
                            $estimationPart->amount = $parts[$i]['amount'];
                            $estimationPart->description = $parts[$i]['description'];
                            $estimationPart->type = null;
                            $estimationPart->save();
                        }
                    }
                    $Inquiry->estimation_id = $estimation->id;
                }

            }
            if (!empty($request->wo_id)
                && !empty($request->wo_detail)
                && !empty($request->type)
                && !empty($request->workorder_due_date)
                && !empty($request->priority)
                && !empty($request->assign)) {
                $workOrder = WorkOrder::find($Inquiry->work_order_id);
                if ($workOrder) {
                    $workOrder->wo_id = $request->wo_id;
                    $workOrder->wo_detail = $request->wo_detail;
                    $workOrder->type = $request->type;
                    $workOrder->client = $request->client;
                    $workOrder->priority = $request->priority;
                    $workOrder->due_date = $request->workorder_due_date;
                    $workOrder->status = 'pending';
                    $workOrder->assign = $request->assign;
                    $workOrder->notes = !empty($request->notes) ? $request->notes : null;
                    $workOrder->preferred_date = !empty($request->preferred_date) ? $request->preferred_date : null;
                    $workOrder->preferred_time = !empty($request->preferred_time) ? $request->preferred_time : null;
                    $workOrder->preferred_note = !empty($request->preferred_note) ? $request->preferred_note : null;
                    $workOrder->save();

                    $services = !empty($request->services) ? $request->services : [];
                    $parts = !empty($request->parts) ? $request->parts : [];

                    if (!empty($services)) {
                        for ($i = 0; $i < count($services); $i++) {
                            $serviceId = isset($services[$i]['id']) ? $services[$i]['id'] : 0;
                            $woService = WOServicePart::find($serviceId);

                            if ($woService == null) {
                                $woService = new WOServicePart();
                                $woService->wo_id = $workOrder->id;
                            }
                            $woService->service_part_id = $services[$i]['service_part_id'];
                            $woService->quantity = $services[$i]['quantity'];
                            $woService->amount = $services[$i]['amount'];
                            $woService->description = $services[$i]['description'];
                            $woService->type = 'service';
                            $woService->save();
                        }
                    }

                    if (!empty($parts)) {
                        for ($i = 0; $i < count($parts); $i++) {
                            $woPart = WOServicePart::find(isset($parts[$i]['id']) ? $parts[$i]['id'] : 0);
                            if ($woPart == null) {
                                $woPart = new WOServicePart();
                                $woPart->wo_id = $workOrder->id;
                            }
                            $woPart->service_part_id = $parts[$i]['service_part_id'];
                            $woPart->quantity = $parts[$i]['quantity'];
                            $woPart->amount = $parts[$i]['amount'];
                            $woPart->description = $parts[$i]['description'];
                            $woPart->type = 'part';
                            $woPart->save();
                        }
                    }
                } else {
                    $workOrder = new WorkOrder();
                    $workOrder->wo_id = $request->wo_id;
                    $workOrder->wo_detail = $request->wo_detail;
                    $workOrder->type = $request->type;
                    $workOrder->client = $request->client;
                    $workOrder->priority = $request->priority;
                    $workOrder->due_date = $request->workorder_due_date;
                    $workOrder->status = 'pending';
                    $workOrder->assign = $request->assign;
                    $workOrder->notes = !empty($request->notes) ? $request->notes : null;
                    $workOrder->preferred_date = !empty($request->preferred_date) ? $request->preferred_date : null;
                    $workOrder->preferred_time = !empty($request->preferred_time) ? $request->preferred_time : null;
                    $workOrder->preferred_note = !empty($request->preferred_note) ? $request->preferred_note : null;
                    $workOrder->parent_id = parentId();
                    $workOrder->save();

                    $parts = !empty($request->parts) ? $request->parts : [];

                    if (!empty($parts)) {
                        for ($i = 0; $i < count($parts); $i++) {
                            $service = ServicePart::find($parts[$i]['service_part_id']);

                            $woService = new WOServicePart();
                            $woService->wo_id = $workOrder->id;
                            $woService->service_part_id = $parts[$i]['service_part_id'];
                            $woService->quantity = $parts[$i]['quantity'];
                            $woService->amount = $parts[$i]['amount'];
                            $woService->description = $parts[$i]['description'];
                            $woService->type = $service->type ?? 'part';
                            $woService->save();

                            foreach ($service->serviceTasks as $task) {
                                $WOServiceTask = new WOServiceTask();
                                $WOServiceTask->wo_id = $workOrder->id;
                                $WOServiceTask->service_part_id = $task->service_id;
                                $WOServiceTask->service_task = $task->task;
                                $WOServiceTask->duration = $task->duration;
                                $WOServiceTask->description = $task->description;
                                $WOServiceTask->status = 'pending';
                                $WOServiceTask->save();
                            }
                        }
                    }

                    $Inquiry->work_order_id = $workOrder->id;
                }
            }
            $Inquiry->save();

            return redirect()->route('inquiry.index')->with('success', __('Inquiry successfully updated.'));
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
        if (Auth::user()->can('delete inquiry')) {
            $Inquiry = Inquiry::with('estimation')->find($id);
            $EstimationController = new EstimationController();
            if ($Inquiry->estimation) {
                $estimationId = $EstimationController->destroy($Inquiry->estimation->id, true);
            }
            $Inquiry->delete();
            return redirect()->route('inquiry.index')->with('success', __('Inquiry successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function getClientVehical(Request $request)
    {
        $vehicals = Vehicle::where('client', $request->client_id)->get();
        $options = '<select name="vehicle" id="Vehicle" class="form-control basic-select" required="required">';
        foreach ($vehicals as $key => $vehical) {
            $options .= '<option value=' . $vehical->id . '>' . $vehical->name ?? null . ' - ' . $vehical->registration_no ?? null . '</option>';
        }
        $options .= '</select>';

        $user = User::where('parent_id', parentId())->find($request->client_id);
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'User Not Found!']);
        }
        $clienDetail = ClientDetail::where('user_id', $user->id)->first();
        $data = [];
        $data['html'] = $options;
        $data['address'] = !empty($clienDetail) ? $clienDetail->service_address : null;
        return response()->json(['status' => 1, 'data' => $data]);
    }
}
