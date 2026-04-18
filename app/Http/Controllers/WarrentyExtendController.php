<?php

namespace App\Http\Controllers;

use App\Models\Warrenty_extend;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ServicePart;
use App\Models\Vehicle;
class WarrentyExtendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $warrentyExtends=Warrenty_extend::has('servicePart')->get();
        $serviceParts=ServicePart::all()->pluck('product_name','id');
       // $clients=User::where('type','client')->get()->pluck('first_name','id');
        //$vehicles=Vehicle::get()->pluck('rego','id');
      return view('warrenty_extend.index',compact('warrentyExtends','serviceParts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$clients= collect();
        $vehicles=collect();

        // $vehicles=Vehicle::get()->pluck('rego','id');
        // $clients=User::where('type','client')->get()->pluck('first_name','id');
        $serviceParts=ServicePart::all()->pluck('product_name','id');
        return view('warrenty_extend.create',compact('serviceParts','clients','vehicles'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     // dd($request->all());
   
    
     //   if (\Auth::user()->can('create warrenty extend')) {
            $validator = \Validator::make($request->all(), [
                'customer_id' => 'exists:users,id',
                'product_id' => 'exists:service_parts,id',
                 'status' => 'in:0,1',

                'purchase_date'=> 'date',
        
                'coverage_type'=> 'string|max:100',
                'price' => 'string|max:100',
                'extend_start_date' => 'date',
                'extend_end_date' => 'date',
           
            ]);
    
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
    
            
            
            
    
            $warrent_extend = Warrenty_extend::create([
                'customer_id' => $request->input('customer_id'),
                'product_id' => $request->input('product_id'),
                   'status' => $request->input('status'),
                   'duration' => $request->input('duration'),
                'purchase_date' => $request->input('purchase_date'),
                'coverage_type' => $request->input('coverage_type'),
                'extend_start_date' => $request->input('extend_start_date'),
                'extend_end_date' => $request->input('extend_end_date'),
                
                'price' => $request->input('price'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            
    
            return redirect()->route('warrentyextend.index')->with('success', __('warrenty extend created successfully.'));
        // } else {
        //     return redirect()->back()->with('error', __('You do not have permission to create a service or part.'));
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warrentyextend   $warrentyextend 
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warrentyextend  $warrentyextend 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $serviceParts=ServicePart::all()->pluck('product_name','id');
        // $clients=User::where('type','client')->get()->pluck('first_name','id');
        $warrentyExtend=Warrenty_extend::find($id);
        $vehicles=Vehicle::get()->pluck('rego','id');

		$clients= collect();
        $vehicles=collect();
        return view('warrenty_extend.edit',compact('warrentyExtend' ,'clients','serviceParts','vehicles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warrentyextend  $warrentyextend 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            
    
     //   if (\Auth::user()->can('edit warrenty extend )) {
        $validator = \Validator::make($request->all(), [
            'customer_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:service_parts,id',
             'status' => 'required|in:0,1',

            'purchase_date'=> 'required|date',
    
            'coverage_type'=> 'required|string|max:100',
            'price' => 'required|string|max:100',
            'extend_start_date' => 'required|date',
            'extend_end_date' => 'required|date',
       
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        
        $warrentyExtend = Warrenty_extend::find($id);
        if (!$warrentyExtend ) {
            return redirect()->back()->with('error', __('warrenty extend not found.'));
        }

      

        $warrentyExtend->update($request->all());
           

        return redirect()->route('warrentyextend.index')->with('success', __('warrenty extend updated successfully.'));
    // }
     // else {
    //     return redirect()->back()->with('error', __('Permission Denied.'));
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warrentyextend  $warrentyextends
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //if (\Auth::user()->can('delete warrenty extend')) {
            $warrentyExtend= Warrenty_extend::find($id);
            $warrentyExtend->delete();
            return redirect()->route('warrentyextend.index')->with('success', __('warrenty extend successfully deleted.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}