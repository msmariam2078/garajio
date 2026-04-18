
            <div class="modal-body ms-5" id="customModal">

                <form method="POST" action="{{ route('service.store') }}" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row ">
                        <div class="form-group col-md-12 mb-4 ">
                            <label for="title" class="form-label mb-3">Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Enter Title Name" required>
                        </div>

                        <!-- <div class="form-group col-md-12 mb-4">
                            <label for="price" class="form-label mb-3">Price</label>
                            <input type="number" class="form-control" name="price" id="price"
                                placeholder="Enter Price ">
                        </div>


                        <div class="form-group col-md-12 mb-4">
                            <label for="unit_id" class="form-label mb-3">{{ __('Unit of measurement') }}</label>
                            <select id="unit_id" name="unit_id" class=" selectdropdown" required>
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->description }}</option>
                                @endforeach
                            </select>
                        </div> -->


                        <div class="form-group col-md-12 mb-4">
                            <label for="skill_id" class="form-label mb-3">{{ __('Skills Group') }}</label>
                            <select id="skill_id" name="skill_id[]" class="selectdropdown"
                                multiple="multiple" required>
                                <option value="">Select Skills</option>
                                @foreach ($skilgroups as $skilgroup)
                                <option value="{{ $skilgroup->id }}">{{ $skilgroup->group_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-12 mb-4 col-sm-12">
                            <label for="description" class="form-label mb-3">Description</label>
                            <textarea class="form-control" rows="2" name="description" cols="50" id="description"
                                required></textarea>
                        </div>

                    </div>

           


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
            </form>
</div>
   
<script>
        $('#close').click(function(){
    $('.modal').modal('hide');
});
$(function() {
    $(".selectdropdown").selectize({
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
});
</script>

