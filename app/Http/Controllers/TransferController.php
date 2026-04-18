<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\WarHouse;
use App\Models\InventoryDetail;
use App\Models\ServicePartadjustMent;
class TransferController extends Controller
{
   public function index()
   {
    $transfers=Transfer::all();
      return view('transfer.index',compact('transfers'));
   }
   public function create(){
    $warehouses=WarHouse::pluck('name','id');
    return view('transfer.create',compact('warehouses'));
   }
   public function store(Request $request){
   // dd($request->all());
    //   $validator = \Validator::make($request->all(), [
    //     'service_part_id' => 'required|exists:service_parts,id',
    //    'from_warehouse' => 'required|exists:war_houses,id',
    //    'to_warehouse' => 'required|exists:war_houses,id',
    //     'stock' => 'required|numeric',
    //    'qty_transfer' => 'required|numeric',
    //    'unit'=>'required|string',
    //    'description'=>'string|max:2000|min:4'
     
    // ]);

    // if ($validator->fails()) {
    //     $messages = $validator->getMessageBag();
    //     return redirect()->back()->with('error', $messages->first());
    // }

//dd($request->all());
    $transfer = new Transfer();
    $transfer->service_part_id= $request->service_part_id;
    $transfer->from_warehouse_id= $request->from_warehouse;
    $transfer->to_warehouse_id= $request->to_warehouse;
    $transfer->description= $request->description;
    $transfer->stock= $request->stock;
    $transfer->unit= $request->unit;
    $transfer->qty_transfer= $request->qty_transfer;
     
    $transfer->save();
     // dd($transfer);
 
   if($transfer)
   {
        $inventory_minus = new InventoryDetail([
        'service_part_id' => $request->input('service_part_id'),
     
        'quantity' =>'-'. $request->input('qty_transfer'),
        'location' => $request->input('from_warehouse'),
        'reference' => 'Transfer',
        'document' => 'TR'.$transfer->id,
        
    ]);
      $inventory_minus->save();
    $inventory_plus = new InventoryDetail([
        'service_part_id' => $request->input('service_part_id'),
     
        'quantity' =>'+'. $request->input('qty_transfer'),
        'location' => $request->to_warehouse,
        'reference' => 'Transfer',
        'document' => 'TR'.$transfer->id,
        
    ]);


    $inventory_plus->save();
    $from_adjustment=ServicePartadjustMent::where('service_part_id',$request->service_part_id)
    ->where('warehouse_id',$request->from_warehouse)->first();
   
    $from_adjustment->onhand-=$request->qty_transfer;
    $from_adjustment->save();
    $to_adjustment=ServicePartadjustMent::where('service_part_id',$request->service_part_id)
    ->where('warehouse_id',$request->to_warehouse)->first();
   if($to_adjustment)
   {
    $to_adjustment->onhand+=$request->qty_transfer;
    $to_adjustment->save();
   }
   else{
    $to_adjustment=ServicePartadjustMent::create([
     'service_part_id'=>$request->service_part_id,
     'warehouse_id'=>$request->to_warehouse,
     'onhand'=>$request->qty_transfer,
     'commited'=>0,
     'unavailable'=>0,
     'available'=>0

    ]);
   }
   }
    return response()->json(['message' => 'tranfer created successfuly']);
   
   }
   public function destroy($id){
    $transfer = Transfer::find($id);
    $transfer->delete();
    return redirect()->route('transfer.index')->with('success', __('Adjustment successfully deleted.'));

   }  
}
