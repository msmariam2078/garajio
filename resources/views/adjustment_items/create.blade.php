<style>
.custom {
    width: 800px;

    padding: 20px;
}
</style>
<script>
</script>

<div class="modal fade" id="adjustmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-5 custom">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Adjustment Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ms-5">

                {{ Form::open(['route' => 'adjustment_item.store', 'method' => 'post' ]) }}
                <div class="row ">
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('service_part_id', __('Service Part'), ['class' => 'form-label mb-3']) }}


                        {{ Form::select('service_part_id', $serviceParts, null, ['class' => 'form-control ','id'=>'item']) }}

                    </div>

                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('location', __('Location'), ['class' => 'form-label mb-3']) }}


                        {{ Form::text('location', null, ['class' => 'form-control ','id'=>'location','hidden'=>true]) }}
                      <input type="text" class="form-control" id="warehouse" readonly/>
                    </div>

                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('quantity', __('Quantity'), ['class' => 'form-label mb-3']) }}

                        {{ Form::text('quantity', null, ['class' => 'form-control numberonly','required'=>'required', 'placeholder' => __(' ')]) }}

                    </div>
                    <div class="form-group col-md-6 mb-4">
                        {{ Form::label('expir_day', __('Expiry Date'), ['class' => 'form-label mb-3']) }}

                        {{ Form::date('expir_day', null, ['class' => 'form-control ', 'placeholder' => __(' ')]) }}

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
<script>
     $('#item').on('change', function() {
        var warehouse = $('#warehouse');
        var location =$('#location');
       var id= $('#item').val();
        
  console.log(id);

        if (id) {

            $.ajax({
                url: '/get-warehouse?service_part_id=' + id,
                type: 'GET',
                success: function(response) {
                    console.log(response);



                    if (response) {
                      warehouse.val(response.warehouse.name);
                      location.val(response.warehouse.id);
                    } else {
                        warehouse.val('no data found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr);

                    warehouse.val("An error occurred while fetching warehouse.");
                    alert('{{ __("An error occurred while fetching warehouse.") }}');
                }
            });
        }
    });


// $('.close').click(function(){
// $('.modal').hide();
// })
</script>
    </script>