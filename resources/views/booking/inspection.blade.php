@extends('layouts.master')
@section('css')
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

@endsection
@section('page-header')
@endsection
@section('content')
<style>
            
  
  input[type="checkbox"] {
    width: 20px;
    height: 20px;
    appearance: none;
    
    border: 2px solid grey;
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
@include('messages_alert')
<div class="row">

    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                    <h5 class="card-title mb-0 customer-header">Customer Details</h5>

                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>

                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Client Type') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            <input type="hidden" value="{{  $clientDetails->id}}" id="customer_id" />
                            <td>{{ $clientDetails->first_name }} {{ $clientDetails->last_name }}</td>

                            <td>{{ $clientDetails->client_type }}</td>
                            <td>{{ $clientDetails->email }}</td>
                            <td>+{{preg_replace('/[^0-9]/', '',  $clientDetails?->ccm )}}{{ $clientDetails->phone_number }}</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="card-title mb-0">Equipments Details</h5>


                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>

                                <th>{{ __('Registration Number') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Make') }}</th>
                                <th>{{ __('Model') }}</th>


                            </tr>
                        </thead>
                        <tbody>

                            <tr>
							<input type="hidden" name="booking_id" value="{{$bookings->id}}" id="booking_id" />
                            <input type="hidden" name="equipment_id" value="{{$vehicles->id}}" id="equipment_id" />
                                <td>{{ $vehicles->rego }} </td>
                                <td>{{ $vehicles->name }} </td>
                                <td>{{ @$vehicles->vehicle_makes?->make_name }} </td>
                                <td>{{ @$vehicles->vehicle_models?->model_name }} </td>





                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</div>
@if(!$inspection)
  <div class="row">

    <div class="col-xl-12">
                   <div class="card-header">
                    <div class="card-title">Inspection Details

                    
                        
                         
                              <select class="float-right form-control w-50" id="inspection-temp">
                                <option value=''>select A template</option>
                                @foreach($templates as $template)
                                <option value="{{$template->id}}">{{$template->code}}</option>
                              @endforeach
                              </select>
                         </div>
                  
                </div>
  </div>
  @endif

  </div>

<div class="row">

    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                    <h5 class="card-title mb-0 customer-header">Template Details</h5>

                </div>
            </div>
            <div class="card-body">

               <div class="card-body " id="temp-details">
                   <div class="row">							
                    <!-- <input type="hidden" name="booking_id" value="{{$bookings->id}}" id="booking_id" /> -->
                    <input type="hidden" name="inspection_id" value="{{$inspection?->id}}" id="inspection_id" />
                       <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Template Name</label>
                            <input type="text" class="form-control" id="temp-name" value="{{$template?->code}}" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Template Description</label>
                            <input type="text" class="form-control" id="temp-des" value="{{$template?->des}}" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Date</label>
                            <input type="date" class="form-control" id="temp-date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Vehicle Fault</label>
                            <input type="text" class="form-control" id="v_flaut" value="" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Diagnostic Proccess</label>
                            <input type="text" class="form-control" id="d_proccess" value="" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Machine Hours</label>
                            <input type="text" class="form-control" id="m_hour" value="{{$inspection?->machine_hours}}" >
                        </div>
                   </div>
                </div>
    </div>
    <div id="mainAccordion" class="col-xl-12 accordion"></div>
@if($inspection)
    <div class="col-xl-12">
        <div class="card ">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                    <h5 class="card-title mb-0 customer-header">Group Details</h5>

                </div>
            </div>
            <div class="card-body">

                <div id="mainAccordion" class="accordion">
                    @foreach($groups as $key => $group)
                     <div class="card">
                        <div class="accor bg-primary text-white" id="heading{{$key}}">
                         <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                          {{$group->code}}
                        </button>
                       </h2>
                     </div>

                 <div id="collapse{{$key}}" class="collapse" aria-labelledby="heading{{$key}}" data-parent="#mainAccordion">
        <div class="pointss">
        <div class="row pt-3">
         <div class=" col-5 text-center">
            <label for="" class="">Description</label>
          </div>
          <div class=" col-1 text-center" >
            <label for="" class="font-weight-bold">Condition (E/G/F/P)</label>
          </div>
      
          <div class=" col-1 text-center">
            <label for="" class="">Maintenance Required </label>
          </div>
           <div class=" col-2 text-center">
            <label for="" class="">Comment</label>
          </div>
          <div class=" col-1 text-center">
          <label for="" class="">Completed</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Fixed Soon</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Urgent</label>
            </div>
      
                </div>
        
            
            @foreach($group->mainpoints as $e=> $point) 
        <div class="row p-2">
        <div class="col-5 text-center">
             <input type="hidden" name="point_group_id" id="" value="{{$group->id}}"class="form-control w-100" readonly>
            <input type="hidden" name="point_id" id="" value="{{$point->id}}"class="form-control w-100" readonly>
             <input type="text" name="point_name" id="" value="{{$point->point_name}}"class="form-control w-100" readonly>
          </div>
        
                <div class="col-1 text-center">
           
             <select name="point_condition" id="" value=""class="form-control w-100 point_comment" >
            <option value=""  ></option>
             <option value="E"  {{$point->condition=='E' ?'selected' : ''}}>E</option>
             <option value="G" {{$point->condition=='G' ?'selected' : ''}}>G</option>
             <option value="F" {{$point->condition=='F' ?'selected' : ''}}>F</option>
             <option value="P"  {{$point->condition=='P' ?'selected' : ''}}>P</option>
             </select>
          </div>
      
          <div class=" col-1 text-center">

             <select name="point_maintenance" id="" class="form-control w-100 point_input" >
                <option value=""></option>
             <option value="Yes" {{$point->maintenance_required=='Yes' ?'selected' : ''}}>Yes</option>
             <option value="No"  {{$point->maintenance_required=='No' ?'selected' : ''}}>No</option>
             </select>
          </div>
        <div class="col-2 text-center">
           
             <input type="text" name="point_comment" id="" value="{{$point->comment}}"class="form-control w-100" >
          </div>
       
           
          <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_completed" {{$point->completed=='true' ||$point->completed==1 ? 'checked' : ''}}  value="0"  />
            </div>
               <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_fixed" value="0" {{$point->soon=='true' || $point->soon==1? 'checked' : ''}}   />
            </div> 
              <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_urgent" value="0" {{$point->urgent =='true' || $point->urgent==1? 'checked' : ''}}  />
            </div>
            <!-- <div class=" col-1 text-center">

            <input type="checkbox" class="red" name="point_red" value="0" {{$point->red=='true' ||$point->red==1 ? 'checked' : ''}} />
            </div>
              <div class=" col-1 text-center">
 
            <input type="checkbox" class="yellow" name="point_yellow" value="0" {{$point->yellow =='true' ||$point->yellow==1? 'checked' : ''}}   />
            </div>
              <div class=" col-1 text-center">
         
            <input type="checkbox" class="green" name="point_green" value="0" {{$point->green=='true' ||$point->green==1 ? 'checked' : ''}}   />
            </div> -->
                </div>  
                           
                            @endforeach
          

                        </div>
                           
                   </div>
            
            </div>

             @endforeach

              
            </div>
      </div>
      <div>
      <button class="btn btn-primary float-right mr-3" id="saveinspection" onclick="updatebookinginspection()">Save</button>
    </div>  
    </div>
</div>

    <div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                    <h5 class="card-title mb-0 customer-header">Inspection</h5>

                </div>
            </div>
            <div class="card-body">
                 <div class="card mb-0" >
                        <div class="card-header" id="" class="text-black " style="font-size:16px;background:#eeeeee">
                            
                                Inspection ID: {{ $inspection->id}} - Status: @if($inspection->status=="open")Open 
                                @elseif($inspection->status=="quote")Quotation
                                @elseif($inspection->status=='approved')Approved
                                @endif
                        
                        </div>
                         <div class="d-flex justify-content-end mt-3">
                                 @if($inspection->status!=="approved")   
                                    <a type="button"  class="btn btn-outline-danger text-danger me-2 customModal"
                                        data-url="{{ route('quotation.cancel', [$inspection->id,'ins']) }}">
                                        <i class="ri-close-line"></i> Cancel
                                  </a>&nbsp;
                                    <a href="{{ route('inspection.show', $inspection->id) }}"
                                        class="btn btn-outline-success me-2">
                                        <i class="ri-share-line"></i> Share
                                    </a>&nbsp;
                                  
                                     <a href="{{ route('inspection.confirm', $inspection->id) }}"
                                        class="btn btn-outline-success">
                                        <i class="ri-check-line"></i> Approve
                                    </a>
                                    @endif
                         
                                </div>
    
                                <div class="btn-list mt-4 d-flex justify-content-end">
                                                 <a class="btn btn-primary me-2" href="{{route('booking.edit',$bookings->id)}}">
                                                        Quotation
                                                </a>&nbsp;
                                <button type="button" class="btn btn-primary customModal"
                                         data-url="{{ route('quotation.cancel', [$inspection->id,'ins']) }}">
                                          Cancel
                               </button>
                              
                </div>
            </div>

   </div>
   @endif
  @if(!$inspection)
  <div class="col-sm-12">

                        <div class="justify-content-center ">
                            <a class="btn btn-primary ms-2 text-white float-right" id="submitBookingInspection"
                                onclick="submitBookingInspection()">
                                Add Inspection
                            </a>
                        </div>
  
    </div>
    @endif
            
<script>
               
     let insp =document.getElementById("inspection-temp");
     let name=document.getElementById('temp-name');
     let des=document.getElementById('temp-des');
     let date=document.getElementById('temp-date');
     let template;


     insp.addEventListener('change',  function() {
     document.getElementById("temp-details").classList.remove("d-none");
      const mainAccordion = document.getElementById('mainAccordion');
     mainAccordion.innerHTML='';
     let temp = this.value;
                $.ajax({
                    url: '/fetch-template-details?temp='+temp,
                    method: 'GET',
                    success: function(data) {
                    template=data;
                    console.log(template);
                    name.value=data.code || 'n/A';
                    des.value=data.des || 'n/A';
                     let d = new Date();
                    const yyyy = d.getFullYear();
                       const mm = String(d.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                       const dd = String(d.getDate()).padStart(2, '0');
                       date.value = `${yyyy}-${mm}-${dd}`;
                    // const datetime= date.value=data.created_at;
                    // if(datetime)
                    // {
                    //      const dateObj = new Date(datetime);
                    //      const dateOnly = dateObj.toISOString().split('T')[0];
                    //      date.value=dateOnly;
                    // }
                    // // const save =document.getElementById('savebutton');
                    // save.classList.remove('d-none');
                   
                    

  data.data.forEach((mainItem, mainIndex) => {
    // Create main card
    const mainCard = document.createElement('div');
    mainCard.classList.add('card');

    // Card header for main accordion item
    const mainHeader = document.createElement('div');
    mainHeader.classList.add('accor','bg-primary');
    mainHeader.id = `headingMain${mainIndex}`;

    mainHeader.innerHTML = `
      <h5 class="mb-0 d-flex justify-contnent-between">
        <button class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseMain${mainIndex}" aria-expanded="false" aria-controls="collapseMain${mainIndex}">
         <span class="pr-4">${mainIndex+1}</span> ${mainItem.code}
              
        </button>
        

      </h5>
    `;

    // Collapse container for main accordion item
    const mainCollapse = document.createElement('div');
    mainCollapse.id = `collapseMain${mainIndex}`;
    mainCollapse.classList.add('collapse');
   // if (mainIndex === 0) mainCollapse.classList.add('show'); // open first by default
    mainCollapse.setAttribute('aria-labelledby', `headingMain${mainIndex}`);
    mainCollapse.setAttribute('data-parent', '#mainAccordion');

    // Nested accordion container inside mainCollapse
    const nestedAccordion = document.createElement('div');
    nestedAccordion.id = `nestedAccordion${mainIndex}`;
    nestedAccordion.classList.add('accordion');
    const nestedCard = document.createElement('div');
    nestedCard.classList.add('card','mt-3','p-2','bg-secondary-transparent','points');
          nestedCard.innerHTML = `<div class="row ">
         <div class=" col-5 text-center">
            <label for="" class="">Description</label>
          </div>
          <div class=" col-1 text-center" >
            <label for="" class="font-weight-bold">Condition (E/G/F/P)</label>
          </div>
      
          <div class=" col-1 text-center">
            <label for="" class="">Maintenance Required </label>
          </div>
           <div class=" col-2 text-center">
            <label for="" class="">Comment</label>
          </div>
          <div class=" col-1 text-center">
          <label for="" class="">Commpleted</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Fixed Soon</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Urgent</label>
            </div>
          
                </div>`;
    // Loop through nested items
    mainItem.editpoints.forEach((nestedItem, nestedIndex) => {


      

    //   const nestedHeader = document.createElement('div');
    //   nestedHeader.classList.add('accor');
    //   nestedHeader.style.background='#eeeeee';
    //   nestedHeader.id = `headingNested${mainIndex}_${nestedIndex}`;

    //   nestedHeader.innerHTML = `
    //     <h6 class="m-0 p-0">
    //       <button class="btn btn-link m-0 pt-1 pl-3" data-toggle="collapse" data-target="#collapseNested${mainIndex}_${nestedIndex}" aria-expanded="false" aria-controls="collapseNested${mainIndex}_${nestedIndex}">
    //        <span class="pr-4">${nestedIndex+1}</span>${nestedItem.point_des}
    //       </button>
    
     
    //     </h6>
         
    //   `;

    //   const nestedCollapse = document.createElement('div');
    //   nestedCollapse.id = `collapseNested${mainIndex}_${nestedIndex}`;
    //   nestedCollapse.classList.add('collapse');
    //   nestedCollapse.setAttribute('aria-labelledby', `headingNested${mainIndex}_${nestedIndex}`);
    //   nestedCollapse.setAttribute('data-parent', `#nestedAccordion${mainIndex}`);


      nestedCard.innerHTML += `<div class="row p-2">
        <div class="col-5 text-center">
            <input type="hidden" class="point_id" name="point_group_id" value="${mainItem.id}"/>
            <input type="hidden" class="point_id" name="point_id" value="${nestedItem.id}"/>
             <input type="text" name="point_name" id="" value="${nestedItem.point_des}"class="form-control w-100" readonly>
          </div>
         
      
               <div class="col-1 text-center">
           
             <select name="point_condition" id="" value=""class="form-control w-100 point_comment" >
             <option value=""></option>
             <option value="E">E</option>
             <option value="G">G</option>
             <option value="F">F</option>
             <option value="P">P</option>
             </select>
          </div>
      
          <div class=" col-1 text-center">

             <select name="point_maintenance" id="" class="form-control w-100 point_input" >
             <option value=""></option>
             <option value="Yes">Yes</option>
             <option value="No">No</option>
             </select>
          </div>
           <div class="col-2 text-center">
           
             <input type="text " name="point_comment" id="" value=""class="form-control w-100" >
          </div>
            
           
          <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_completed" value="0"  />
            </div>
               <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_fixed" value="0"  />
            </div>
               <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed" name="point_urgent" value="0"  />
            </div>
        
                </div>`;

    //  nestedCard.appendChild(nestedHeader);
    //  nestedCard.appendChild(nestedCollapse);

    });
    nestedAccordion.appendChild(nestedCard);
    mainCollapse.appendChild(nestedAccordion);
    mainCard.appendChild(mainHeader);
    mainCard.appendChild(mainCollapse);

    mainAccordion.appendChild(mainCard);
  });
      
                    }
            });
            });

        function collectPoints(classname)
            {const points = [];
                const accordionRoot = document.querySelectorAll(`.${classname} .row`);
                accordionRoot.forEach(e=>{
                const inputs = e.querySelectorAll('input , select');
                
                let point={};
                inputs.forEach(input => {
                
                 const key = input.name ;
  
               if (input.matches("select")) {
                   point[key] =  input.value;
                          }
                          else if (input.type === 'checkbox' ) {
                 point[key] = input.checked;
                          } else {
                     point[key] = input.value;
                }
                if(point[key]==='point_red'){}
                 
                    });
                    points.push(point);
                });
                 return points;
            }
    
    function submitBookingInspection() {

       if (!insp.value) {
        alert("Please select a atemplate first");
        return;
        }
    $.ajax({
        url: '{{ route('bookinginspection.store') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            booking_id: $("#booking_id").val(),
            customer_id: $("#customer_id").val(),
            equipment: $("#equipment_id").val(),
            template:$("#inspection-temp").val(),
            points:collectPoints("points")

        },
        success: function(response) {
            console.log(response);
            alert('Inspection Updated Successfully');
            location.reload();

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('There was an error submitting the booking inspection.');
        }
    });
}
 function updatebookinginspection() {


    $.ajax({
        url: '{{ route('updatebookinginspection.store') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            inspection_id: $("#inspection_id").val(),
            v_flaut: $("#v_flaut").val(),
            m_hour: $("#m_hour").val(),
            d_proccess:$("#d_proccess-temp").val(),
            points:collectPoints("pointss")

        },
        success: function(response) {
            console.log(response);
            alert('Inspection Updated Successfully');
            location.reload();

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('There was an error submitting the booking inspection.');
        }
    });
}
            </script>
            @endsection

     
   
         
      