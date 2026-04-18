<div class="modal-body">
    <form method="POST" action="{{ route('booking.postcancelbooking') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <!-- Include the booking ID as a hidden field -->
        <input type="hidden" name="booking_id" value="{{ $bookingid }}">
        <div class="form-group col-md-12">
                <label for="cancellation_reason" class="form-label">Reason for Cancellation</label>
                <select 
                    class="form-control" 
                    name="cancellation_reason" 
                    id="cancellation_reason" 
                    required
                >
                    <option value="" disabled selected>Select a reason</option>
                    <option value="price">Price</option>
                    <option value="brand">Brand</option>
                    <option value="item_availability">Item Availability</option>
                    <option value="technician_availability">Technician Availability</option>
                    <option value="location">Location</option>
                    <option value="service_not_required">Service Not Required</option>
                    <option value="unsure">Unsure</option>
                </select>
            </div>
        <div class="row mb-5">
            <div class="form-group col-md-12">
                <label for="cancellation_note" class="form-label">Cancellation Note</label>
                <textarea 
                    class="form-control" 
                    name="cancellation_note" 
                    id="cancellation_note" 
                    rows="4" 
                    placeholder="Provide a reason for cancellation" 
                    required
                ></textarea>
            </div>

            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
