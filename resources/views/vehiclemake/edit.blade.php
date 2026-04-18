<div class="modal-body">
    <form method="POST" action="{{ route('vehiclemake.update', $vm->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-12">
                <label for="group_name" class="form-label">Vehicle Make</label>
                <input class="form-control" placeholder="Enter Group Name" name="make_name" type="text" value="{{ old('group_name', $vm->make_name) }}" id="group_name">
            </div>
           
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
        </div>
    </form>
</div>
