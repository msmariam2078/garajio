<div class="modal-body">
    <form method="POST" action="{{ route('regionalspecs.update', $rs->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Engine Specs Field -->
            <div class="form-group col-md-12">
                <label for="enginespecs" class="form-label">Title</label>
                <input class="form-control" placeholder="Enter Engine Specs" name="title" type="text" value="{{ old('title', $rs->title) }}" id="enginespecs">
            </div>

           
        </div>

        <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
        </div>
    </form>
</div>
