<style>
.custom {
    width: 800px;
    border-radius: 20px;
    padding: 20px;
}
</style>
<script>
</script>

<div class="modal fade" id="warhouseModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-3 custom">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Ware House</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ms-5">
                {{Form::open(array('route'=>'warehouse.store','method'=>'post'))}}
                <div class="row">
                    <div class="form-group col-md-12 mb-2">
                        {{Form::label('name',__('Name'),array('class'=>'form-label mb-3','id'=>'n')) }}
                        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                    </div>
                    <div class="form-group col-md-12 mb-4 col-sm-12">
                        <label for="description" class="form-label mb-3">Description</label>
                        <textarea class="form-control" rows="2" name="description" cols="50" id="description"
                            required></textarea>
                    </div>
                    
                    <div class='col-md-12'>
                        {{ Form::label('fleet', __('Fleet '), ['class' => 'form-label']) }}

                        <label class="custom-switch">
                            <input type="checkbox" id="type-switch" name="status" class="toggle-status" value="warehouse">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="form-group col-md-6 " id='fleet' style='display:none;'>
                        {{ Form::label('fleet_type', __('Fleet Type'), ['class' => 'form-label','id'=>'f']) }}
                        {{ Form::select('fleet_type', [
                'two_wheeler' => 'Two Wheeler',
                'four_wheeler' => 'Four Wheeler',
                'optional' => 'Optional',
               
            ], null, ['class' => '', 'placeholder' => __('Select Fleet Type')]) }}
                    </div>
                    <div class="form-group col-md-6 " id='tech' style='display:none;'>
                        {{ Form::label('technician', __('Technician'), ['class' => 'form-label']) }}
                        {{ Form::select('technician', $technicians, null, ['class' => '', 'placeholder' => __('Select Technician')]) }}
                    </div>

                    <div class="form-group col-md-12 mb-2">
                        {{ Form::label('address', __('Address'), ['class' => 'form-label mb-3']) }}
                        {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter address')]) }}
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        {{ Form::label('city', __('City'), ['class' => 'form-label mb-3']) }}
                        {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter city')]) }}
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        {{ Form::label('state', __('State'), ['class' => 'form-label mb-3']) }}
                        {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter state')]) }}
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                        {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter country')]) }}
                    </div>
                    <div class="form-group col-md-6 mb-2">
                        {{ Form::label('zip_code', __('Post Code'), ['class' => 'form-label mb-3']) }}
                        {{ Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter post code')]) }}
                    </div>




                </div>






            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{Form::submit(__('Create'),array('class'=>'btn btn-primary ml-10'))}}
            </div>
            {{Form::close()}}


        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $("select").selectize();
    $('#type-switch').change(function() {
        if ($('#type-switch').is(':checked')) {
            $('#fleet').show();
            $('#tech').show();

        } else {
            $('#fleet').hide();
            $('#tech').hide();

            console.log(2);
        }

    });



});
</script>