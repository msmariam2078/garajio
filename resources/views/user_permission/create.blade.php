{{ Form::open(array('url' => 'permission')) }}
<div class="modal-body">
    <div class="row ">
        <div class="form-group col-md-6">
            {{Form::label('title',__('Permission Title'),['class'=>'form-label '])}}
            {{Form::text('title',null,array('class'=>'form-control'))}}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('user_roles', __('User Roles'),['class'=>'form-label']) }}
            <select name="user_roles[]" class="form-control">
                @foreach ($userRoles as $id=>$name) <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="row justify-content-end">
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
    </div>
</div>
</div>
{{ Form::close() }}