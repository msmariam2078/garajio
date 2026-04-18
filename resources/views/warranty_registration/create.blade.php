<style>
.custom {
    width: 800px;

    padding: 20px;
}
</style>

<div class="modal-body ms-5" id="customModal">

    <form method="POST" action="{{ route('warrentyRegistration.store') }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="form-group col-md-6">
              {{ Form::label('warranty_no', __('Warranty Number'), ['class' => 'form-label']) }}
              <input type='text' name='warranty_no' class='form-control'/>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('work_order_id', __('Work order'), ['class' => 'form-label']) }}

                <select id='work_order_id' class='form-control ' name="work_order_id">
                    <option>select work order</option>
                    @foreach($workOrders as $work)
                    <option value="{{$work->id}}"> WO-{{$work->id}}</option>
                    @endforeach
                </select>


            </div>

            <div class="form-group col-md-6">
                {{ Form::label('customer_name', __('Customer'), ['class' => 'form-label']) }}


                {{ Form::text('customer_name',  null, ['class' => 'form-control basic-select','id'=>'customer_name','required'=>'required','readonly'=>true]) }}

            </div>
            <div class="form-group col-md-6">
                {{ Form::label('product_id', __('Product'), ['class' => 'form-label']) }}

                <select id='product' class='form-control basic-select' name="product_id" required>
                    <option>select product</option>
                </select>


            </div>
            <input type="hidden" id="customer_id" name="customer_id" value="">

            <div class="form-group col-md-6">
                {{ Form::label('vehicle_id', __('Vehicle'), ['class' => 'form-label']) }}


                {{ Form::text('vehicle_id', null, ['class' => 'form-control basic-select','required'=>'required','id'=>'vehicle_name','readonly'=>true]) }}

            </div>

            <div class="form-group col-md-6">
                {{ Form::label('invoice_id', __('Invoice ID'), ['class' => 'form-label']) }}

                {{Form::text('invoice_id',null,array('class'=>'form-control','id'=>'invoice_id','required'=>'required','readonly'=>true))}}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('invoice_date', __('Invoice Date'), ['class' => 'form-label']) }}

                {{Form::text('invoice_date',null,array('class'=>'form-control','id'=>'invoice_date','required'=>'required','readonly'=>true))}}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('warranty_period', __('Warranty Period'), ['class' => 'form-label']) }}


                {{ Form::text('warranty_period', null, ['class' => 'form-control basic-select','required'=>'required','id'=>'w_period']) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('warranty_start_date', __('Warranty Start Date'), ['class' => 'form-label']) }}

                {{Form::date('warranty_start_date',null,array('class'=>'form-control','required'=>'required','id'=>'start'))}}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('warranty_end_date', __('Warranty End Date'), ['class' => 'form-label']) }}

                {{Form::text('warranty_end_date',null,array('class'=>'form-control','required'=>'required','readonly'=>true,'id'=>'end'))}}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}

                <select class='form-control basic-select' name="status" required>
                    <option value='1'>Active</option>
                    <option value='2'>Suspended</option>
                </select>


            </div>
        </div>








        <div class="modal-footer">
            <button type="button" class="btn btn-secondary mapmodal_close" id="close">Cancel</button>
            <button type="submit" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
        </div>

    </form>
</div>




<script>
function addMonths(date, months) {
    date.setMonth(date.getMonth() + months);

    return date;
}
$('#close').click(function(){
    $('#customModal').modal('hide');
});


$(document).ready(function() {
 

    // Event listener for start date change
    start.addEventListener("change", function() {
        let warranty_period = document.getElementById("w_period").value;
        let inv_date = document.getElementById("invoice_date").value;

        let end = addMonths(new Date(inv_date), parseInt(warranty_period));

        document.getElementById("end").value = new Date(end).toLocaleDateString();
    });

    // Event listener for work order ID change
    $('#work_order_id').change(function() {
        let id = $(this).val();
        $('#invoice_id').val('');
        $('#invoice_date').val('');
        $('#customer_id').val(''); // Reset customer_id hidden input
        $('#product').empty();

        $.ajax({
            url: '{{ route('workorder.getOne') }}',
            type: "get",
            dataType: 'json',
            data: {
                id: id
            },
            success: function(res) {
                console.log(res);

                // Set customer ID in hidden input and customer name (if visible)
                $('#customer_id').val(res.customer_id); // Set hidden input
                $('#customer_name').val(res.customer); // If a visible input is present

                // Populate product dropdown
                $.each(res.products, function(i, item) {
                    $.each(item, function(j, e) {
                    $('#product').append(
                        $('<option>', {
                            value: e.product_id,
                            text: e.product_name
                        })
                    );
                });
            });
                // Set other form fields
                $('#invoice_id').val('INV00' + res.invoice.invoice_id);
                $('#invoice_date').val(res.invoice.invoice_date);
                $('#vehicle_name').val('VCL-' + res.vehicle);
            },
        });
    });
});
</script>