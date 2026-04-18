<style> 
      .custom { 
        width: 800px; 
      border-radius:20px;
        padding: 20px;
      } 
    </style>
    <script>
         </script>

<div class="modal fade" id="update_ve{{$vehicle->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Vehicle </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          {{ Form::model($vehicle, ['route' => ['vehicle.update', \Illuminate\Support\Facades\Crypt::encrypt($vehicle->id)], 'method' => 'PUT']) }}
             <div class="row">
             <div class="form-group col-md-6">
            {{ Form::label('rego', 'Registration Number', ['class' => 'form-label mb-3']) }}
            {{ Form::text('rego', $vehicle->rego, ['class' => 'form-control', 'placeholder' => 'Enter Registration Number']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('name', 'Name', ['class' => 'form-label mb-3']) }}
            {{ Form::text('name', $vehicle->name, ['class' => 'form-control', 'placeholder' => 'Enter Vehicle Name']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('client', __('Customer'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('client', $clients, $vehicle->clients, ['class' => ' hidesearch', 'required' => 'required']) !!}
            @if (Gate::check('create client'))
                <a class="customModal float-end small" href="#!" data-size="lg" data-url="{{ route('client.create') }}"
                    data-title="{{ __('Create Client') }}">
                    {{ __('Create Client') }}
                </a>
            @endif
        </div>
        
        <!-- State -->
        <div class="form-group col-md-6">
            {{ Form::label('state', 'State/Emirates', ['class' => 'form-label mb-3']) }}
            {!! Form::select('state', $emirates, $vehicle->state, ['class' => ' hidesearch', 'required' => 'required']) !!}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('v_make', __('Vehicle Make'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('v_make', $vm, $vehicle->v_make, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vm')">
                    {{ __('Create Vehicle Make') }}
                </a>
        </div>

       <div class="form-group col-md-6">
            {{ Form::label('vm', __('Vehicle Model'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vm', $vmod, $vehicle->vm, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vmod')">
                    {{ __('Create Vehicle Model') }}
                </a>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('vmc', __('Vehicle Model Code'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vmc', $vmc, $vehicle->vmc, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vmc')">
                    {{ __('Create Vehicle Model Code') }}
                </a>
        </div>

        <!-- Model Series -->
        <div class="form-group col-md-6">
            {{ Form::label('model_series', 'Model Series', ['class' => 'form-label mb-3']) }}
            {{ Form::text('model_series', $vehicle->model_series, ['class' => 'form-control', 'placeholder' => 'Enter Model Series']) }}
        </div>

        <!-- VIN -->
        <div class="form-group col-md-6">
            {{ Form::label('vin', 'VIN', ['class' => 'form-label mb-3']) }}
            {{ Form::text('vin', $vehicle->vin, ['class' => 'form-control', 'placeholder' => 'Enter VIN']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('engine_number', 'Engine Number', ['class' => 'form-label mb-3']) }}
            {{ Form::text('engine_number', $vehicle->engine_number, ['class' => 'form-control', 'placeholder' => 'Enter Engine Number']) }}
        </div>
        
        <div class="form-group col-md-6">
            {{ Form::label('vt', __('Vehicle Transmission'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vt', $vt, $vehicle->vt, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vt')">
                    {{ __('Create Vehicle Transmission') }}
                </a>
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('a_c', 'A/C' , ['class' => 'form-label mb-3']) !!}
            {!! Form::checkbox('a_c',1, $vehicle->a_c == 1 ? true : false,['class' => 'form-check-control']) !!}
        </div>

       <div class="form-group col-md-6">
            {{ Form::label('vbt', __('Vehicle Body Type'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vbt', $vbt, $vehicle->vbt, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vbt')">
                    {{ __('Create Vehicle Body Type') }}
                </a>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('vcn', __('Vehicle Colour'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vcn', $vcn, $vehicle->vcn, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vcn')">
                    {{ __('Create Vehicle Color') }}
                </a>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('vsc', __('Vehicle Seating Capacity'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vsc', $vsc, $vehicle->vsc, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vsc')">
                    {{ __('Create Vehicle Seating Capacity') }}
                </a>
        </div>

        <!-- Odometer -->
        <div class="form-group col-md-6">
            {{ Form::label('odometer', 'Odometer', ['class' => 'form-label mb-3']) }}
            {{ Form::number('odometer', $vehicle->odometer, ['class' => 'form-control', 'placeholder' => 'Enter Odometer Reading']) }}
        </div>
 <div class="form-group col-md-6">
            {{ Form::label('vdt', __('Vehicle Driving Type'), ['class' => 'form-label mb-3']) }}
            {!! Form::select('vdt', $vdt, $vehicle->vdt, ['class' => ' hidesearch', 'required' => 'required']) !!}
            <a class="float-end small" href="javascript:void(0)" onclick="open_modal('vdt')">
                    {{ __('Create Vehicle Driving Type') }}
                </a>
        </div>
          
            
           </div>
        
          </div>

      
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Submit</button>

           </div>
           {{ Form::close() }}
     </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    //change selectboxes to selectize mode to be searchable
  
    $("select").selectize();
    $('.customer_id').change(function() {
  

  
    
  let id=$(this).val();
  
  $.ajax({
    
    url: '{{ route('users.country') }}',
    type: "get",
    dataType: 'json',
  
    data: {id:id},
    
    success: function(res) {
      $('#country').val(res.data);
    
    
      },
});
 
});

  });




</script>