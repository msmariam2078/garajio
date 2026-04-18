<div class="modal-body">
    <form method="POST" action="{{ route('vehiclemake.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="make_name" class="form-label">Make Name</label>
                <input type="text" class="form-control" name="make_name" id="make_name" placeholder="Enter Make Name" onkeyup="autoCapitalize(this)">
            </div>
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
    function autoCapitalize(input) {
		input.value = input.value
        .split(' ')
        .map(word =>
            word ? word.charAt(0).toUpperCase() + word.slice(1).toLowerCase() : ''
        )
        .join(' ');
    }
</script>
