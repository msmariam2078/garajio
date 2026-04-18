@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Create Transfer Order</h4>
						</div>
					</div>
					
				
				</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')

     @include('messages_alert')
   
<div class="row">
   <div class="col-12 ">
   
      <div class="card  " >
      
          <div class="card-body p-4" >
            <div class="row mb-4">
                <div class="col-5 form-group mb-4">
                  <label for="fwarehouse" class='mb-3'>From Warehouse</label>
                  <select id="fwarehouse" name="from_warehouse_id" class="form-control"  onchange="fetchproducts()" required>
                     <option >Select a Warehouse</option>
                     @foreach($warehouses as $id=>$name)
                        <option value="{{$id}}">{{$name}}</option>
                     @endforeach
                  </select>
               </div>
                <div class="col-5 form-group mb-4">
                  <label for="twarehouse" class='mb-3'>To Warehouse</label>
                  <select id="twarehouse" name="to_warehouse_id" class="form-control" onchange="check_warehouse()" required>
                     <option >Select a Warehouse</option>
                        @foreach($warehouses as $id=>$name)
                        <option value="{{$id}}">{{$name}}</option>
                     @endforeach
                  </select>
               </div>
            </div>
              <!-- <div class="form-group w-50 mb-4">
                  <label for="transfer_code" class='mb-3'>Transfer Code</label>
                  <input type="text" id="transfer_code" name="transfer_code" class="form-control"  required />
                </div>     -->
          <div class="card-header">
          Items
          </div>
            <div class="table-responsive">
                        <table class="table text-nowrap table-bordered datatbl-advance">
                            <thead>
                                <tr>
                               
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Stock</th>
                                    <th>UOM</th>
                                    <th>Qty Of Transfer</th>
                                    <th>Action</th>
                               

                                  
                                </tr>
                            </thead>
                            <tbody id="productTableBody"></tbody>
                        </table>
          </div>
              <div class="mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="4" placeholder="Enter description"></textarea>
             </div>
          <div class="mt-4 mr-3 float-right">
            <button class="btn btn-primary" id="submit">Submit</button>
               <a class="btn btn-warning" href="{{url()->previous()}}" id="close">Close</a>
          </div>
          </div>
        </div>
    </div>
</div>

<script>
     let items = [];
    var selecteditem;
   const tableBody = document.getElementById('productTableBody'); 

    function check_warehouse()
   {
    let warefrom=document.getElementById('fwarehouse').value; 
    let wareto=document.getElementById('twarehouse').value; 
    if(warefrom)
   {
    if(wareto==warefrom)
   {
    alert('You cant Select same warehouse');
   }
   }
   }
   async function fetchproducts()
    {
    var fromwarehouse= document.getElementById('fwarehouse').value;    

     
            try {
                const response = await fetch(
                    `/getwarehouseproducts?warehouse_id=${encodeURIComponent(fromwarehouse)}`
                );
                const products = await response.json();
               
      
                    
                    items=[];
                if( products.length > 0 )
                { 

            products.forEach((item, index) => {
                items.push(item);
              
          
            });
            rendertable();

                }
            } catch (error) {
                console.error('Error fetching products:', error);
                return null;
            }

    }
function rendertable()
{
    tableBody.innerHTML = '';
     items.forEach((item, index) => {
                items.push(item);
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
          
            
            <td class="item-no-cell" data-index="${index}">${item.item_no}</td>
            <td class="product-name-cell" data-index="${index}">${item.product_name}</td>
            <td class="stock-cell" data-index="${index}">${item.stock}</td>
            <td class="qty-cell" data-index="${index}">${item.title}</td>
           <td class="qty-cell" data-index="${index}">0</td>
    
           <td>
                <button class="btn btn-success btn-sm delete-row" data-index="${index}" onclick="selectedtable(${index})">select</button>
            </td>
         
        `;
                tableBody.appendChild(newRow);
            });

}


function selectedtable(index){
    selecteditem=items.at(index);
    tableBody.innerHTML = ''; 
    
        const newRow = document.createElement('tr');
                newRow.innerHTML = `
          
            <td class="product-name-cell" data-index="${index}">${selecteditem.product_name}</td>
            <td class="item-no-cell" data-index="${index}">${selecteditem.item_no}</td>
            <td id="stock" data-index="${index}">${selecteditem.stock}</td>
            <td id="uom" data-index="${index}">${selecteditem.title}</td>
            <td>
            <input id="qty_of_transfer" type="number" class="quantity-cell" data-index="${index}" id="qty_to_transfer" min="0" max="100">  
            </td>
            <td>
                <button class="btn btn-danger btn-sm delete-row" data-index="${index}" onclick="rendertable()">Delete</button>
            </td>
            <input type="hidden" id="product-id" value="${selecteditem.id}" />
         
        `;
                tableBody.appendChild(newRow);
}
// document.getElementById("close").addEventListener("click", function() {

//  $.ajax({
//      url: "{{ route('transfer.store') }}",
                   
     
     
// method: 'POST',


// });

  function collectFormData() {
          let formData = {
                
                   from_warehouse: $('#fwarehouse').val(),
                    to_warehouse: $('#twarehouse').val(),
                
                    description: $('#description').val(),
                    service_part_id : $('#product-id').val(),
                    stock: $('#stock').text(),
                    unit: $('#uom').text(),
                    qty_transfer: $('#qty_of_transfer').val(),
              
              
                  
                };

                return formData;
            }
           

        $('#submit').click(function() {
                let formData = collectFormData();
                console.log(formData);
                    if (formData.from_warehouse==formData.to_warehouse) {
                    
                     alert('you cant choose same warehouse');
                     return;
                }
                if (!formData.qty_transfer) {
                   
                    alert("Please Enter Quantity");
                    return;
                    
                }
                  if (!formData.from_warehouse) {
                   
                    alert("Please Select From Warehouse");
                    return;
                    
                }
                  if (!formData.to_warehouse) {  
                    alert("Please Select To Warehouse");
                    return;
                    
                }
                if (formData.qty_transfer>formData.stock) {
                   
                    alert("there is no stock");
                    return;
                    
                }
                console.log(formData);
                       $.ajax({
                    url: "{{ route('transfer.store') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Transfer submitted successfully!');
                        window.location.href = "/transfer";
                    },
                    error: function(error) {
                        alert('Error submitting transfer');
                        console.error(error);
                     }
                });});

</script>                
     


@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>



@endsection
