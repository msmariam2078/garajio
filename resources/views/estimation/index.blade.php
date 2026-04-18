@extends('layouts.master')
@section('title', 'Quotation')
@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Quotation</h4>
            <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <div class="mb-3 mb-xl-0">
            <!-- <a class="btn btn-primary btn-sm ml-20" href="{{ route('estimation.create') }}"> <i
                    class="ti-plus mr-5"></i>
                {{__('Create Quotation')}}
            </a> -->
        </div>
    </div>
</div>
@endsection

@section('content')
@include('messages_alert')
<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap datatbl-advance" >
                        <thead>
                            <tr>
								<th class="d-none"></th>
                                <th>{{ __('Quotation ID') }}</th>
                                <th>{{ __('Quotation Date') }}</th>
                                <th>{{ __('Booking ID') }}</th>
                                <th>{{ __('Client') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Status') }}</th>
								<th>{{__('Agent')}}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estimations->sortByDesc('id')->values() as $key => $item)
                            <tr>
                                <td class="d-none"></td>
                                <td class="text-center"> #QU-{{$item->id}}</td>
                                <td>{{ $item->send_date }}</td>
                                <td>{{ $item->booking ? '#BO' . $item->booking->id : 'N/A' }}</td>
                                <td>{{ $item->customer ? $item->customer->first_name . ' ' . $item->customer->last_name : 'N/A' }}
                                </td>
                                <td>{{$item->items_sum_totalamount}}</td>
                                <td>{{ $item->due_date ?? '--' }}</td>
                                <td>
                                    @if($item->status == 'Pending')
                                    <span class="badge badge-warning" style="width:75px;">{{ $item->status }}</span>
                                    @elseif($item->status == 'New')
                                    <span class="badge badge-secondary" style="width:75px;">{{ $item->status }}</span>
                                    @elseif($item->status == 'cancelled')
                                    <span class="badge badge-danger" style="width:75px;">Cancelled</span>
                                    @elseif($item->status == 'accepted')
                                    <span class="badge badge-primary" style="width:75px;">Accepted</span>
                                    @elseif($item->status == 'confirmed'||$item->status == 'Confirmed')
                                    <span class="badge badge-success" style="width:75px;">Confirmed</span>
                                    @else
                                    <span class="badge badge-light" style="width:75px;">{{ $item->status }}</span>
                                    @endif
                                </td>
								<td>{{$item->agent->first_name}}</td>
                                <td>
                					@if (Gate::check('view quotation'))
                                    	<a href="{{ route('estimation.show',   \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}"
                                        class="btn btn-sm btn-warning">{{ __('View') }}</a>
                                    @endcan    
                                    @if (Gate::check('view quotation'))
                                    	<a href="{{ url('booking/'.$item->booking_id.'/edit') }}"
                                        class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                                    @endcan                               

                                    @can('delete quotation')
										<form action="{{ route('estimation.destroy', $item->id) }}" method="POST"
											style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
										</form>
                                    @endcan
									<a href="{{ url('estimation.email', $item->id) }}" class="btn btn-sm btn-info text-white">
										<i class="fas fa-share"></i>
										<i class="far fa-envelope pl-2"></i>
									</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection