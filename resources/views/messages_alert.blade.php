    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if ($message = Session::get('success'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{ $message }}",
                    type: "success"
                });
            }
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{ $message }}",
                    type: "error"
                });
            }
        </script>
    @endif
    @if ($message = Session::get('payment'))
        <script>
            window.onload = function() {
                Swal.fire({
                    title: "created",

                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                });
            }
        </script>
    @endif
