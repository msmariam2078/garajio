@php
$users = \Auth::user();
$languages = \App\Models\Custom::languages();
$userLang = \Auth::user()->lang;
$profile = asset(Storage::url('upload/profile'));
$data = '';
@endphp


@push('script-page')
<script>
$(document).ready(function() {
    $('#search-query').on('keyup', function(e) {
        let query = $(this).val();

        if (query.length > 0) {
            $.ajax({
                url: "{{ route('search') }}",
                type: 'GET',
                data: {
                    query: query
                },
                success: function(response) {
                    $('#search-results').empty();
                    $('#search-results').append(response.html);
                    $('#search-results').show();
                }
            });
        }
    });
});
</script>
@if(auth()->user()->type == 'technician')
<script async src="https://maps.googleapis.com/maps/api/js?key={{ googleApiKey() }}&loading=async&libraries=places">
</script>
<script>
function initMap(lat, long, callback) {
    const latlng = {
        lat: lat,
        lng: long
    };
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        location: latlng
    }, (results, status) => {
        if (status === "OK" && results[0]) {
            callback(results[0].formatted_address);
        } else {
            callback(false);
        }
    });
}


document.getElementById('checkin-button').addEventListener('click', function() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const long = position.coords.longitude;
            initMap(lat, long, function(workingAddress) {
                if (workingAddress) {

                    fetch('{{ route('
                            checkin ') }}', {

                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    lat: lat,
                                    long: long,
                                    working_address: workingAddress
                                })
                            })
                        .then(response => response.json())
                        .then(data => {
                            toastrs('Success', data.message, 'success');
                        })
                        .catch(error => {
                            toastrs('Error', 'Check-in failed. Please try again.', 'error');
                        });
                } else {
                    toastrs('Error', 'Geocoding failed. Please try again.', 'error');
                }
            });
        });
    } else {
        toastrs('Error', 'Geolocation is not supported by this browser.', 'error');
    }
});

document.getElementById('checkout-button').addEventListener('click', function() {
    fetch('{{ route("checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            toastrs('Success', data.message, 'success');
        }).catch(error => {
            toastrs('Error', 'Check-out failed. Please try again.', 'error');
        });
});
</script>
@endif
@endpush
<style>
.dropdown-menu {
    min-width: 500px;
    padding-top: 30px;
    padding-bottom: 30px;
}

.dropdown-item {
    padding-top: 10px;
}

@media (min-width: 767.98px) {

    #client-search {
        width: 300px;
        /* max-width: 300px; */
    }
}
</style>


<!-- Header Start-->
<header class="codex-header">
    <div class="header-contian d-flex justify-content-between align-items-center">
        <div class="header-left d-flex align-items-center">
            <div class="sidebar-action navicon-wrap"><i data-feather="menu"></i></div>
            <ul class="nav-iconlist">
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">

                        <span class="align-middle d-none d-sm-inline-block">{{ ucfirst($userLang) }}</span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu dropdown-menu-animated topbar-dropdown-menu">
                        @foreach ($languages as $language)
                        @if ($language != 'en')
                        <a href="{{ route('language.change', $language) }}" class="dropdown-item notify-item">
                            <span class="align-middle">{{ ucfirst($language) }}</span>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </li>
            </ul>
            @if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'owner')
            <div class="dropdown ms-4">
                <div class="input-group" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true">
                    <span class="input-group-text my-2"><i class="fa fa-search"></i></span>
                    <input type="search" class="form-control my-2" id="client-search"
                        placeholder="Search Client's name, email, phonenumber">
                </div>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="search-results"
                    style="width: 500px;">
                    <li class="dropdown-item">{{ __('No client found.') }}</li>
                </ul>
            </div>
            @endif




            @if(auth()->user()->type == 'technician')
            <div class="d-flex">
                <button id="checkin-button" class="btn text-primary border ms-2">{{ __('In') }}</button>
                <button id="checkout-button" class="btn text-danger border ms-2">{{ __('Out') }}</button>
            </div>
            @endif
        </div>
        <div class="header-right d-flex align-items-center justify-content-center">
            <a href="{{route('calendar')}}" class='me-3'><img src='imgs/calendar.svg'
                    style='width:30px;height:34px;' /></a>
            <div class="dropdown">
                <button class=" me-3" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                    aria-expanded="false" style='border:none;background:transparent;'>
                    <img src='imgs/bell.svg' style='width:30px;height:34px;' />
                </button>

                <ul class="dropdown-menu " aria-labelledby="dropdownMenuButton1">
                    <h2 class='ps-3 text-black-50 '>Notifications - <span style='color:red;'>2</span></h2>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class='d-flex align-items-center justify-content-between'>
                                <div>
                                    <h4 class='my-3'>Samso aliao</h4>
                                    <p>Samso Nagaro Like your home work</p>
                                </div>
                                <div class='text-black-50'>2 hours ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class='d-flex align-items-center justify-content-between'>
                                <div>
                                    <h4 class='my-3'>Samso aliao</h4>
                                    <p>Samso Nagaro Like your home work</p>
                                </div>
                                <div class='text-black-50'>2 hours ago</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class='d-flex align-items-center justify-content-between'>
                                <div>
                                    <h4 class='my-3'>Samso aliao</h4>
                                    <p>Samso Nagaro Like your home work</p>
                                </div>
                                <div class='text-black-50'>2 hours ago</div>
                            </div>
                        </a>
                    </li>
                </ul>


            </div>





            <ul class="nav-iconlist">
                @if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'owner')
                <li data-bs-toggle="tooltip" data-bs-original-title="{{ __('Theme Settings') }}"
                    data-bs-placement="bottom">
                    <div class="navicon-wrap customizer-action"><i class="fa fa-cog"></i></div>
                </li>
                @endif
                <li class="nav-profile">
                    <div class="media">
                        <div class="user-icon"><img class="img-fluid rounded-50"
                                src="{{ !empty($users->profile) ? $profile . '/' . $users->profile : $profile . '/avatar.png' }}"
                                alt="logo"></div>
                        <div class="media-body">
                            <h6>{{ \Auth::user()->name }}</h6><span class="text-light">{{ \Auth::user()->type }}</span>
                        </div>
                    </div>
                    <div class="hover-dropdown navprofile-drop">
                        <ul>
                            <li><a href="{{ route('setting.account') }}"><i class="ti-user"></i>{{ __('Profile') }}</a>
                            </li>
                            @if (Gate::check('manage account settings'))
                            <li>
                                <a href="{{ route('setting.account') }}">{{ __('Account Setting') }}</a>
                            </li>
                            @endif
                            @if (Gate::check('manage password settings'))
                            <li>
                                <a href="{{ route('setting.password') }}">{{ __('Password Setting') }}</a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"><i
                                        class="fa fa-sign-out"></i>{{ __('Logout') }}</a>
                                <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- Header End-->