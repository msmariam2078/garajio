@extends('layouts.master')
@section('title','Inventory')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('inventory.index') }}" class="text-dark">Inventory Details</a>
                </h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">


            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">

                <form method="GET" action="{{ url('/inventory-search') }}">
                    <div class="row justify-content-center mt-4"
                        style="position: absolute; left: 300px; top:-80px; z-index: 1000">
                        <div class="col-auto">
                            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                        </div>
                        <div class="col-auto">
                            <select class="form-control" name="item_type">
                                <option value="">Select type</option>
                                <option value="Inventory" {{ request('item_type') == 'Inventory' ? 'selected' : '' }}>
                                    Inventory</option>
                                <option value="Service" {{ request('item_type') == 'Service' ? 'selected' : '' }}>Service
                                </option>
                                <option value="Scrap" {{ request('item_type') == 'Scrap' ? 'selected' : '' }}>Scrap
                                </option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select class="form-control" name="warehouse_id">
                                <option value="">Select warehouse</option>
                                @foreach ($warehouse as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('warehouse_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select class="form-control" name="reference">
                                <option value="">Select reference</option>
                                <option value="Scraps" {{ request('reference') == 'Scraps' ? 'selected' : '' }}>Scraps
                                </option>
                                <option value="Sales" {{ request('reference') == 'Sales' ? 'selected' : '' }}>Sales
                                </option>
                                <option value="adjustment" {{ request('reference') == 'adjustment' ? 'selected' : '' }}>
                                    adjustment</option>
                                <option value="Transfer" {{ request('reference') == 'Transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search pr-2"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>

                @php
                    $totalQuantity = 0;
                    foreach ($inventory as $inv) {
                        $totalQuantity += $inv->quantity;
                    }
                @endphp

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap datatbl-advance2">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Item Code') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Make') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Document') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                    <th>{{ __('Total Price') }}</th>
                                    <th>{{ __('Warranty') }}</th>
                                    <th>{{ __('Expire Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>		
                                @foreach ($inventory as $key => $inv)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($inv->created_at)->format('Y-m-d') }}</td>
                                        <td>{{ $inv->servicePart->item_no }}</td>
                                        <td>{{ $inv->servicePart->product_name }}</td>
                                        <td>{{ $inv->servicePart->item_type }} </td>
                                        <td>{{ $inv->location ? App\Models\WarHouse::find($inv->location)?->name : '' }}
                                        </td>
                                        <td>-- </td>

                                        <td>{{ $inv->reference }} </td>
                                        <td>{{ $inv->document ? $inv->document : '-' }} </td>
                                        <td>{{ $inv->servicePart->u_o_m?->title }}</td>
                                        <td>{{ $inv->quantity }} </td>
                                        <td>{{ $inv->servicePart->price }} </td>
                                        <td>{{ abs((int) $inv->servicePart->price * (int) $inv->quantity) }} </td>
                                        <td>{{ $inv->servicePart->warranty }} </td>
                                        <td>{{ $inv->expir_day }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="font-weight: bold; background-color: #f0f0f0;">
                                    @for ($i = 0; $i < 8; $i++)
                                        <td></td>
                                    @endfor
                                    <td class="text-right">Total Quantity:</td>
                                    <td>{{ $totalQuantity }}</td>
                                    @for ($i = 0; $i < 4; $i++)
                                        <td></td>
                                    @endfor
                                </tr>
                            </tfoot>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            let tableElement = $('.datatbl-advance2');

            if ($.fn.DataTable.isDataTable(tableElement)) {
                tableElement.DataTable().clear().destroy();
            }

            tableElement.DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        footer: true,
                        title: 'Inventory Report',
                        messageTop: 'Generated on: ' + new Date().toLocaleDateString(),
                        customize: function(win) {
                            $(win.document.body).css('font-size', '10pt')
                                .prepend('<h3 style="text-align:center">Inventory Report</h3>');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        title: 'Inventory Report'
                    },
                    {
                        extend: 'pdfHtml5',
                        footer: true,
                        title: 'Inventory Report',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'csvHtml5',
                        footer: true,
                        title: 'Inventory Report'
                    },
                    {
                        extend: 'copyHtml5',
                        footer: true
                    }
                ],
                ordering: false,
                paging: true,
                responsive: true
            });
        });
    </script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endsection
