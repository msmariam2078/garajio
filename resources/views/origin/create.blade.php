<div class="modal-body">
    <form method="POST" action="{{ route('origin.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <!-- Group Name -->
            <div class="col-md-12 mb-3">
                <label for="group_name" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description"
                    placeholder="Enter Description">
            </div>

           

            <!-- Submit Button -->
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


