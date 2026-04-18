@extends('layouts.master')
@section('title', 'Workorder')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('workorder.index') }}" class="text-dark">
                        WorkOrder
                    </a>
                </h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                {{-- <form method="GET" action="{{ url('/workorder-search') }}">
                    <div class="row justify-content-center mt-4" style="position: absolute; left: 300px; top:-80px; z-index: 1000">
                        <div class="col-auto">
                            <input type="date" name="appointment_date" class="form-control"
                                value="{{ request('appointment_date') }}">
                        </div>                 
                    </div>
                </form> --}}

                <form id="filterForm" class="mb-3">
                    <div class="row justify-content-center mt-4" style="position: absolute; left: 300px; top:-80px; z-index: 1000">

                        {{-- Technician --}}
						<div class="col-auto">
                            @php
                                $technicians = $workorders
                                    ->map(fn($w) => $w->technician_user)
                                    ->filter()
                                    ->unique('id')
                                    ->sortBy('id');
                            @endphp

                            <select name="technician_id" class="form-control">
                                <option value="">Select Technician</option>
                                @foreach ($technicians as $tech)
                                    <option value="{{ $tech->id }}"
                                        {{ request('technician_id') == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Appointment Date --}}
                        <div class="col-auto">
                            <input type="date" name="appointment_date" class="form-control">
                        </div>

                        {{-- Created By (Agent) --}}
						<div class="col-auto">
                            @php
                                $agents = $workorders
                                    ->filter(fn($w) => $w->created_by)
                                    ->pluck('agent')
                                    ->unique('id')
                                    ->sortBy('id');
                            @endphp
                            <select class="form-control" name="created_by">
                                <option value="">Select Agent</option>
                                @foreach ($agents as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('created_by') == $item->id ? 'selected' : '' }}>
                                        {{ $item->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>  

                        {{-- Status --}}
						<div class="col-auto">
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                @foreach ($workorders->pluck('status')->unique()->sort()->values() as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search pr-2"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                @php
                    $statusColors = [
                        'Open' => '#1e90ff',
                        'Work Completed' => 'green',
                        'Pending' => '#ffc107',
                        'Paid' => 'green',
                        'Accepted' => '#007bff',
                        'Confirmed' => '#ff6347',
                        'OnHold' => 'rgb(40,131,167)',
                        'Enroute' => 'rgb(40,131,167)',
                        'Invoiced' => 'red',
                        'Inspection Completed'=>'#178236',
                        'Start Inspecion'=>'#FF8904'
                    ];
                @endphp

                {{-- <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap datatbl-advance" style="font-size:13px">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>WO-ID</th>
                                    <th>Customer Name</th>
                                    <th>Service Group</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>V Reg</th>
                                    <th>V Model</th>
                                    <th>Technician</th>
                                    <th>Appointment Date & time</th>
                                    <th>Status</th>
                                    <th>Agent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workorders->sortByDesc('id')->values() as $key => $workorder)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td class="text-center">#WO-{{ $workorder->id }}</td>
                                        <td>{{ $workorder->client?->full_name }}</td>		
										
										@php $serviceGroup = $workorder->serviceGroupRecord(); @endphp
										<td>{{ $serviceGroup?->name ?? '' }}</td></td>
                                        <td>+{{ preg_replace('/[^0-9]/', '', $workorder->client?->ccm) }}{{ $workorder->client?->phone_number }}
                                        </td>
                                        <td>{{ $workorder->bookings?->city ?? '' }}</td>

                                        @php $vehicle = $workorder->vehicleRecord(); @endphp
                                        <td>{{ $vehicle?->rego ?? '' }}</td>
                                        <td>{{ $vehicle?->vehicle_models?->model_name ?? '' }}</td>

                                        @php $technician = $workorder->technicianRecord(); @endphp
                                        <td>{{ $technician?->full_name ?? '' }}</td>

                                        @php $booking = $workorder->bookingRecord; @endphp
                                        <td>{{ $booking?->booking_date ?? '' }} {{ $booking?->booking_time ?? '' }}</td>

                                        @php
                                            $badgeColor = $statusColors[$workorder->status] ?? '#4e89c4';
                                        @endphp

                                        <td>
                                            <span class="badge" style="width:75px; background-color: {{ $badgeColor }}; color:white;">
                                                {{ $workorder->status }}
                                            </span>
                                        </td>
                                        <td>{{ $workorder->agent?->first_name }}</td>

                                        <td>
                                       
                                            @can('show work order')
                                                <a class="text-success" href="{{ route('workorder.show', $workorder->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            @can('edit work order')
                                                <a class="text-success" href="{{ route('workorder.edit', $workorder->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            @can('delete work order')
                                             {!! Form::open(['method' => 'DELETE', 'route' => ['workorder.destroy', $workorder->id]]) !!}
                                                <a class="text-danger confirm_dialog" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> --}}

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="workorders-table" style="font-size:13px" class="table">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>WO-ID</th>
                                    <th>Customer Name</th>
                                    <th>Service Group</th>
                                    <th>Phone</th>
                                    <th>City</th>
                                    <th>V Reg</th>
                                    <th>V Model</th>
                                    <th>Technician</th>
                                    <th>Appointment</th>
                                    <th>Status</th>
                                    <th>Agent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>

    <script>
        $(function() {
            let table = $('#workorders-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                order: [
                    [1, 'desc']
                ], // 2nd column (WO-ID) descending
                dom: 'Bfrtip',
                buttons: ['print', 'excel', 'pdf', 'csv', 'copy'],
                ajax: {
                    url: '{{ route('workorders.data') }}',
                    data: function(d) {
                        d.technician_id = $('select[name=technician_id]').val();
                        d.appointment_date = $('input[name=appointment_date]').val();
                        d.created_by = $('select[name=created_by]').val();
                        d.status = $('select[name=status]').val();
                    }
                },
                columns: [
					{
                        data: 'id',
                        name: 'id',
                        className: 'd-none'
                    },
                    {
                        data: 'wo_id',
                        name: 'id'
                    },
                    {
                        data: 'customer',
                        name: 'client.full_name'
                    },
                    {
                        data: 'service_group',
                        orderable: false
                    },
                    {
                        data: 'phone',
                        name: 'client.phone_number'
                    },
                    {
                        data: 'city',
                        orderable: false
                    },
                    {
                        data: 'v_reg',
                        orderable: false
                    },
                    {
                        data: 'v_model',
                        orderable: false
                    },
                    {
                        data: 'technician',
                        orderable: false
                    },
                    {
                        data: 'appointment',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'agent',
                        name: 'agent.first_name'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // 🔄 Refresh on filter submit
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
@endsection
<script>
    function initAutocomplete() {

    }
    $('#customModal').on('show.bs.modal', function() {
        initAutocomplete();
        // Initialize map and autocomplete on page load
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    });

    $('.table').DataTable({
        columnDefs: [{
            targets: 0, // assuming the WO-ID is in the first column
            type: 'num' // tells DataTables to sort numerically
        }],
        order: [
            [0, 'desc']
        ] // default order: descending
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dayDisplay = document.getElementById("dayDisplay");
        const dateDisplay = document.getElementById("dateDisplay");
        const prevDateButton = document.getElementById("prevDate");
        const nextDateButton = document.getElementById("nextDate");

        // Set initial date to today
        let currentDate = new Date();

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, "0");
            const day = date.getDate().toString().padStart(2, "0");
            return `${year}-${month}-${day}`;
        };

        const formatDay = (date) => {
            const options = {
                weekday: 'long'
            };
            return new Intl.DateTimeFormat('en-US', options).format(date);
        };

        const updateDate = () => {
            if (dayDisplay && dateDisplay) {
                dayDisplay.value = formatDay(currentDate); // Update day
                dateDisplay.value = formatDate(currentDate); // Update date
            }
            console.log("Date updated to: ", formatDate(currentDate), formatDay(currentDate));
        };

        prevDateButton?.addEventListener("click", () => {
            currentDate.setDate(currentDate.getDate() - 1);
            updateDate();
        });

        nextDateButton?.addEventListener("click", () => {
            currentDate.setDate(currentDate.getDate() + 1);
            updateDate();
        });

        // Set initial values explicitly for testing
        dayDisplay.value = "Monday";
        dateDisplay.value = formatDate(currentDate); // Today's date in YYYY-MM-DD format
    });
</script>
