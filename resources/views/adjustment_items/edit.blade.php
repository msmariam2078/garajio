<style> 
      .custom { 
        width: 700px; 
      
        border-radius:20px;
        padding: 20px;
      } 
    </style>
    <script>
         </script>

<div class="modal fade" id="update_adjustment{{$adjustment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create Adjustment Item</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          {{ Form::model($adjustment, ['route' => ['adjustment_item.update', $adjustment->id], 'method' => 'PUT']) }}
             <div class="row ">
       

        <div class="form-group col-md-6 mb-4" >
            {{ Form::label('location', __('Location'), ['class' => 'form-label mb-3']) }}
   
    
           
            <input class="form-control"  id="warehouse" value="{{$adjustment->warehouse->name ?? ''}}" readonly>
        </div>

        <div class="form-group col-md-6 mb-4">
           {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label mb-3']) }}

            {{ Form::text('quantity', $adjustment->quantity, ['class' => 'form-control numberonly','required'=>'required', 'placeholder' => __(' ')]) }}
        
        </div>
        <!-- <div class="form-group col-md-6 mb-4">
           {{ Form::label('unit_price', __('Unit Price'), ['class' => 'form-label mb-3']) }}

            {{ Form::text('unit_price',  $adjustment->unit_price, ['class' => 'form-control ', 'placeholder' => __(' '),'required'=>'required']) }}
        
        </div> -->
        <div class="form-group col-md-6 mb-4">
           {{ Form::label('expir_day', __('Expiry Day'), ['class' => 'form-label mb-3']) }}

           {{Form::date('expir_day',$adjustment->expir_day,array('class'=>'form-control','required'=>'required'))}}
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