@extends('layouts.master')

@section('title', 'Inspection Group')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Inspection Group</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insgroupModal">Create
                        Inspection group</button>

                </div>
            </div>
        </div>
        @include('inspection.group.create')
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->
    <div class="row row-sm">

        <div class="col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatbl-advance">
                            <thead>
                                <tr>
									<th class="d-none"></th>
                                    <th>{{ __('Group ID') }}</th>
                                    <th>{{ __('Group Code') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($groups->sortByDesc('id')->values() as $key => $group)
                                    <tr>
										<th class="d-none"></th>
                                        <th>#GR-{{ $group->id }}</th>
                                        <th>{{ $group->code }}</th>
                                        <th>{{ $group->des }}</th>
                                        <td class="text-center">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['inspection.groupdestroy', $group->id]]) !!}
												<a class="" href="#" data-toggle="modal"
													data-target="#update_group{{ $group->id }}">
													<img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
														style='width:25px;height:25px;'>
												</a>

												<a class="text-danger confirm_dialog mx-3" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}" href="#">
													<img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
														style='width:25px;height:25px;'>
												</a>

                                                <a class="btn btn-sm btn-outline-primary"
                                                    href="{{ route('inspection.grouppoints', ['id' => $group->id]) }}">
                                                    Points
                                                </a>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @include('inspection.group.edit')
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->




    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')

    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
