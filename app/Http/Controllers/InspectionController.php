<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\GroupInspection;
use App\Models\Inspection;
use App\Models\TempInspection;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\vehicle_model;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use App\Models\Group_editable_point;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspections = Inspection::all();
        $customer = User::where('parent_id', parentId())->where('type', 'client')->get();
        $templates = TempInspection::get();
        return view('inspection.index',compact('inspections','customer','templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::where('type', 'client')->get();
        $vehicles=Vehicle::all();
        $templates=TempInspection::all();
        $groups=GroupInspection::all();
        $templates = TempInspection::get();
         $technicians = User::where('parent_id', parentId())->where('type', 'technician')->get()->pluck('id', 'full_name');
        $supervisors = User::where('parent_id', parentId())->where('type', 'supervisor')->get()->pluck('id', 'full_name');

        return view('inspection.create',compact('customers','templates','vehicles','templates','groups','technicians','supervisors'));
    }

        public function getBookings($customerId)
        {
            $bookings = Booking::where('client', $customerId)->get();
            return response()->json($bookings);
        }
        public function getcustomer($customerId)
        {
            $customer = User::find($customerId);
            return response()->json($customer);
        }
        public function getVehicleInfo($bookingId)
        {
            $booking = Booking::find($bookingId);
            $vehicle = $booking ? Vehicle::find($booking->vehicle) : null;
            $model = vehicle_model::find($vehicle->vm);
            $vehicle->vm = $model ? $model->model_name : null;
            return response()->json($vehicle);
        }
        public function getVehicle($vehicleId)
        {
            
            $vehicle =  Vehicle::find($vehicleId);
         
            return response()->json($vehicle);
        }
        public function getTemplateDetails(Request $request)
        {
            $template = TempInspection::find($request->temp);
            $groups_ids=json_decode($template->groups) ?? [];
            $groups= GroupInspection::with('editpoints')->whereIn('id',$groups_ids)->get() ?? [];
        
            $template->data =$groups;
            return response()->json($template);
        }
        public function updateStatus(Request $request, $id)
        {
            $user = Inspection::find($id);
            $user->status = $request->has('status') ? 1 : 0;   
            $user->save();

            if ($user->status) {
                return redirect()->back()->with('success', __('Inspection successfully activated.'));
            } else {
                return redirect()->back()->with('success', __('Inspection successfully deactivated.'));
            }
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { //dd($request->all());
        // $validatedData = $request->validate([
        //     'customer_id' => 'required',
        //     'booking_id' => 'required',
        //     'template_id' => 'required',
        //     'orders' => 'nullable|array',
        //     'orders.*.type' => 'nullable|string',
        //     'orders.*.comment' => 'nullable|string',
        //     'orders.*.estimated_hours' => 'nullable|numeric',
        //     'orders.*.estimated_cost' => 'nullable|numeric',
        //     'orders.*.estimated_product_price' => 'nullable|numeric',
        //     'orders.*.estimated_product_cost' => 'nullable|numeric',
        //     'orders.*.color' => 'nullable|string',
        // ]);
        // $inspection = new Inspection();
        // $inspection->customer = $validatedData['customer_id'];
        // $inspection->booking = $validatedData['booking_id'];
        // $inspection->vehicle = Booking::find($validatedData['booking_id'])->vehicle;
        // $inspection->template = $validatedData['template_id'];
        // $inspection->data = json_encode($validatedData['orders']);
        // $inspection->save();

        // if ($request->workorder_id) {
        //     $woids = $request->workorder_id;
        //     $workOrders = WorkOrder::where('id', $woids)->get();
        //     foreach ($workOrders as $workOrder) {
        //         $existingVehicles = json_decode($workOrder->inspections, true);
        //         $existingVehicles = $existingVehicles ?? [];
        //         $existingVehicles[] = $inspection->id;
        //         $uniqueVehicles = array_unique($existingVehicles);
        //         $workOrder->inspections = json_encode($uniqueVehicles);
        //         $workOrder->save();
        //     }
        //     return redirect()->route('workorder.edit', ['workorder' => $request->workorder_id]);
        // }
                // ]);
             //   dd($request->all());
        $groups= implode(',',$request->arraylistgroup);
        $inspection = new Inspection();
        $inspection->customer = $request->customer_id;
        $inspection->status=$request->status;
        $inspection->equipment = $request->vehicle;
        $inspection->technician = $request->technician;
        $inspection->supervisor = $request->supervisor;
        $inspection->groups=$groups;
        $inspection->customer_comment = $request->customer_comment;
        $inspection->technician_comment = $request->technician_comment;
        $inspection->advisior_comment = $request->advisior_comment;
        $inspection->save();

             return response()->json(['message' => 'created successfully '], 200);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inspection = Inspection::find($id);
                $inspection_id=$inspection?->id;
         $template = $inspection?->template;
        //dd($template);
        $groups= json_decode($inspection?->groups) ?? [];
        $groups= GroupInspection:: with(['mainpoints' => function ($query) use ($inspection_id) {
          $query->where('inspection_id', $inspection_id);
          }])->whereHas('mainpoints')->whereIn('id',$groups)->get();
    

        //dd($quotations);
        $customerDetails = User::with('clients')->findOrFail($inspection->customer);
        $vehicles = Vehicle::with('vehicle_models')->findOrFail($inspection->equipment);
      //  return view('inspection.show',compact('inspection','groups','quotations','customerDetails','vehicles'));
     return view('inspection.show-new',compact('customerDetails','inspection','groups'));
    }
    public function sendInspectEmail($id){
        $inspection=inspection::find($id);
        $inspection->status = "quote";
        $inspection->save();
          return response()->json(['success' => true, 'message' => 'inspection sent via email']);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = User::where('parent_id', parentId())->where('type', 'client')->get();
        $templates = TempInspection::get();
        return view('inspection.edit',compact('customers','templates'));
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
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'booking_id' => 'required',
            'template_id' => 'required',
            'orders' => 'nullable|array',
            'orders.*.type' => 'nullable|string',
            'orders.*.comment' => 'nullable|string',
            'orders.*.estimated_hours' => 'nullable|numeric',
            'orders.*.estimated_cost' => 'nullable|numeric',
            'orders.*.estimated_product_price' => 'nullable|numeric',
            'orders.*.estimated_product_cost' => 'nullable|numeric',
            'orders.*.color' => 'nullable|string',
        ]);
        $inspection = Inspection::find($id);
        $inspection->customer = $validatedData['customer_id'];
        $inspection->booking = $validatedData['booking_id'];
        $inspection->vehicle = Booking::find($validatedData['booking_id'])->vehicle;
        $inspection->template = $validatedData['template_id'];
        $inspection->data = json_encode($validatedData['orders']);
        $inspection->save();
        return redirect()->back()->with('success', 'Inspection updated successfully.');
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $groupInspection = Inspection::find($id);
        $groupInspection->delete();
        return redirect()->back()->with('success', 'Inspection  deleted successfully!');
    }
    public function cancel($id)
    {
        
        $groupInspection = Inspection::find($id);
        $groupInspection->delete();
        //dd($groupInspection);
        return redirect()->back()->with('success', 'Inspection  deleted successfully!');
    }
     public function confirminspection($id)
    {
        
        $groupInspection = Inspection::find($id);
        $groupInspection->status='approved';
        $groupInspection->save();
        //dd($groupInspection);
        return redirect()->back()->with('success', 'Inspection  confirmed successfully!');
    }

    public function groupindex()
    {
        $groups = GroupInspection::all();
        return view('inspection.group.index',compact('groups'));
    }

    public function groupcreate()
    {
        return view ('inspection.group.create');
    }
    public function groupstore(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:group_inspections,code',
            'description' => 'required|string|max:1000',
        ]);
        $groupInspection = new GroupInspection();
        $groupInspection->code = $validatedData['code'];
        $groupInspection->des = $validatedData['description'] ?? ''; 
        $groupInspection->save();
        return redirect()->back()->with('success', 'Group created successfully!');
   
    }
    public function groupedit($id)
    {
        $group = GroupInspection::find($id);
        return view ('inspection.group.edit',compact('group'));
    }
    public function groupupdate(Request $request,$id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
        $groupInspection = GroupInspection::find($id);
        $groupInspection->code = $validatedData['code'];
        $groupInspection->des = $validatedData['description'] ?? ''; 
        $groupInspection->save();
        return redirect()->back()->with('success', 'Group updated successfully!');
    }
    public function groupdestroy($id)
    {
        $groupInspection = GroupInspection::find($id);
        $groupInspection->delete();
        return redirect()->back()->with('success', 'Group deleted successfully!');
    }
    public function groupmainpoints($id)
    {
        $points = Group_editable_point::where('group_id',$id)->get();
        $group = GroupInspection::find($id);
        return view('inspection.group.main-point',compact('points','group'));
    }
  
    public function grouppoints($id)
    {
        $groupInspection = GroupInspection::find($id);
       // $points =$groupInspection->editpoints ?? []; 
        return view('inspection.group.points',compact('groupInspection'));
    }
    
     public function grouppoints2($id)
    {
        $groupInspection = GroupInspection::find($id);
        $points =$groupInspection->editpoints ?? []; 
        
            return response()->json($points, 200);
    } 
    public function storegrouppoints(Request $request,$id)
    { 
     //   dd($request->all());
    ////    $validator = \Validator::make(
    //     $request->all(),
    //     [
    //         'name' => 'required|array',
            
    //         'name.*' => 'required',
         
    //     ]
    // );

    // if ($validator->fails()) {
    //     $messages = $validator->getMessageBag();
    //     return redirect()->back()->with('error', $messages->first());
    // }
      // dd($request->all());
    //   $name=$request->name;
    //   $input=$request->input;
    //   $completed=$request->completed;
    //   $urgent=$request->urgent;
    //   $comment=$request->comment;
     // $pointsArray=[];
       $group = GroupInspection::find($id);
       if($group->editpoints->count() >1)
       {
         $group->editpoints()->delete();
       }
        foreach($request->all() as $key=> $name)
       {// dd($input[$key]);
    //    if($input&&in_array($key,$input))
    //     $pointsArray[]="input";
    //     if($completed&&in_array($key,$completed))
    //     $pointsArray[]="completed";
    //     if($urgent&&in_array($key,$urgent))
    //     $pointsArray[]="urgent";
    //     if($comment&&in_array($key,$comment))
    //     $pointsArray[]="comment";
           $point = new Group_editable_point ();
           $point->point_des=$name;
           $point->group_id=$id;
    //     $point->points=json_encode($pointsArray);
           $point->save();
    //     $pointsArray=[];

       }

        return redirect()->route('inspection.groupindex')->with('success', 'points saved successfully!');
    }
    public function storemainpoints()
    {
    
 return redirect()->route('inspection.index')->with('success', 'points saved successfully!');
    

     }
    public function templatesindex()
    {
        $templates = TempInspection::all();
        return view('inspection.templates.index',compact('templates'));
    }

    public function templatescreate()
    {
      //  $groupnames = GroupInspection::pluck('code');
        $groups=GroupInspection::all();
        return view ('inspection.templates.create',compact('groups'));
    }
    public function templatesstore(Request $request)
    {//
       // dd($request->all());
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:temp_inspections,code',
            'comment1' => 'nullable|string|max:255',
            'comment2' => 'nullable|string|max:255',
            'description' => 'required|string',
            'groups' => 'nullable|array',
            // 'groups.*.name' => 'required|string|max:255',
            // 'groups.*.orders' => 'nullable|array',
            // 'groups.*.orders.*.order' => 'nullable|integer',
            // 'groups.*.orders.*.type' => 'nullable|string|max:255',
            // 'groups.*.orders.*.description' => 'nullable|string|max:255',
            // 'groups.*.orders.*.product_code' => 'nullable|string|max:255',
            // 'groups.*.orders.*.estimated_time' => 'nullable|string|max:255',
            // 'groups.*.orders.*.comments' => 'nullable|string|max:255',
        ]);
        $template = new TempInspection();
        $template->code = $validated['code'];
//$template->cl1 = $validated['comment1'];
        $template->groups = json_encode($validated['groups']);
        $template->des = $validated['description'];
      //  $template->data = json_encode($validated['groups']);
        $template->save();
        return redirect()->route('inspection.templatesindex')->with('success', 'Template created successfully.');
   
   
    }
    public function templatesedit($id)
    {
        $groups= GroupInspection::all();
        $template = TempInspection::findOrFail($id);
        $template_groups= json_decode($template->groups) ?? [] ;
    //    $template->data = json_decode($template->data, true);
        return view ('inspection.templates.edit',compact('template','groups','template_groups'));
    }
    public function templatesupdate(Request $request,$id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'comment1' => 'nullable|string|max:255',
            'comment2' => 'nullable|string|max:255',
            'description' => 'required|string',
            'groups' => 'nullable|array',
          //  'groups.*.name' => 'required|string|max:255',
            // 'groups.*.orders' => 'nullable|array',
            // 'groups.*.orders.*.order' => 'nullable|integer',
            // 'groups.*.orders.*.type' => 'nullable|string|max:255',
            // 'groups.*.orders.*.description' => 'nullable|string|max:255',
            // 'groups.*.orders.*.product_code' => 'nullable|string|max:255',
            // 'groups.*.orders.*.estimated_time' => 'nullable|string|max:255',
            // 'groups.*.orders.*.comments' => 'nullable|string|max:255',
        ]);
        $template = TempInspection::find($id);
        $template->code = $validated['code'];
        $template->groups = $validated['groups'];
        //$template->cl1 = $validated['comment1'];
       // $template->cl2 = $validated['comment2'];
        $template->des = $validated['description'];
//$template->data = json_encode($validated['groups']);
        $template->save();
        return redirect()->route('inspection.templatesindex')->with('success', 'Template updated successfully.');
    }
    public function templatesdestroy($id)
    {
        $groupInspection = TempInspection::find($id);
        $groupInspection->delete();
        return redirect()->back()->with('success', 'Template deleted successfully!');
    }
    
}
