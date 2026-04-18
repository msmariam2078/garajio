<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adjustment_item;
use App\Models\ServicePart;
use App\Models\ServicePartAdjustment;
use App\Models\InventoryDetail;
class AdjustmentItemController extends Controller
{
    public function index()
    {


       // if (\Auth::user()->can('manage adjustment')) {
            $adjustments = Adjustment_item::has('servicePart')->get();
            $serviceParts=ServicePart::all()->pluck('product_name','id');
            return view('adjustment_items.index', compact('adjustments','serviceParts'));

        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }

    public function create()
    {
       if (\Auth::user()->can('create adjustment')) {
         $serviceParts=ServicePart::all()->pluck('product_name','id');
            return view('adjustment_items.create',compact('serviceParts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        } 
    }


    public function store(Request $request)
    {


	// 	"_token" => "qnKqB1ryjtpohduYg2xJLINAYOTRQ6QB8R6irhm5"
	// 	"service_part_id" => "7"
	// 	"location" => "15"
	// 	"quantity" => "10"
	// 	"expir_day" => "2025-04-24"
	//   ]
   
     //if (\Auth::user()->can('create adjustment')) {
        $validator = \Validator::make($request->all(), [
        'service_part_id' => 'required|exists:service_parts,id|unique:adjustment_items,service_part_id',
       // 'unit_price' => 'required|string|max:10',
        'location' => 'required|string|max:255',
       'quantity' => 'required|numeric',
       'expir_day'=>'date|after:today'
     
    ]);

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }
    
    

       $adjustment = new Adjustment_item([
        'service_part_id' => $request->input('service_part_id'),
     'unit_price' => '0',
        'location' => $request->input('location'),
        'quantity' => $request->input('quantity'),
        'expir_day' => $request->input('expir_day'),
    ]);

    $adjustment->save();
    $inventory = new InventoryDetail([
        'service_part_id' => $request->input('service_part_id'),
     
        'quantity' => $request->input('quantity'),
        'location' => $request->input('location'),
        'reference' => 'adjustment',
        'expir_day' => $request->input('expir_day'),
        
    ]);

    $inventory->save();

    return redirect()->route('adjustment_item.index')->with('success', __('adjustment created successfully.'));
    //   } else {
    // return redirect()->back()->with('error', __('You do not have permission to create a adjustment.'));
    //    }
      }


    
     public function edit( $id){
    if (\Auth::user()->can('edit adjustment')) {
        $adjustment=Adjustment_item::find($id);
      if (!$adjustment) {
        return redirect()->back()->with('error', __('Adjustment not found.'));
    }
      $serviceParts=ServicePart::all()->pluck('product_name','id');
      return view('adjustment_items.edit',compact('adjustment','serviceParts'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    } 
      }


      public function update(Request $request,$id)
      {
      //  if (\Auth::user()->can('edit adjustment')) {
      
            $validator = \Validator::make($request->all(), [
           
                
               
                'location' => 'string|max:255',
               'quantity' => 'numeric',
               'expir_day'=>'date'
            ]);
    
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
      
            
            $adjustment = Adjustment_item::find($id);
            if (!$adjustment) {
                return redirect()->back()->with('error', __('Adjustment not found.'));
            }
    
          
    
            $adjustment->update($request->all());
            $inventory = new InventoryDetail([
                'service_part_id' => $adjustment->service_part_id,
             
                'quantity' => $adjustment->quantity,
                'location' => $adjustment->location,
                'reference' => 'adjustment',
                'expir_day' => $adjustment->expir_day,
                
            ]);
        
            $inventory->save();
               
    
            return redirect()->route('adjustment_item.index')->with('success', __('Service or Part updated successfully.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // } 
      }

    public function post(Request $request)
    {
    if (\Auth::user()->can('manage adjustment')) {
    $adjustment=Adjustment_item::find($request->id);

    $item= $adjustment->servicePart;
    $item->qty_on_hand=$adjustment->quantity;
    $item->quantity=$adjustment->quantity;
    $item->save();
    return redirect()->back()->with('success', __('posted successfuly!'));
    } else {
    return redirect()->back()->with('error', __('Permission Denied.'));
}
}
  public function post_all(Request $request)
  {
   // if (\Auth::user()->can('manage adjustment')) {
    $ids = $request->ids;
   
    foreach ($ids as $id){

        $adjustment=Adjustment_item::find($id);

        $item= $adjustment->servicePart;
       // $item->qty_on_hand=$adjustment->quantity;
        $item->quantity=$adjustment->quantity;
        $item->save();
        $adjustmentReport=ServicePartAdjustment::where('service_part_id',$item->id)->first();
        $adjustmentReport->onhand=$adjustment->quantity;
        $adjustmentReport->save();
    }
    return response()->json(['data' =>'posted successfuly!' ]);

// } else {
//     return redirect()->back()->with('error', __('Permission Denied.'));
// }
  }


  public function destroy($id)
    {
      //  if (\Auth::user()->can('delete adjustment_item')) {
            $adjustment = Adjustment_item::find($id);
            $adjustment->delete();
            return redirect()->route('adjustment_item.index')->with('success', __('Adjustment successfully deleted.'));
        
            // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}