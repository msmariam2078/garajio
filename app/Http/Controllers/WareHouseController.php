<?php

namespace App\Http\Controllers;

use App\Models\WarHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WareHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('warehouse sidebar')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        $warHouses = WarHouse::with('user')->where(function($query) {
            $query->where('isDeleted', false)
                  ->orWhereNull('isDeleted');
        })->get();
        
          
        $country = User::$country;
        $country_code_s = settings()['country_code'] ?? 'default_code';
        $country_s = settings()['country'] ?? 'default_country';
    
        $technicians = User::with('clients')
          //  ->where('parent_id', parentId())
            ->where('type', 'technician')
            ->doesntHave('warehouse')
            ->get()
            ->mapWithKeys(function ($client) {
                return [$client->id => $client->full_name . ' - ' . $client->email . ' - ' . $client->phone_number];
            });
           
        return view('war_house.index',compact('warHouses','technicians','country_s','country_code_s','country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('create warehouse')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    
        $country = User::$country;
        $country_code_s = settings()['country_code'] ?? 'default_code';
        $country_s = settings()['country'] ?? 'default_country';
    
             $technicians = User::with('clients')
       // ->where('parent_id', parentId())
        ->where('type', 'technician')
       // ->whereDoesntHave('warehouse')
        ->get();
       // dd($technicians);
    
        return view('war_house.create', [
            'country' => $country,
            'technicians' => $technicians,
            'country_code_s' => $country_code_s,
            'country_s' => $country_s
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
       
        if (!Auth::user()->can('create warehouse')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

      

        WarHouse::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'description' => $request->description,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'type' => $request->fleet_type,
            'technicians' => $request->technician,
            'parent_id' => parentId()
        ]);

        return redirect()->back()->with('success', __('Ware House Created Successfully!')); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('warehouse sidebar')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
     

        $warehouse = WarHouse::findOrfail($id);
        $technicians = User::with('clients')
       // ->where('parent_id', parentId())
        ->where('type', 'technician')
        ->whereDoesntHave('warehouse',function ($query) use ($warehouse) {
         $query->whereNot('technicians',$warehouse->technicians);
})
        ->get()
        ->mapWithKeys(function ($client) {
            return [$client->id => $client->full_name . ' - ' . $client->email . ' - ' . $client->phone_number];
        });
      //  dd($technicians);
        
        return view('war_house.edit',compact('warehouse','technicians'));
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
        // dd($request->all());
        if (!Auth::user()->can('edit warehouse')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        // $validator = Validator::make($request->all(), [
        //     'name'          => 'required|max:255',
        //     'city'        => 'required|max:255',
        //     'state'        => 'required|max:255',
        //     'country'        => 'required|max:255',
        //     'zip_code'        => 'required|max:255',
        // ]);
        // if ($validator->fails()) {
        //     $messages = $validator->getMessageBag();
        //     return redirect()->back()->with('error', $messages->first());
        // }

        $warehouse = WarHouse::findorfail($id);
        if(!$warehouse){
            return redirect()->back()->with('error', __('Ware House Not Found!'));
        }
        // $technicians=WarHouse::pluck('technicians')->toArray();
       // dd($technicians);

       // dd(in_array($request->technicians,$technicians));

	   $technicians = WarHouse::where('id', '!=', $id)->pluck('technicians')->toArray();

		if (in_array($request->technicians, $technicians)) {
			return redirect()->back()->with('error', __('This Technician is already Taken!'));
		}

        //  if(in_array($request->technicians,$technicians)){
        //         return redirect()->back()->with('error', __('This Technician is already Taken!'));

        //  }

        $warehouse->update(
            [
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'zip_code' => $request->zip_code,
                'type' => $request->fleet_type,
                'technicians' => $request->technicians,
                'description' => $request->description,
            ]
        );
        return redirect()->back()->with('success', __('Ware House Updated Successfully!')); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('delete warehouse')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }


        $warehouse = WarHouse::find($id);

        if(!$warehouse){
            return redirect()->back()->with('error', __('Ware House Not Found!'));
        }

        $warehouse->delete();

        return redirect()->back()->with('success', __('Ware House Deleted Successfully!')); 
    }

    public function getWarehouse(Request $request)
    {
        $warehouse = WarHouse::where('parent_id',parentId())->find($request->ware_house_id);
        if($warehouse){
            return response()->json(['status'=>1,'data'=>$warehouse]);
        }
        return response()->json(['status'=>0,'msg'=>'Ware House Not Found!']);
    }
}