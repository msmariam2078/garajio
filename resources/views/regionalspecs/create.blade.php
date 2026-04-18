<div class="modal-body">
    <form method="POST" action="{{ route('regionalspecs.store') }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="group_name" class="form-label"> Title</label>
                <input type="text" class="form-control" name="group_name" id="group_name"
                    placeholder="Enter Group Name">
            </div>


            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>