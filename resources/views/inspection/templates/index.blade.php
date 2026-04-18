@extends('layouts.master')
@section('title', 'Inspection Template')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Inspection Template</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-primary"><a href="{{ route('inspection.templatescreate') }}"
                            class='text-white'>Create Inspection template</a></button>
                </div>
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
						<table class="table table-bordered datatbl-advance">
                            <thead>
                                <tr>
									<th class="d-none"></th>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($templates->sortByDesc('id')->values() as $key => $template)
                                    <tr>
										<td class="d-none"></td>
                                        <td>#TEM-{{ $template->id }}</td>
                                        <td>{{ $template->code }}</td>
                                        <td>{{ $template->des }}</td>
                                        <td class="text-center">
                                            <a class="" href="{{ route('inspection.templatesedit', $template->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            <a class="" href="#" data-toggle="modal"
                                                data-target="#delete_template{{ $template->id }}">
                                                <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                        </td>
                                    </tr>
                                    @include('inspection.templates.delete')
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
