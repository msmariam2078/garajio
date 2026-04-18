@extends('layouts.master')
@section('css')

    <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
	
    <link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
 
    @endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto"> {{__('Technician')}}</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/ profile</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
					
				
                  
					                </div>
              
                    
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')


<div class="row">

    <div class="col-md-12">
        <div class="card">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- ID -->
                    <div class="form-group">
                        <label for="id">{{ __('ID') }}</label>
                        <input type="text" id="id" class="form-control"
                            value="{{ technicianPrefix() }}{{ $technician->id }}" readonly>
                    </div>

                    <!-- Profile Picture -->
                    <div class="form-group">
                        <label for="profile">{{ __('Profile Picture') }}</label>
                        <img src="{{ asset($technician->profile) }}" alt="{{ $technician->full_name }}'s picture"
                            class="img-thumbnail" style="width: 100px; height: auto;">

                       
                    </div>
                    <div class="col-sm-12 col-md-12 mb-4">
                       
                        <input type="file" name='profile' class="dropify" data-height="100" id="profile" />
									
                    </div>
                   <div class='row'>
                    <!-- Name -->
                    <div class="form-group col-md-6">
                        <label for="full_name">{{ __('Name') }}</label>
                        <input type="text" id="full_name" name="full_name" class="form-control"
                            value="{{ $technician->full_name }}" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group col-md-6">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ $technician->email }}" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group col-md-6">
                        <label for="phone_number">{{ __('Phone Number') }}</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control"
                            value="{{ $technician->phone_number ?? '-' }}">
                    </div>

                    <!-- Skill Group -->
                    <div class="form-group col-md-6">
                        <label for="tech_skillgroup">{{ __('Skill Group') }}</label>
                        <input type="text" id="tech_skillgroup" name="tech_skillgroup" class="form-control"
                            value="{{ $technician->tech_skillgroup->group_name ?? '-' }}">
                    </div>

                    <!-- Address -->
                    <div class="form-group col-md-6">
                        <label for="service_address">{{ __('Address') }}</label>
                        <input type="text" id="service_address" name="service_address" class="form-control"
                            value="{{ $technician->clients->service_address ?? '-' }}">
                    </div>

                    <!-- Status -->
                    <div class="form-group col-md-6">
                        <label for="status">{{ __('Status') }}</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" {{ $technician->status == 'active' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="inactive" {{ $technician->status == 'inactive' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                        </select>
                    </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group row d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <form action="{{ route('technician.update-hours', $technician->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- ID -->
                    <div class="form-group">
                        <label for="id">{{ __('ID') }}</label>
                        <input type="text" id="id" class="form-control"
                            value="{{ technicianPrefix() }}{{ $technician->id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="days">{{ __('Select Days of the Week') }}</label>
                        <select id="days" name="days" class="form-control">
                            <option value="monday">{{ __('Monday') }}</option>
                            <option value="tuesday">{{ __('Tuesday') }}</option>
                            <option value="wednesday">{{ __('Wednesday') }}</option>
                            <option value="thursday">{{ __('Thursday') }}</option>
                            <option value="friday">{{ __('Friday') }}</option>
                            <option value="saturday">{{ __('Saturday') }}</option>
                            <option value="sunday">{{ __('Sunday') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="from_time">{{ __('From Time') }}</label>
                        <input type="time" id="from_time" name="from_time" class="form-control">
                    </div>

                    <!-- To Time -->
                    <div class="form-group">
                        <label for="to_time">{{ __('To Time') }}</label>
                        <input type="time" id="to_time" name="to_time" class="form-control">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ __('Update Working Hours') }}</button>
                    </div>
                </div>
            </form>
        </div>



    </div>
    <div class="col-md-6">
        <div class="card ">
            <div class="card-body">
                <h5>{{ __('Work Hours') }}</h5>
                @if($workHours->isEmpty())
                <p>{{ __('No work hours recorded for this technician.') }}</p>
                @else
              <div class="table-responsive">
                                <table class="table mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>{{ __('Day') }}</th>
                            <th>{{ __('From Time') }}</th>
                            <th>{{ __('To Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workHours as $workHour)
                        <tr>
                            <td>{{ ucfirst($workHour->workingday) }}</td>
                            <td>{{ $workHour->start_time }}</td>
                            <td>{{ $workHour->end_time }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>



<script>
$(document).ready(function() {
    $('#select').selectize();
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    function getCurrentTime() {
        const now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }


    document.getElementById('from_time').value = getCurrentTime();
    document.getElementById('to_time').value = getCurrentTime();
});
</script>


























@endsection
@section('js')
 
 

    <!--Internal  Notify js -->
    <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

    <script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>

@endsection