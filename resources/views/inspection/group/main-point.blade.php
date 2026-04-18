@extends('layouts.master')

@section('page-header')
					<!-- breadcrumb -->
					<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Inspection Group <span class="text-primary"> {{$group->code}} </span? Points</h4>
						</div>
					</div>
				
				</div>
				<!-- breadcrumb -->
                 <style>
input[type="checkbox"] {
    width: 20px;
    height: 20px;
    appearance: none;
    
    border: 2px solid #eeeeee;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
}
.urgent:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}
.completed:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}
.fixed:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}
.green{ 
    background-color: #d0f0c0;
}
.yellow{ 
    background-color: #fcffa4;
}
.red{ 
    background-color: #ff9999;
}
.green:checked {
    background-color: green;
    border-color: green;
}

.red:checked {
    background-color: red;
    border-color: red;
}
.yellow:checked {
    background-color: yellow;
    border-color: yellow;
}

input[type="checkbox"]::after {
    content: '✔'; /* Unicode checkmark */
    font-size: 16px;
    color: white; /* Checkmark color */
    position: absolute;
    top: 0;
    left: 3px;
    display: none;
}

input[type="checkbox"]:checked::after {
    display: block;
}
</style>
@endsection
@section('content')
@include('messages_alert')
<form method="POST" action="{{route('inspection.groupmainpointsstore')}}">
@csrf 
<div class="card  " >
 <div class="card-body p-4  " >

    
    <div class="row  mb-4">
   
    <table id="newrow" class="table text-nowrap table-bordered table-hover abc2">
            <thead>
                <tr>
                 <!-- <th scope="col">Code</th> -->
                <th scope="col">Description</th>
                <th scope="col">Condition(E/G/F/P)</th>
                <th scope="col">Maintenance Required (Yes/No)</th>
                <th scope="col">Comment</th>
                    <th scope="col">Completed</th>
                    <th> Fixed Soon</th>
                    <th> Urgent</th>
                    <th>Red</th>
                    <th>yellow</th>
                    <th>Green</th>
                    
                

                    
                   
                </tr>
            </thead>
            <tbody >
             @foreach($points as $point)  
             <?php $e_point=json_decode($point->points) ?> 
            <tr class="text">
             <!-- <td><input type='text' name="" class="form-control" value="{{App\Models\GroupInspection::find($point->group_id)->code}}"readonly /></td>
              -->
             <td><input type='text' name="name[]" class="form-control" value="{{$point->point_des}}"readonly /></td>
                      
           <td>
             <select name="condition[]" id="" value=""class="form-control" >
             <option value=""></option>
             <option value="E">E</option>
             <option value="G">G</option>
             <option value="F">F</option>
             <option value="P">P</option>
             </select>
         </td>
      
          <td>

             <select name="maintenance[]" id="" class="form-control " >
             <option value=""></option>
             <option value="Yes">Yes</option>
             <option value="No">No</option>
             </select>
          </td>
             <!-- <td><input type="text"  name="condition[]" class="form-control" value="0" {{in_array('condition',$e_point ??[]) ? 'readonly'  :''}} /></td>
            <td><input type="textarea" name="maintenance[]" class="form-control" value="0" {{in_array('maintenance',$e_point ??[])  ? 'readonly'  :''}}/></td>
             -->
             <td><input type="textarea" name="comment[]" class="form-control" value="0" {{in_array('comment',$e_point ??[])  ? 'readonly'  :''}}/></td>
            
             <td><input type="checkbox" class="completed" name="completed[]" value="0" {{in_array('completed',$e_point ??[]) ? 'disabled'  :'enable'}}/></td>
             
             <td><input type="checkbox" class="fixed" name="urgent[]" value="0" {{in_array('urgent',$e_point ??[])  ? 'disabled'  :'enable'}}/></td>
             <td><input type="checkbox" class="urgent" name="ryb[]" value="0" {{in_array('urgent',$e_point ??[])  ? 'disabled'  :'enable'}}></td>
             <td><input type="checkbox" class="red" name="ryb[]" value="0" {{in_array('ryb',$e_point ??[])  ? 'disabled'  :'enable'}}/></td>
             <td><input type="checkbox" class="yellow" name="ryb[]" value="0" {{in_array('ryb',$e_point ??[])  ? 'disabled'  :'enable'}}/></td>
             <td><input type="checkbox" class="green" name="ryb[]" value="0" {{in_array('ryb',$e_point ??[])  ? 'disabled'  :'enable'}}/>
            
            </td> 
             
             </tr>
             @endforeach
            </tbody>
            </table>
   
</div>
<div class="row  mb-4 d-flex justify-content-end">
                <input class="btn btn-primary" type="submit" value="Save"/>
             </div>
</form>

</div>
</div>

				

				
		
@endsection
@section('js')


  

    <!--Internal  Notify js -->
    <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
@endsection