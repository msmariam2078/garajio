

<style> 
      .custom { 
        width: 800px; 
      
        padding: 20px;
      } 
    </style>
  

{{ Form::model($warrentyExtend,['route' => ['warrentyextend.update',$warrentyExtend->id], 'method' => 'PUT' ,'file'=>'true']) }}
<div class="modal-body" id="customModal">
    <div class="row">
    <div class="form-group col-md-6 mb-4" >
            {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label ']) }}
   
    
            {{ Form::select('customer_id', $clients, null, ['class' => 'form-control ']) }}
           
        </div>
    <div class="form-group col-md-6 mb-4" >
            {{ Form::label('product_id', __('Product'), ['class' => 'form-label ']) }}
   
    
            {{ Form::select('product_id', $serviceParts, null, ['class' => 'form-control ']) }}
           
        </div>
      
        <div class="form-group col-md-6 mb-4">
           {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}

           {{Form::date('purchase_date',$warrentyExtend->purchase_date,array('class'=>'form-control','required'=>'required'))}}
        </div>

     
        <div class="form-group col-md-6 mb-4">
           {{ Form::label('extend_start_date', __('Extend Warrenty Start Date'), ['class' => 'form-label ']) }}

  
           {{Form::date('extend_start_date',$warrentyExtend->extend_start_date,array('class'=>'form-control','id'=>'start'))}}
        
        </div>
           <div class="form-group col-md-6 mb-4">
           {{ Form::label('duration', __('Duration (Months)'), ['class' => 'form-label']) }}

          {{ Form::text('duration', $warrentyExtend->duration, ['class' => 'form-control ','id'=>'period', 'placeholder' => __(' '),'required'=>'required']) }}
        
        </div>
        <div class="form-group col-md-6 mb-4">
           {{ Form::label('extend_end_date', __('Extend Warrenty End Date'), ['class' => 'form-label ',]) }}

            
           {{Form::date('extend_end_date',$warrentyExtend->extend_end_date,array('class'=>'form-control','id'=>'end'))}}
        
        </div>
        <div class="form-group col-md-6 mb-4">
           {{ Form::label('price', __('Price'), ['class' => 'form-label ']) }}

            
           {{Form::text('price',$warrentyExtend->price,array('class'=>'form-control','required'=>'required'))}}
        
        </div>
        <div class="form-group col-md-6 mb-4" >
            {{ Form::label('coverage_type', __('Status'), ['class' => 'form-label ']) }}
   
    
            {{ Form::select('coverage_type', ['parts'=>'parts'], null, ['class' => 'form-control ']) }}
           
        </div>

        <div class="form-group col-md-6 mb-4" >
            {{ Form::label('status', __('Status'), ['class' => 'form-label ']) }}
   
    
            {{ Form::select('status', [0=>'unpaid',1=>'paid'], null, ['class' => 'form-control ']) }}
           
        </div>
        </div>
        </div>

     
     
     
    
  
      

   
     
        <div class="modal-footer">
                <button type="button" class="btn btn-secondary mapmodal_close" id="close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
         </div>
     
{{ Form::close() }}

  <script>
      
        function addMonths(date, months) {
        date.setMonth(date.getMonth() + months);

        return date;
    }
    function formatDateForInput(dateString) {
    const parts = dateString.split('/');
    if (parts.length === 3) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`; // yyyy-MM-dd
    }
    return dateString;
}


    $('#close').click(function() {
        $('#customModal').modal('hide');
    });
    $(document).ready(function() {
        let start = document.getElementById("start");
        let warranty_period = document.getElementById("period");
       
        warranty_period.addEventListener("change", function() {
       document.getElementById('end').value='';
       
            
            if (warranty_period.value > 0) {
                let end = addMonths(new Date(start.value), parseInt(warranty_period.value));
                  const endDateString = end.toISOString().split('T')[0];
                console.log(endDateString);
                   document.getElementById('end').value = endDateString;

            }
        });
});

         </script>
