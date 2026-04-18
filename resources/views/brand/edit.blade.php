<div class="modal-body">
    <form method="POST" action="{{ route('brand.update', $brand->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <!-- Group Name -->
            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="description"
                    value="{{ $brand->description }}" placeholder="Enter Description">
            </div>

            <!-- Submit Button -->
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</div>
