<style> 
      .custom { 
        width: 800px; 
      border-radius:20px;
        padding: 20px;
      } 
    </style>
    <script>
         </script>

<div class="modal fade" id="cgroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create Customer group</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
    
          <form method="POST" action="{{ route('customergroup.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
             <div class="row ">
             <div class="col-md-12 mb-4">
                <label for="group_name" class="form-label mb-3">Group Name</label>
                <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Enter Group  Name">
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