<div class="modal-body">
    <form method="POST" action="{{ route('technician.addwarranty.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product_id }}">
        <input type="hidden" name="workorder_id" value="{{ $workorder_id }}">
        <input type="hidden" name="booking_item_id" value="{{ $booking_item_id }}">
      
      
        <div class="mb-3">
            <label for="warrant_number" class="form-label">Warranty Number</label>
            <input type="text" name="warrant_number" id="warrant_number" class="form-control" value="{{$warrant_number ?? ''}}" required >
        </div>
        <div class="mb-3">
            <label for="warrant_number" class="form-label">Warranty period</label>
            <input type="text" name="warrant_period"  value="{{$period}} Month"id="warrant_number" class="form-control" required readonly>
        </div>

        <div class="mb-3">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d')}}"
                required readonly>
        </div>


        <div class="mb-3">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" name="to_date" id="to_date" class="form-control" value="{{\Carbon\Carbon::now()->addMonth($period)->format('Y-m-d')}}" required readonly>
        </div>

        <div class="col-md-6 my-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>