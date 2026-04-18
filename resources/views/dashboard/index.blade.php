@extends('layouts.master')
@section('title', 'Dashboard')
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <style>
        .fc-event {
            text-align: center;
            font-size: 10px;
            color: white;
        }

        .client .list-group {

            height: 100%;
            overflow-y: auto;

        }

        .line-solid {
            height: 1px;
            width: 100%;
            background: #eeeeee;
        }

        .line {

            height: 2px;
            width: 100%;
            background:
                repeating-linear-gradient(90deg, grey 0 2px, #0000 0 7px)
                /*5px red then 2px transparent -> repeat this!*/
        }

        .workorder {
            width: 90%;
            overflow-x: auto;
        }

        .swiper-slide {

            width: 100%;
            height: 850px;
            text-align: center;
            background: white;
            position: relative;
        }

        .swiper-slide .col {
            background-color: #E6EFC3;
        }

        .swiper .swiper-button-next,
        .swiper .swiper-button-prev {
            background-color: white;
            background-color: rgba(255, 255, 255, 0.5);


            position: absolute;
            top: 4%;


        }

        #uaemap {
            height: 270px;
            min-width: 310px;
            max-width: 900px;
            margin: 0 auto;
        }

        .loading {
            margin-top: 10em;
            text-align: center;
            color: gray;
        }
    </style>

    <!-- Include Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!</h2>
                <p class="mg-b-0">Admin Dashboard</p>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
                    <div>
                        <h6 class="mb-3 tx-12 text-white">TODAY BOOKINGS</h6>
                        <div>
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                {{ App\Models\Booking::whereDate('created_at', Carbon\Carbon::today())->count() }}
                            </h4>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h6 class="mb-3 tx-12 text-white">MONTHLY BOOKINGS</h6>
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ App\Models\Booking::whereMonth('created_at', \Carbon\Carbon::now()->month)->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
                    <div>
                        <h6 class="mb-3 tx-12 text-white">TODAY WORK ORDERS</h6>
                        <div>
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                {{ App\Models\WorkOrder::whereDate('created_at', Carbon\Carbon::today())->count() }}
                            </h4>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h6 class="mb-3 tx-12 text-white"> MONTHLY WORK ORDERS </h6>
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ App\Models\WorkOrder::whereMonth('created_at', Carbon\Carbon::now()->month)->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
                    <div>
                        <h6 class="mb-3 tx-12 text-white">TODAY INVOICE</h6>
                        <div>
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                {{ App\Models\Invoice::whereDate('created_at', Carbon\Carbon::today())->count() }}</h4>
                        </div>
                    </div>
                    <div>
                        <div>
                            <h6 class="mb-3 tx-12 text-white">TOTAL REVENUE</h6>

                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">AED {{ $result['totalRevenue'] }}</h4>

                            </div>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
                    <div>
                        <h6 class="mb-3 tx-12 text-white">TODAY ASSIGNED WORK ORDERS</h6>
                        <div>
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">
								{{ \App\Models\WorkOrder::whereDate('created_date', \Carbon\Carbon::today())->where('status', '!=', 'Open')->count() }}
							</h4>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card pb-4">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Work Order status</h4>
                    </div>

                </div>

                <div class="card-body">
                    @php
                        $statusColors = [
                            'Confirmed' => '#036fe7',
                            'Pending' => '#f93a5a',
                            'Invoiced' => '#f7b731',
                            'Completed' => '#28a745',
                        ];
                    @endphp

                    <div class="total-revenue">
                        @foreach ($statusColors as $status => $color)
                            <div>
                                <h4>{{ $statusSums[$status] ?? 0 }}</h4>
                                <label>
                                    <span style="background-color: {{ $color }};"></span>
                                    {{ $status }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="bar2" class="sales-bar mt-4"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-5">
            <div class="card card-dashboard-map-one">
                <label class="main-content-label">Sales revenue by cities in UAE</label>
                <span class="d-block mg-b-20 text-muted tx-12">Sales Performance of all states in the United Emarite</span>
                <div class="">
                    <div id="uaemap"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm">
        <div class="col-xl-4 col-md-12 col-lg-12 mb-4">
            <div class="card mb-0" style="height: 500px; overflow-y: auto;">
                <div class="card-header ">
                    <h3 class="card-title mb-1">Recent Customers</h3>
                </div>
                <div class="card-body  p-0 customers mt-1">
                    <div class="list-group list1 list-lg-group list-group-flush">
                        @foreach ($result['recentClient'] as $key => $client)
                            <div class="list-group-item list-group-item-action" href="#">
                                <div class="media mt-0">
                                    <img class="avatar-lg rounded-circle mr-3 my-auto"
                                        src="{{ asset('assets/image/1748700120_683b0bd8a2798.png') }}"
                                        alt="Image description">
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="mt-0">
                                                <h5 class="mb-1 tx-15">{{ $client->full_name }}</h5>
                                                <p class="mb-0 tx-13 text-muted">{{ $client->email }}<span
                                                        class="text-success ml-2">+{{ preg_replace('/[^0-9]/', '', $client->ccm) }}{{ $client->phone_number }}</span>
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card mb-0" style="height: 500px; overflow-y: auto;">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-3">Sales Activity</h3>

                </div>
                <div class="product-timeline card-body pt-2 mt-1">
                    <ul class="timeline-1 mb-0 py-3">
                        <li class="mt-0"> <i class="ti-pie-chart bg-primary-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Total Products</span>
                            <p class="mb-0 text-muted tx-12">{{ $result['totalProduct'] }} </p>
                        </li>
                        <li class="mt-0"> <i class="mdi mdi-cart-outline bg-danger-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14 ">Total Sales</span>
                            <p class="mb-0 text-muted tx-12">{{ $result['totalInvoice'] }}</p>
                        </li>
                   
                        <li class="mt-0"> <i class="ti-wallet bg-warning-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Total Warranty</span>
                            <p class="mb-0 text-muted tx-12">{{ $result['totalWarranty'] }}</p>
                        </li>
                        <li class="mt-0"> <i class="si si-eye bg-purple-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Total Bookings</span>
                            <p class="mb-0 text-muted tx-12">
                                {{ $bookingCount = App\Models\Booking::whereMonth('requested_date', now()->month)->whereYear('requested_date', now()->year)->where('status', 'Booking')->count() }}
							</p>
                        </li>
                        <li class="mt-0 mb-0"> <i class="icon-note icons bg-primary-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14 ">Total Work Orders</span>
                            <p class="mb-0 text-muted tx-12">{{ $result['totalWorkOrder'] }}</p>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-2">Recent Booking Status</h3>
                </div>
                <div class="card-body sales-info p-0">
                    <div id="chart2" style="width: 250px; margin: auto;"></div>
                    <br><br><br>
                    <div class="row sales-infomation pb-0 mb-0 mx-auto wd-100p">
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-success brround"></span>Booking</p>
                            <h3 class="mb-1">
								{{ $bookingCount }}
                            </h3>
                            <div class="d-flex">
                                <p class="text-muted">This month</p>
                            </div>
                        </div>
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-info brround"></span>Cancelled</p>
                            <h3 class="mb-1">
                                {{ $canceledCount = App\Models\Booking::whereMonth('requested_date', now()->month)->whereYear('requested_date', now()->year)->where('status', 'canceled')->count() }}
                            </h3>
                            <div class="d-flex">
                                <p class="text-muted">This month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-0">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-0 ">SOURCE OF BOOKING</h3>
                </div>
                <div class="card-body">
                    <div class="row p-0 m-0">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">EXISTING CUSTOMERS</p>

                            </div>
                            <h4 class="font-weight-bold mb-2">{{ $result['totalClient'] }}</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-primary-gradient wd-{{ ceil($result['totalClient'] / 10) * 10 }}p"
                                    role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">NEW CUSTOMERS</p>
                            </div>
                            <h4 class="font-weight-bold mb-2">{{ $result['newClient'] }}</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-danger-gradient wd-{{ ceil($result['newClient'] / 10) * 10 }}}}p"
                                    role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm row-deck">
        <div class="col-md-12 col-lg-4 col-xl-4">
            <div class="card card-dashboard-eight pb-2">
                <h6 class="card-title">Your Top Cities</h6>
                <div class="list-group">

                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8 col-xl-8">
            <div class="card card-table-two">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-1">Your Most Selling Products</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>


                <div class="table-responsive country-table">
                    <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-lg-25p">ID</th>
                                <th class="wd-lg-25p tx-left">Name</th>
                                <th class="wd-lg-25p tx-left">Sales Amount</th>
                                <th class="wd-lg-25p tx-left">Sold Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['mostSellingProduct'] as $product)
                                <tr>
                                    <td>P-{{ $product->id }}</td>
                                    <td class="tx-right tx-medium tx-left">{{ $product->product_name }}</td>
                                    <td class="tx-right tx-medium tx-left"> AED
                                        {{ (int) ($product->servicePartAdjustments?->sum('unavailable') ?? 1) * (int) ($product->price ?? 0) ?? '' }}
                                    </td>
                                    <td class="tx-right tx-medium tx-left">
                                        {{ $product->servicePartAdjustments?->sum('unavailable') ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-sm row-deck">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header py-2 d-flex text-light" style="background: royalblue">
                    <h4 class="mb-0 pt-1">Technician Dashboard</h4>
                    <h5 class="ml-5 pt-2">{{ date('M-d') }}</h5>
                </div>

                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-md-3 border">
                            <canvas id="statusChart" width="400" height="400"></canvas>

                            @php
                                $total = $workorderGroup->sum('total');
                                $unassigned = $workorderGroup->firstWhere('status', 'Open')?->total ?? 0;
                            @endphp

                            <table class="table table-bordered text-center">
                                <thead style="background: chartreuse; height: 40px;">
                                    <tr>
                                        <th>Total workorder</th>
                                        <th>Assigned</th>
                                        <th>Unassigned</th>
                                    </tr>
                                </thead>
                                <tbody style="background: darksalmon; height: 40px;">
                                    <tr class="fw-bold">
                                        <td>{{ $total }}</td>
                                        <td>{{ $total - $unassigned }}</td>
                                        <td>{{ $unassigned }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-9 border">
                            <div class="row row-sm">
                                <div class="col-md-12 bg-secondary-transparent px-5 py-2 d-flex align-items-center">
                                    <!-- Left Arrow -->
                                    <div class="swiper-button-prev me-3"></div>

                                    <!-- Swiper Container -->
                                    <div class="swiper mySwiper" style="width: 100%;">
                                        <div class="swiper-wrapper">
                                            @php
                                                $openJob = App\Models\WorkOrder::where('status', 'Open')->get();
                                            @endphp

                                            @foreach ($openJob as $item)
                                                <div class="swiper-slide">
                                                    <a href="{{ route('workorder.edit', $item->id) }}" target="_blank"
                                                        class="bg-white border rounded p-3 d-block text-center">
                                                        <h5 class="mb-0">
                                                            <i class="fas fa-briefcase pr-2"></i>
                                                            WO-{{ $item->id }}
                                                        </h5>
                                                        Click & assign technician
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right Arrow -->
                                    <div class="swiper-button-next ms-3"></div>
                                </div>
                            </div>

                            {{-- right side --}}
                            @php
                                use Illuminate\Support\Facades\DB;
                                use Carbon\Carbon;
                                use App\Models\TechnicianBookingAppointment;

                                $today = Carbon::today()->toDateString();
                                $ignoreStatus = ['Open', 'Pending'];

                                $appointments = TechnicianBookingAppointment::with(['technician', 'workorder'])
                                    ->where('from_date', $today)
                                    ->whereHas('workorder', fn($q) => $q->whereNotIn('status', $ignoreStatus))
                                    ->get()
                                    ->groupBy(fn($a) => $a->technician?->id ?? 'Unknown');

                                $technicianIds = [];
                                foreach ($appointments as $id => $group) {
                                    $technician = $group->first()->technician;
                                    $technicianIds[$id] = $technician?->id ?? 0;
                                }

                                $workorderColors = collect($appointments)
                                    ->flatten()
                                    ->mapWithKeys(
                                        fn($appt) => [
                                            $appt->workorder->id => '#' . substr(md5($appt->workorder->id), 0, 6),
                                        ],
                                    )
                                    ->toArray();

                                function timeToLeft($time)
                                {
                                    [$h, $m] = explode(':', $time);
                                    return ((int) $h + (int) $m / 60) * 100;
                                }

                                function isOverlap($aStart, $aEnd, $bStart, $bEnd)
                                {
                                    return $aStart < $bEnd && $bStart < $aEnd;
                                }
                            @endphp

                            <style>
                                .timeline-wrapper {
                                    display: flex;
                                    overflow-x: hidden;
                                    overflow-y: auto;
                                    height: 600px;
                                    border: 1px solid #ccc;
                                    font-family: Arial, sans-serif;
                                    position: relative;
                                    margin: 0px -10px;
                                }

                                .tech-names {
                                    flex: 0 0 auto;
                                    background: #f8f9fa;
                                    border-right: 1px solid #ddd;
                                    z-index: 2;
                                    position: sticky;
                                    left: 0;
                                    background: honeydew;
                                }

                                .tech-cell {
                                    height: 50px;
                                    line-height: 22px;
                                    padding: 4px 6px;
                                    border-bottom: 1px solid #ddd;
                                    display: flex;
                                    align-items: center;
                                    font-size: 13px;
                                    font-weight: 500;
                                }

                                .tech-header {
                                    font-weight: bold;
                                    background: #fff;
                                    position: sticky;
                                    top: 0;
                                    z-index: 3;
                                    height: 50px;
                                    background: honeydew;
                                }

                                .timeline-scroll-area {
                                    overflow-x: auto;
                                    width: 100%;
                                    cursor: grab;
                                    user-select: none;
                                    position: relative;
                                }

                                .timeline-scroll-area:active {
                                    cursor: grabbing;
                                }

                                .timeline-inner {
                                    min-width: 2400px;
                                }

                                .time-label-row {
                                    display: flex;
                                    height: 50px;
                                    background: #fff;
                                    border-bottom: 2px solid #999;
                                    position: sticky;
                                    top: 0;
                                    z-index: 1;
                                    background: honeydew;
                                }

                                .time-label {
                                    min-width: 100px;
                                    text-align: center;
                                    font-size: 13px;
                                    font-weight: bold;
                                    color: #333;
                                    line-height: 50px;
                                    border-right: 1px solid #eee;
                                    user-select: none;
                                }

                                .workorder-row {
                                    display: flex;
                                    height: 50px;
                                    position: relative;
                                    background: #fff;
                                    border-bottom: 1px solid #ddd;
                                }

                                .workorder-block {
                                    position: absolute;
                                    color: #fff;
                                    border-radius: 4px;
                                    font-size: 12px;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    padding: 2px 4px;
                                }

                                .workorder-block small {
                                    font-size: 10px;
                                    color: #f1f1f1;
                                    display: block;
                                    line-height: 12px;
                                }

                                .workorder-block:hover {
                                    filter: brightness(85%);
                                }

                                .technician-info {
                                    display: flex;
                                    align-items: center;
                                    height: 50px;
                                }

                                .technician-photo {
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50%;
                                    object-fit: cover;
                                    margin-right: 8px;
                                }

                                .technician-text {
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: space-between;
                                    height: 30px;
                                    font-size: 12px;
                                    line-height: 1;
                                }

                                .technician-text small {
                                    font-size: 10px;
                                    color: #777;
                                }
                            </style>

                            <div class="timeline-wrapper">
                                <!-- Technician List -->
                                <div class="tech-names">
                                    <div class="tech-cell tech-header">Technician List</div>
                                    @foreach ($appointments as $group)
                                        @php
                                            $technician = $group->first()->technician;
                                            $image = $technician?->profile
                                                ? asset($technician->profile)
                                                : asset('assets/img/media/avatar2.webp');

                                            // Get shift ids as array
                                            $shiftIds = json_decode($technician->shift, true) ?? [];
                                            $shiftNames = \App\Models\ShiftMaster::whereIn('id', $shiftIds)
                                                ->pluck('title')
                                                ->toArray();
                                        @endphp

                                        <div class="tech-cell">
                                            <div class="technician-info">
                                                <img src="{{ $image }}" alt="Tech" class="technician-photo">
                                                <div class="technician-text">
                                                    <h5 class="mb-0">{{ $technician->first_name }}
                                                        {{ $technician->last_name }}
                                                    </h5>
                                                    @if (!empty($shiftNames))
                                                        <p>({!! implode(', ', $shiftNames) !!})</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Timeline Scroll Area -->
                                <div class="timeline-scroll-area" id="timelineScrollArea">
                                    <div class="timeline-inner">
                                        <!-- Time Labels -->
                                        <div class="time-label-row">
                                            @for ($i = 0; $i < 24; $i++)
                                                <div class="time-label">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</div>
                                            @endfor
                                        </div>

                                        <!-- Workorder Rows -->
                                        @foreach ($appointments as $group)
                                            @php
                                                $sortedAppointments = $group->sortBy('from_time');
                                                $rendered = [];
                                            @endphp
                                            <div class="workorder-row">
                                                @foreach ($sortedAppointments as $appt)
                                                    @php
                                                        $start = $appt->from_time;
                                                        $end = $appt->to_time;
                                                        $duration = (strtotime($end) - strtotime($start)) / 3600;
                                                        $left = timeToLeft($start);
                                                        $width = $duration * 100;
                                                        $workorderId = $appt->workorder->id ?? 0;
                                                        $status = $appt->workorder->status ?? 'N/A';
                                                        $bgColor = $workorderColors[$workorderId] ?? '#6c757d';

                                                        $topOffset = 10;
                                                        foreach ($rendered as $renderedAppt) {
                                                            if (
                                                                isOverlap(
                                                                    strtotime($start),
                                                                    strtotime($end),
                                                                    strtotime($renderedAppt['start']),
                                                                    strtotime($renderedAppt['end']),
                                                                )
                                                            ) {
                                                                $topOffset += 12;
                                                            }
                                                        }
                                                        $rendered[] = ['start' => $start, 'end' => $end];
                                                        $showStacked = $width < 80;
                                                    @endphp

                                                    <div class="workorder-block"
                                                        style="left: {{ $left }}px; width: {{ $width }}px; top: {{ $topOffset }}px; background-color: {{ $bgColor }};"
                                                        title="WO-{{ $workorderId }}: {{ $status }} ({{ $start }}–{{ $end }})">

                                                        @if ($showStacked)
                                                            <div>WO-{{ $workorderId }}</div>
                                                            <div style="font-size: 10px;">
                                                                <small>{{ $status }}</small>
                                                            </div>
                                                        @else
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span>WO-{{ $workorderId }}</span>
                                                                <small
                                                                    style="font-size: 10px;">{{ $status }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <script>
                                const scrollArea = document.getElementById('timelineScrollArea');
                                let isDown = false,
                                    startX, scrollLeft;

                                scrollArea.addEventListener('mousedown', (e) => {
                                    isDown = true;
                                    startX = e.pageX - scrollArea.offsetLeft;
                                    scrollLeft = scrollArea.scrollLeft;
                                });

                                scrollArea.addEventListener('mouseleave', () => isDown = false);
                                scrollArea.addEventListener('mouseup', () => isDown = false);

                                scrollArea.addEventListener('mousemove', (e) => {
                                    if (!isDown) return;
                                    e.preventDefault();
                                    const x = e.pageX - scrollArea.offsetLeft;
                                    scrollArea.scrollLeft = scrollLeft - (x - startX);
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('assets/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('assets/js/index.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.vmap.sampledata.js') }}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        (async () => {
            const topology = await fetch(
                'https://code.highcharts.com/mapdata/countries/ae/ae-all.topo.json'
            ).then(response => response.json());

            const data = [
                ['ae-az', 10],
                ['ae-du', 11],
                ['ae-sh', 12],
                ['ae-rk', 13],
                ['ae-uq', 14],
                ['ae-fu', 15],
                ['ae-740', 16],
                ['ae-aj', 17],
                ['ae-742', 18]
            ];

            Highcharts.mapChart('uaemap', {
                chart: {
                    map: topology,
                    events: {
                        load: function() {
                            this.series[0].points.forEach(point => {
                                const nameFixes = {
                                    "Dubay": "Dubai",
                                    "Umm Al Qaywayn": "Umm Al Quwain",
                                    "Ras Al Khaymah": "Ras Al Khaimah",
                                    "Fujayrah": "Fujairah"
                                };

                                if (nameFixes[point.name]) {
                                    point.name = nameFixes[point.name];
                                }
                            });

                            this.series[0].update({
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}'
                                }
                            });
                        }
                    }
                },

                title: {
                    text: null
                },

                subtitle: {
                    text: null
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom'
                    }
                },

                colorAxis: {
                    min: 0
                },

                series: [{
                    data: data,
                    name: 'Random data',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }]
            });

        })();
    </script>

    {{-- Technician Dashboard --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const workorderGroup = @json($workorderGroup);

        // Generate distinct HSL colors
        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = Math.floor((360 / count) * i);
                colors.push(`hsl(${hue}, 70%, 60%)`);
            }
            return colors;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const labels = [];
            const data = [];

            workorderGroup.forEach(item => {
                labels.push(item.status);
                data.push(item.total);
            });

            const backgroundColors = generateColors(labels.length);

            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'left',
                            labels: {
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const value = data.datasets[0].data[i];
                                            return {
                                                text: `${label} (${value})`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: data.datasets[0].backgroundColor[
                                                    i],
                                                lineWidth: 1,
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: "Today's Workorder Status Summary",
                            font: {
                                size: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label} (${context.raw})`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- Include Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- Swiper Initialization -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 5,
                }
            }
        });
    </script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Confirmed', 'Enroute', 'On Hold', 'Completed'],
                datasets: [{
                    label: '# status',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridDay',
                headerToolbar: {
                    left: 'prev,today,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                events: [{
                        title: 'busy',
                        start: '2025-03-03T08:00',
                        end: '2025-03-03T09:00',
                        color: '#eeeeee',
                        textColor: 'black'

                    },
                    {
                        title: 'workorder',
                        start: '2025-03-03T13:00',
                        end: '2025-03-03T16:00',
                        color: 'rgba(54, 162, 235, 0.2)',
                        textColor: 'black'

                    }
                ]
            });
            calendar.render();
        });
    </script>
    <script>
        var chartData = @json($chartData);

        // Define statuses and their colors
        const statusList = ['Confirmed', 'Pending', 'Invoiced', 'Completed'];

        const statusColors = {
            'Confirmed': '#036fe7',
            'Pending': '#f93a5a',
            'Invoiced': '#f7b731',
            'Completed': '#28a745'
        };

        // Build series and colors dynamically
        const series = statusList.map(status => ({
            name: status,
            data: chartData[status] || []
        }));

        const colors = statusList.map(status => statusColors[status]);

        // Chart options
        var optionsBar = {
            chart: {
                height: 249,
                type: 'bar',
                toolbar: {
                    show: true
                },
                fontFamily: 'Nunito, sans-serif'
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '52%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                endingShape: 'rounded',
                colors: ['transparent'],
            },
            series: series,
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            fill: {
                opacity: 1
            }
        };

        new ApexCharts(document.querySelector('#bar2'), optionsBar).render();
    </script>

    <script>
        // Get counts from Blade
        var booking = {{ $bookingCount ?? 0 }};
        var cancel = {{ $canceledCount ?? 0 }};

        // Calculate percentage
        var total = booking + cancel;
        var dynamicValue = total > 0 ? Math.round((booking / total) * 100) : 0;

        var options = {
            chart: {
                height: 250,
                type: 'radialBar',
            },
            series: [dynamicValue],
            colors: ['#22c03c'],
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '60%',
                    },
                    track: {
                        background: '#00b9ff',
                        strokeWidth: '100%',
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            fontSize: '24px',
                            color: '#333',
                            formatter: function(val) {
                                return val + '%';
                            }
                        }
                    },
                    stroke: {
                        dashArray: 4
                    }
                }
            },
        };
        var chart = new ApexCharts(document.querySelector('#chart2'), options);
        chart.render();
    </script>
@endsection
