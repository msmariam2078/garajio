@extends('layouts.master')

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Inventory Setup</h4>
           
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="mb-3 mb-xl-0">


        </div>
    </div>
</div>
@endsection

@section('content')
@include('messages_alert')
<div class="card">
     <div class="card-body">
        <div class="card-header mb-4 font-weight-bold">
           A. Inventory Auto Number
        </div>
        <form method="POST" action="{{ route('inventory.savesetup') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
          <div class="row px-4">
          <div class="col-12 custom-control custom-switch mb-4">
                  <input type="checkbox" class="custom-control-input" id="customSwitch1" name="auto" {{ $setup && $setup->auto ?'checked' : ''}}>
                        <label class="custom-control-label "style="width: 250px !important;" for="customSwitch1">
                           Auto Number
                        </label>
          </div>
              <!-- Booking Dropdown -->
                   <div class=" col-6 form-group mb-4 d-none" id="item_prefix">
                        <label  class='mb-3'>Item Number Prefix</label>
                        <input type="text"  name="item_prefix" value="ITEM000" class="form-control" require>
                 
                    </div>
                    <div class="col-6 form-group mb-4 d-none" id="item_number" require> 
                        <label  class='mb-3'>Next Item Number</label>
                        <input type="text"  name="item_number" class="form-control" >
                 
                    </div>
                   
         </div>
          <div class="card-header mb-4 font-weight-bold">
           B. Negative Quantity
        </div>
          <div class="row px-4">
          <div class="col-12 custom-control custom-switch mb-4">
                  <input type="checkbox" class="custom-control-input" id="customSwitch2" name="hand_availability" {{ $setup && $setup->hand_availability ?'checked' : ''}}>
                        <label class="custom-control-label "style="width: 250px !important;" for="customSwitch2" >
                           On-Hand Availability:
                        </label>
          </div>
          </div>
          <div class="col-12 d-flex justify-content-end"><input type="submit" class="btn btn-primary" /></div>
         

    </form>
    </div>
</div>

<script>
          $(document).ready(function() {
  
    document.getElementById('customSwitch1').addEventListener('click', async function() {
        if(this.checked)
    {
        document.getElementById('item_prefix').classList.remove("d-none");
        document.getElementById('item_number').classList.remove("d-none");
    }
    else{
        document.getElementById('item_prefix').classList.add("d-none");
        document.getElementById('item_number').classList.add("d-none");
    }
    });
});
</script>
  

@endsection