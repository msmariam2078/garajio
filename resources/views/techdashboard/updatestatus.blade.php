<div class="modal-body">
    <form method="POST" action="{{ route('workorder.updatetechstatus') }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="workorder" value="{{ $workorderid }}">
        <div class="form-group col-md-12">
            <label for="cancellation_reason" class="form-label">Status</label>
            <select class="form-control" name="staus" id="cancellation_reason" required>
                <option value="" disabled selected>Select a status</option>
                <option value="Enroute">Enroute</option>
                <option value="StartWork">Start Work</option>
                <option value="OnHold">On Hold</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        <div class="row mb-5">
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>