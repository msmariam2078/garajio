@extends('layouts.master')
@section('title','Reset Password')

@section('css')
    <!-- Notify Plugin -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between align-items-center">
        <div>
            <h4 class="content-title mb-2">Reset password</h4>
        </div>
        @can('create role')
            <div>
                <a href="{{ route('role.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create Role
                </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    @include('messages_alert')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Reset password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ url('reset-password-now') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="title" class="form-label fw-bold">Role type</label>
                                <select class="custom-select mr-sm-2" id="selectRole">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $item)		
										@if (auth()->user()->type === 'super admin' || $item->name !== 'super admin')									
                                        	<option value="{{ $item->name }}">{{ $item->name }}</option>
										@endif
									@endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-none" id="emailList">
                                <label for="title" class="form-label fs-5">Select email</label>
                                <select class="custom-select mr-sm-2" id="selectEmail">
                                    <option value="">Select role</option>
                                </select>
                            </div>

                            <input type="hidden" name="user_id" id="user_id">

                            <div class="col-md-3 d-none" id="resetSection">
                                <label for="password" class="form-label fs-5">New password</label>
                                <input type="text" name="password" id="password" class="form-control" placeholder="Password">
                            </div>

                            <div class="col-md-3 d-none" id="resetButton">
                                <button type="submit" class="btn btn-success btn-block mt-4">Reset now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#selectRole').on('change', function() {
            var type = $(this).val();
            $('#selectEmail').html('<option value="">Loading...</option>');
            $('#resetSection, #resetButton').addClass('d-none'); // hide reset fields initially

            if (type) {
                $.ajax({
                    url: '/get-email',
                    type: 'GET',
                    data: {
                        type: type
                    },
                    success: function(response) {
                        $('#emailList').removeClass('d-none').addClass('d-block');
                        $('#selectEmail').empty().append('<option value="">Select email</option>');
                        $.each(response, function(key, value) {
                            $('#selectEmail').append('<option value="' + value.id + '">' + value
                                .email + '</option>');
                        });
                    }
                });
            } else {
                $('#emailList').removeClass('d-block').addClass('d-none');
            }
        });

        $('#selectEmail').on('change', function() {
            var emailId = $(this).val();

            if (emailId) {
                $('#user_id').val(emailId); // 👈 set selected user_id to hidden input
                $('#resetSection, #resetButton').removeClass('d-none').addClass('d-block');
            } else {
                $('#user_id').val('');
                $('#resetSection, #resetButton').addClass('d-none');
            }
        });
    </script>
@endsection
