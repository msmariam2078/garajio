
            <div class="modal-body ms-5">

                <form method="POST" action="{{ route('service.update', $service->id) }}" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row ">
                        <div class="form-group col-md-12 mb-4">
                            <label for="title" class="form-label mb-3">Title</label>
                            <input type="text" class="form-control" name="title" value='{{ $service->title }}'
                                id="title" placeholder="Enter Title Name" required>
                        </div>
                        <div class="form-group col-md-12 mb-4">
                            <label for="skill_id" class="form-label mb-3">{{ __('Skills Group') }}</label>
                            <select id="skill_id" name="skill_id[]" class="selectdropdown" multiple="multiple">
                                <option value="">Select Skills</option>
                                @php
                                    $skills = explode(',', $service->skillId);
                                @endphp
                                @foreach ($skilgroups as $skilgroup)
                                    <option value="{{ $skilgroup->id }}"
                                        {{ in_array($skilgroup->id, $skills) ? 'selected' : '' }}>
                                        {{ $skilgroup->group_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-12 mb-4 col-sm-12">
                            <label for="description" class="form-label mb-3">Description</label>
                            <textarea class="form-control" rows="2" name="description" cols="50" id="description" required>{{ $service->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>

                    </div>
                </form>
            </div>
     

<script>
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