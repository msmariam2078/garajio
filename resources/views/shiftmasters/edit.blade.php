<div class="modal-body">
    <form method="POST" action="{{ route('shiftmasters.update', $shiftMaster->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ $shiftMaster->title }}" placeholder="Enter Shift Title" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description" value="{{ $shiftMaster->description }}" placeholder="Enter Description">
            </div>

            <div class="col-md-12 mb-3">
                <label for="days" class="form-label">Days</label>
                <select class=" selectize" name="days[]" id="days" multiple="multiple" required>
                    @php
                    $selectedDays = json_decode($shiftMaster->first()->days, true);


                    @endphp
                    <option value="Sunday" {{ in_array('Sunday', $selectedDays) ? 'selected' : '' }}>Sunday</option>
                    <option value="Monday" {{ in_array('Monday', $selectedDays) ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ in_array('Tuesday', $selectedDays) ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ in_array('Wednesday', $selectedDays) ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ in_array('Thursday', $selectedDays) ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ in_array('Friday', $selectedDays) ? 'selected' : '' }}>Friday</option>
                    <option value="Saturday" {{ in_array('Saturday', $selectedDays) ? 'selected' : '' }}>Saturday</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
   
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" name="start_time" id="start_time" value="{{ $shiftMaster->start_time }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" name="end_time" id="end_time" value="{{ $shiftMaster->end_time }}" required>
            </div>

            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
  console.log(2);
        $('#days').selectize();
    });
</script>
