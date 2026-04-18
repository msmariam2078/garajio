<div class="modal-body">
    <form method="post" action="{{ route('uom.update', $edituom->id) }}">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="skill_name" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $edituom->title }}">
            </div>
           
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>