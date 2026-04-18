<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Create Invoice</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('invoice.store') }}" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6 d-none">
                            {{ Form::label('invoice_id', __('Invoice Number'), ['class' => 'form-label mb-3']) }}

                            <div class="input-group">
                                <span class="input-group-text ">
                                    {{ invoicePrefix() }}
                                </span>
                                {{ Form::text('invoice_id', $invoiceNumber, ['class' => 'form-control', 'placeholder' => __('Enter Invoice Number')]) }}
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('invoice_date', __('Invoice Date'), ['class' => 'form-label mb-3']) }}
                            {{ Form::date('invoice_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="client" class="form-label">Client</label>
							<input class="form-control mb-0" placeholder="Search client" type="search" id="search-query3" autocomplete="off">
                            <input type="hidden" name="client" id="customer_id">

                            <ul class="dropdown-menu w-100 show shadow-sm" id="search-results3" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total" class="form-label">Total Amount</label>
                            <input type="number" name="total" id="total" class="form-control"
                                placeholder="Enter total amount" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="discount" class="form-label">Discount Amount</label>
                            <input type="number" name="discount" id="discount" class="form-control"
                                placeholder="Enter discount">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="final_amount" class="form-label">Final Amount</label>
                            <input type="number" name="final_amount" id="final_amount" class="form-control"
                                placeholder="Final amount" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                            {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                            {!! Form::select('status', $status, null, ['class' => 'form-control ', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                            {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2]) }}
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
    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 1000; // 1 second delay

        $('#search-query3').on('keyup', function() {
            clearTimeout(typingTimer);
            let query = $(this).val();

            if (query.length < 2) {
                $('#search-results3').hide();
                return;
            }

            typingTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route('search2') }}', // your route here
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        const results = response.data;

                        if (results.length === 0) {
                            $('#search-results3').html(
                                '<li class="dropdown-item text-muted">No client found</li>'
                            ).show();
                            return;
                        }

                        let html = '';
                        results.forEach(function(item) {
                            html +=
                                `<li class="dropdown-item" data-id="${item.id}" data-name="${item.label}">${item.label}</li>`;
                        });

                        $('#search-results3').html(html).show();
                    },
                    error: function() {
                        $('#search-results3').html(
                            '<li class="dropdown-item text-danger">Error loading</li>'
                        ).show();
                    }
                });
            }, typingDelay);
        });

        // Handle result click
        $(document).on('click', '#search-results3 li', function() {
            const name = $(this).data('name');
            const id = $(this).data('id');

            $('#search-query3').val(name);
            $('#customer_id').val(id);
            $('#search-results3').hide();
        });

        // Hide dropdown if clicked outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#search-query3, #search-results3').length) {
                $('#search-results3').hide();
            }
        });

        // Show again on focus if data exists
        $('#search-query3').on('focus', function() {
            if ($('#search-results3').children().length > 0) {
                $('#search-results3').show();
            }
        });
    });
</script>
