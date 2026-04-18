<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete_user{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-top-margin">
        <div class="modal-content custom_delete">
            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-header border-0">
                    <h2 class="modal-title text-danger fw-semibold" id="deleteUserLabel{{ $user->id }}">
                        Delete Confirmation
                    </h2>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>

                <div class="modal-body">
                    <p class="confirmation-message fs-5 text-secondary">
                        Are you sure you want to delete this user?
                    </p>
                </div>

                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-top-margin {
    margin-top: 60px; /* Adjust this value for top spacing */
}

.custom_delete {
    border-radius: 12px;
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    max-width: 600px;
    margin: auto;
}

.confirmation-message {
    margin-bottom: 10px;
    text-align: center;
    font-size: 18px;
    color: #515151;
}

.modal-title {
    font-size: 24px;
    font-weight: 600;
}

.btn {
    font-size: 16px;
    border-radius: 6px;
}
</style>
