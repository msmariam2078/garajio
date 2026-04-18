<style>
.custom_delete{
    height: 300px;
   width: 600px;
   border-radius: 8px;
   background-color: #fff;
   border-radius: 8px;
   
   
  
   padding: 20px;
    text-align: center;
}

        button {
            border: none;
        }

        h2 {
            color: #515151;
        }

        .confirmation-message {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
        }

        .button-container .button {
            padding: 10px 20px;
            font-size: 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-button {
            background-color: #ccc;
            color: #535353;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
        }
    </style>
        <div class="modal fade" id="delete_service_group{{ $servicegroup->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content custom_delete d-flex justify-content-center">
        <form action="{{ route('service-group.destroy',  $servicegroup->id) }}" method="post">
                {{ method_field('delete') }}
                {{ csrf_field() }}
        <h1>Delete Confirmation</h1>
            <p class="confirmation-message " style="font-size:20px;">
                Are you sure you want to delete this service group ?
            </p>

            <div class="button-container">
                <button id="cancelBtn" 
                    class="button cancel-button">
                    Cancel
                </button>
                <button id="deleteBtn" 
                    class="button delete-button">
                    Delete
                </button>
            </div> 
    </form>
    </div>
   
        </div>
    </div>





