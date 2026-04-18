

<div class="modal fade" id="update_skill{{$skill->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Skill</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          <form method="POST" action="{{ route('skill.update',$skill->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        @method('put')
             <div class="row ">
             <div class="form-group  col-md-12">
            <label for="title" class="form-label">Skill</label>
            <input class="form-control" placeholder="Enter Skill Name" name="skill_name" type="text" value="{{$skill->skill_name}}" id="skill_name">
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