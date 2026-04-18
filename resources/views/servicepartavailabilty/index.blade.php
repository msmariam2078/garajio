@extends('layouts.master')
@section('title','Adjustments Report')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
				<h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('servicepartadjustment.index') }}" class="text-dark">Services & Parts Adjustment</a>
                </h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

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
                <form method="GET" action="{{ url('/adjustment-search') }}" class="{{ Auth::user()->type == 'technician' ? 'd-none' : '' }}">
                    <div class="row justify-content-center mt-4"
                        style="position: absolute; left: 500px; top:-80px; z-index: 1000">
                        <div class="col-auto">
                            <select class="form-control" name="warehouse_id">
                                <option value="">Select warehouse</option>
                                @foreach ($warehouse as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('warehouse_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <select class="form-control" name="service_part_id">
                                <option value="">Select service part</option>
                                @foreach ($servicePart as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('service_part_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->item_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search pr-2"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
									<th class="d-none"></th>
									<th>Id</th>
                                    <th>{{ __('WareHouse') }}</th>
                                    <th>{{ __('Technician Name') }}</th>
                                    <th>{{ __('Service and Part') }}</th>
                                    <th>{{ __('Service and Part No.') }}</th>
                                    <th>{{ __('ON Hand') }}</th>
                                    <th>{{ __('Sold Out') }}</th>
                                    <th>{{ __('Commited') }}</th>
                                    <th>{{ __('Available') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($adjustments->sortByDesc('id')->values() as $key => $adjustment)
                                    <tr>
										<td class="d-none"></td>
										<td>#SP-{{ $adjustment->ids }}</td>

                                        <td>{{ $adjustment->warehouse ? $adjustment->warehouse?->name : 'N/A' }}</td>
                                        <td>{{ $adjustment->warehouse ? $adjustment->warehouse?->user?->full_name : 'N/A' }}</td>
                                        <td>{{ $adjustment->servicePart ? $adjustment->servicePart?->product_name : 'N/A' }}
                                        </td>
                                        <td>{{ $adjustment->servicePart ? $adjustment->servicePart?->item_no : 'N/A' }}
                                        </td>

                                        <td>{{ $adjustment->onhand }}</td>
                                        <td>{{ $adjustment->unavailable }}</td>
                                        <td>{{ $adjustment->commited }}</td>
                                        <td>{{ $adjustment->onhand - $adjustment->unavailable - $adjustment->commited }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
    </div>
@endsection
@section('js')
@endsection
