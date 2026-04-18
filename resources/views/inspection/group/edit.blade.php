<style> 
      .custom { 
        width: 800px; 
      
        padding: 20px;
      } 
    </style>
    <script>
         </script>

<div class="modal fade" id="update_group{{$group->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create inpection Group</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          <form method="POST" action="{{ route('inspection.groupupdate' , $group->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        
          
        <div class="form-group">
                  <label for="name"  class='mb-3'>Group code</label>
                  <input type="text" class="form-control" id="code" name="code" value="{{$group->code}}" required>
               </div>
               <div class="form-group">
                  <label for="description"  class='mb-3'>Description</label>
                  <textarea class="form-control" id="description" name="description" rows="3" required>{{$group->des}}</textarea>
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