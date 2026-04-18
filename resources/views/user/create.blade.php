<style>
.custom {
    width: 800px;
    border-radius: 20px;
    padding: 20px;
}
</style>
<script>
</script>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-5 custom">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ms-5">

                <form method="POST" action="{{ route('users.store') }}" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row ">             
                        <div class="form-group col-md-6 mb-4">
                            {{ Form::label('role', __('Assign Role'), ['class' => 'form-label mb-3']) }}
                            <select name="role" class="form-control" required>
                                @foreach($userRoles as $item)
                                    @if (auth()->user()->type === 'super admin' || $item->name !== 'super admin')
                                		<option value="{{ $item->id }}">{{ $item->name }}</option>
									@endif
                                @endforeach
                            </select>
                        </div>
                

                        <div class="form-group col-md-6 mb-4">
                            {{Form::label('name',__('Name'),array('class'=>'form-label mb-3')) }}
                            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            {{Form::label('email',__('User Email'),array('class'=>'form-label mb-3'))}}
                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter user email'),'required'=>'required'))}}
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            {{Form::label('password',__('User Password'),array('class'=>'form-label mb-3'))}}
                            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter user password'),'required'=>'required','minlength'=>"6"))}}

                        </div>
                        <div class="form-group col-md-6 mb-4">
                            {{Form::label('phone_number',__('User Phone Number'),array('class'=>'form-label mb-3')) }}
                            <div class="input-group mb-3">
                                <select class="form-control ol-md-4" name="ccm">
                                    @foreach ($country_code as $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control" name="phone_number" placeholder="phone number">
                            </div>											  
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            {{Form::label('whatsapp_number',__('User whatsapp Number'),array('class'=>'form-label mb-3')) }}
                            {{Form::text('whatsapp_number',null,array('class'=>'form-control','placeholder'=>__('Enter user whatsapp number')))}}
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            {{ Form::label('reporting_manager', __('Reporting Manager'),['class'=>'form-label mb-3']) }}
                            {!! Form::select('reporting_manager', $ReportingManagers, null,array('class' =>
                            'form-control')) !!}
                        </div>
                        <div class=" col-md-6 mb-4  ">

                            <input type="file" accept="imgs/*" name="profile" style='margin-bottom: 18px;'
                                onchange="loadFile(event)">

                            <img style="border-radius:50%" width="150px" height="150px" id="output" />
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
<script>
var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
    }

};
</script>