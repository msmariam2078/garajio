<div class="modal-body">
    <form method="POST" action="{{ route('shiftmasters.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Shift Title"
                    required>
            </div>
            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description"
                    placeholder="Enter Description" required>
            </div>
            <div class="col-md-12 mb-3">
                <label for="days" class="form-label">Days</label>
                <select class=" selectize" name="days[]" id="days" multiple="multiple" required>
                    <option value="Sunday">Sunday</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" name="start_time" id="start_time" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" name="end_time" id="end_time" required>
            </div>
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
$(".selectize").selectize({
    plugins: ["remove_button"],
    delimiter: ",",
    persist: false,
    create: function(input) {
        return {
            value: input,
            text: input,
        };
    },
});
</script>