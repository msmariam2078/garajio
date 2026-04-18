

<div class="modal fade" id="update_user{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          <form method="POST" action="{{ route('users.update',$user->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('put')
             <div class="row ">
            
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('role', __('Assign Role'),['class'=>'form-label mb-3']) }}
                <select name="role" class="form-control" required>
                                @foreach($userRoles as $item)
                                <option value="{{ $item->id }}" {{$user->parent_id==$item->id}} {{$item->name==$user->type? 'selected' : ''}}>{{ $item->name }}</option>
                                @endforeach
                            </select>
            </div>
    
            <div class="form-group col-md-6 mb-4">
                {{Form::label('name',__('Name'),array('class'=>'form-label mb-3')) }}
                {{Form::text('first_name',$user->first_name,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
            </div>
        <div class="form-group col-md-6 mb-4">
            {{Form::label('email',__('User Email'),array('class'=>'form-label mb-3'))}}
            {{Form::text('email',$user->email,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-6 mb-4">
            {{Form::label('phone_number',__('User Phone Number'),array('class'=>'form-label mb-3')) }}
            {{Form::text('phone_number',$user->phone_number,array('class'=>'form-control','placeholder'=>__('Enter Phone Number')))}}
        </div>
       
        <div class="form-group col-md-6 mb-4">
            {{Form::label('whatsapp_number',__('User whatsapp Number'),array('class'=>'form-label mb-3')) }}
            {{Form::text('whatsapp_number',$user->whatsapp_number,array('class'=>'form-control','placeholder'=>__('Enter user whatsapp number')))}}
        </div>
        <div class="form-group col-md-6 mb-4">
                {{ Form::label('reporting_manager', __('Reporting Manager'),['class'=>'form-label mb-3']) }}
                {!! Form::select('reporting_manager', $ReportingManagers, null,array('class' => 'form-control ','required'=>'required')) !!}
         </div>
        <div class=" col-md-6 mb-4">    
            <input type="file"  accept="imgs/*" name="profile" style='margin-bottom: 18px;' onchange="loadFile(event)">
            {{-- <img style="border-radius:50%" width="150px" height="150px" id="output"/> --}}
            <img style="border-radius:50%; border: 0.5px solid black;" width="150px" height="150px" src="{{ $user->profile }}"/>
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