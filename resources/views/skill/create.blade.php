<style>
.custom {
    width: 800px;
    border-radius: 20px;
    padding: 20px;
}
</style>
<script>
</script>

<div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-5 custom">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Skill </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ms-5">

                <form method="POST" action="{{ route('skill.store') }}" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row ">
                        <div class="col-md-12 mb-3">
                            <label for="skill_name" class="form-label">Skill Name</label>
                            <input type="text" class="form-control" name="skill_name">
                        </div>



                    </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
            </form>
        </div>
    </div>
</div>