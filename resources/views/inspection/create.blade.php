@extends('layouts.master')

@section('page-header')
<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">create Inspection Sheet</h4>
						</div>
					</div>
					
				
				</div>
@endsection
@section('content')
@include('messages_alert')
<div class="card  " >
      <div class="card-body p-4  " >
<form method="POST" action="{{ route('inspection.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="h5 bold mb-4 pb-1" style="border-bottom:2px solid">
          General
          </div>
<div class="row d-flex justify-content-between mb-3">

<div class="col-md-6 col-12 ">

         
      
          
             <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Customer No</label>
                  <select id="customer" name="customer" class="form-control w-75 " onchange="fetchcustomer()" required>
                     <option value="">Select a Customer</option>
                     @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">#cus-{{ $customer->id}}</option>
                     @endforeach
                  </select>
        
           </div>
           <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Name</label>
                <input type='text' name='name' id="name" class="form-control w-75" readonly/>
        
           </div>
        
</div>
<div class="col-md-6 col-12 ">

         
      
<div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Email</label>
                  <input type='text' name='email' id="email" class="form-control w-75" readonly/>
        
           </div>   
<div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Phone</label>
                  <input type='text' name='phone' id="phone" class="form-control w-75" readonly/>
        
</div>

</div>
<div class="col-md-6 col-12 ">

         
      
          
             <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="status" class='form-label'>Status</label>
                  <select id="status" name="status" class="form-control w-75 "  required>
                  
                        <option value="Open">Open</option>
                        <option value="quote">Quote</option>
                        <option value="approved">Approved</option>
                     
                  </select>
        
           </div>
   
  
     

</div>
   <div class="col-md-6 col-12 ">
                   <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="status" class='form-label'>Technician</label>
                  <select id="technician" name="technician" class="form-control w-75 "  required>
                  
                      @foreach($technicians as $name => $id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                     
                  </select>
        
           </div>
           </div>
              <div class="col-md-6 col-12 ">
                   <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="status" class='form-label'>Supervisor</label>
                  <select id="supervisor" name="supervisor" class="form-control w-75 "  required>
                  
                        @foreach($supervisors as $name => $id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach 
                     
                  </select>
        
           </div>
           </div>

</div>
<div class="h5 bold mb-4 pb-1" style="border-bottom:2px solid">
          Equipment Information
          </div>
<div class="row d-flex justify-content-between ">

<div class="col-md-6 col-12 ">

         
      
          
             <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Registration No</label>
                  <select  name="equipment" class="form-control w-75 " id="vehicle" onchange="fetchVehicleInfo()" required>
                           <option value="">Select Equipment</option>
                     @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->rego }}</option>
                     @endforeach
                  </select>
        
           </div>
           <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Equipment ID</label>
                <input type='text' name='id' class="form-control w-75" id="vehicle-id" readonly/>
        
           </div>
           <div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Make</label>
                <input type='text' name='make' class="form-control w-75" id="vehicle-make" readonly/>
        
           </div>
        
        
</div>
<div class="col-md-6 col-12 ">

<div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Model</label>
                  <input type='text' name='model' class="form-control w-75" id="vehicle-model" readonly/>
        
           </div>     
      
          
<div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>Odometer</label>
                  <input type='text' name='odometer' id="vehicle-odometer" class="form-control w-75" readonly/>
        
</div>
<div class="form-group d-flex justify-content-between align-items-center">
                  <label for="customer" class='form-label'>VIN</label>
                  <input type='text' name='vin' id="vehicle-vin" class="form-control w-75" readonly/>
        
</div>

</div>
</div>
<div class="h5 bold mb-4 pb-1" style="border-bottom:2px solid">
          Comments
          </div>
<div class="row d-flex justify-content-between mb-3">

<div class="col-md-6 col-12 ">
<div class="form-group d-flex justify-content-between align-items-center">
         
<textarea type='text' name='customer_comment' id="customer_comment" class="form-control " placeholder="Customer Comment" ></textarea>
                 
</div>
<div class="form-group d-flex justify-content-between align-items-center">
         
                 
<textarea type='text' name='technician_comment' id="technician_comment" class="form-control " placeholder="Technician Comment" ></textarea>     
</div>
</div>
<div class="col-md-6 col-12 ">

<div class="form-group d-flex justify-content-between align-items-center">
         
                 
 <textarea type='text' name='advisior_comment' id="advisior_comment" class="form-control " placeholder="Service Advisior Comment" ></textarea>     
</div>
</div>
</div>
<div class="h5 bold mb-4 pb-1" style="border-bottom:2px solid">
         Inspection Group And Template
</div>
<div class="row mb-3">
   <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered">
                            <thead>
                              <tr>
                                 <!-- <th>
                                 Action
                                </th> -->
                                <th>
                                 Inspection Template 
                                </th>
                                <th>
                                 Inspection Group 
                                </th>
                              </tr>
                           </thead>
                           <tbody id="newrow">
                             <td>
                              <select id="templates" class="form-control">
                                 <option>Select A Template</option>
                                 @foreach($templates as $template)
                                  <option value="{{$template->id}}">{{$template->code}}</option>
                                 @endforeach
                              </select>
                             </td>
                             <td id="selectedgroup">
                              <select id="groups" class="form-control">
                              <option>Select A Group</option>
                              </select><br>
                              <div id="onegroup" class="list-group-item d-flex justify-content-between"></div>
                             </td>
                           </tbody>
                           
                              
                       </table>
                       <!-- <button type="button" class="btn btn-sm btn-success ml-4"
                            id="addrow">Add Row
                        </button> -->
                       
                     </div>
                        


</div>
</div>
<div class="row px-3">
<input type="submit" id="submit" value="Submit" class="btn btn-primary"/>
</div>

</form>
</div>
</div>


<script>
var arraylist= [];
//  function addtolist()
//  {

// const template= document.getElementById('template');
// const selectedTemplate = document.getElementById('selectedtemplate');
// var text=template.options[template.selectedIndex].text;
//  if(!arraylist.includes(text))
//      {
//       arraylist.push(text);
//       var li = document.createElement("li");
//       li.classList.add("list-group-item");
//       li.appendChild(document.createTextNode(text));
//      selectedTemplate.appendChild(li);
//      }
// var arraylistgroup= [];
//  function addtolistgroup()
//  {

const group= document.getElementById('groups');
const selectedgroup = document.getElementById('selectedgroup');
 let div= document.getElementById("onegroup");
selectedgroup.addEventListener('change', function() {
   div.innerHTML='';
var text=group.options[group.selectedIndex].text;
var atext = document.createTextNode("view point");
var id=group.options[group.selectedIndex].value;

 if(!arraylist.includes(id))
     {
     arraylist.push(id);
     console.log(arraylist);
     }
     
      var a = document.createElement("a");
       div.appendChild(document.createTextNode(text));
      a.href="/groupmainpoint/inspection/"+id;
      a.appendChild(atext);
      div.appendChild(a);

     
     // div.classList.add("list-group-item" , "d-flex", "justify-content-between");
      
   //   selectedgroup.appendChild(div);
     
});
       //  let templates = @json($templates);
            // let groups = @json($groups);
            // const tableBody = document.getElementById('newrow');
            // document.getElementById('addrow').addEventListener('click', async function() {
            //     const newRow = document.createElement('tr');
               // let tdzero = document.createElement('td');
                //let tdone = document.createElement('td');
               //  let tdtwo = document.createElement('td');
               //  selectone = document.createElement('select');
               //  selecttwo = document.createElement('select');
               //  var atext = document.createTextNode("Delete");
               //  button =  document.createElement('button');
               //  button.classList.add("delete","btn-danger");
               //  button.appendChild(atext);
               //  selectone.classList.add("form-control");
               //  selecttwo.classList.add("form-control");
               //  selecttwo.classList.add("add");
               //  selecttwo.id="one";
               //  let op= document.createElement('option');
               //  op.innerHTML="Select A Template";
               //  selectone.appendChild(op);
               //  op=document.createElement('option');
               //  op.innerHTML="Select A Group";
               //  selecttwo.appendChild(op);
               //  var atext = document.createTextNode("view point");
               //  templates.forEach(function(el){
               //    var opt = document.createElement('option');
               //    opt.value = el.id;
               //   opt.innerHTML = el.code;
               //   selectone.appendChild(opt);
               //  });
           
       
               // tdzero.appendChild(button);
               //  tdone.appendChild(selectone);
               //  tdtwo.appendChild(selecttwo);
               // // newRow.appendChild(tdzero);
               //  newRow.appendChild(tdone);
               //  newRow.appendChild(tdtwo);

               //  tableBody.appendChild(newRow);
                  document.getElementById('templates').addEventListener('change',  function() {
                  $.ajax({
                    url: '/fetch-template-details?temp='+this.value,
                    method: 'GET',
                    success: function(data) {
                     data.data.forEach(function(el){
                     var opt = document.createElement('option');
                    opt.value = el.id;
                    opt.innerHTML = el.code;
                     document.getElementById('groups').appendChild(opt);
                });

                    }
                   });
                });
//                 handleRowDelete();
//                 handleRowChange();
//   });
      
  
        function handleRowChange() {
            document.querySelectorAll('.add').forEach(function(button) {
                button.addEventListener('change', function() {
                   arraylistgroup.push(this.value);
                    const aElement = this.closest('a');
                    if (aElement) {
                         aElement.href="/groupmainpoint/inspection/"+this.value;// Removes the <tr> from the DOM
                          }
                     else{
               var atext = document.createTextNode("view point");
               let  a = document.createElement("a");
               a.href="/groupmainpoint/inspection/"+this.value;
               a.appendChild(atext);
               this.parentNode.insertBefore(a, this.nextSibling);}
               
                });
            });
        }
         function handleRowDelete() {
            document.querySelectorAll('.delete').forEach(function(button) {
                button.addEventListener('click', function() {
                   const trElement = this.closest('tr');
                    if (trElement) {
                        trElement.remove(); // Removes the <tr> from the DOM
                          }
                });
            });
        }
     function collectFormData() {
                let formData = {
                    customer_id: $('#customer').val(),
                    status: $('#status').val(),
                    technician: $('#technician').val(),
                    supervisor: $('#supervisor').val(),
                    vehicle: $('#vehicle').val(),
                    customer_comment: $('#customer_comment').val(),
                    technician_comment: $('#technician_comment').val(),
                    advisior_comment: $('#advisior_comment').val(),
                    arraylistgroup:arraylist
                    // Fixed comment collection here
                };

                return formData;
            }

            $('#submit').click(function(e) {
                e.preventDefault();

                let formData = collectFormData();
               

               

                $.ajax({
                    url: "{{ route('inspection.store') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Inspection created successfully!');
                  
                    },
                    error: function(error) {
                        alert('Error starting booking');
                        console.error(error);
                    }
                });
            });





      



    

 
   function fetchVehicleInfo() {

      const vehicleId = document.getElementById('vehicle').value;
      const vehicleInfoCard = document.getElementById('vehicle-info');
      
      if (vehicleId) {
      
         fetch(`/vehicles/${vehicleId}`)
            .then(response => response.json())
            .then(data => {
                console.log(vehicleId);
               document.getElementById('vehicle-id').value = 'VEC000'+data.id;
               document.getElementById('vehicle-make').value = data.v_make;
               document.getElementById('vehicle-model').value = data.vm;
               document.getElementById('vehicle-odometer').value = data.odometer;
               document.getElementById('vehicle-vin').value = data.vin;

            
            })
            .catch(error => {
               console.error('Error fetching vehicle info:', error);
            });
      } 
   }

</script>

<script>
   function fetchcustomer() {
    
    const customerId = document.getElementById('customer').value;

    console.log("qq");
    if (customerId) {
    
       fetch(`/customer/${customerId}`)
          .then(response => response.json())
          .then(data => {
           console.log(data);
             document.getElementById('name').value = data.first_name+''+data.last_name;
             document.getElementById('email').value = data.email;
             document.getElementById('phone').value = data.phone_number;


          
          })
          .catch(error => {
             console.error('Error fetching customer info:', error);
          });
    } 
 }
</script>











 @endsection