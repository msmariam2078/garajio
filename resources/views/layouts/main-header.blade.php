<style>
    .dropdown-menu {
        height: 300px;
        width: 400px;
        overflow-y: auto;
        padding-top: 40p;
    }

    .cu .dropdown-menu {
        height: 175px;
    }

    @media (min-width: 767.98px) {

        #search-query {
            width: 300px;
            /* max-width: 300px; */
        }
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }
</style>
@php
    $ids = parentId();
    $authUser = \App\Models\User::find($ids);
    $subscription = \App\Models\Subscription::find($authUser->subscription);
    $settings = settings();
    $companyLogo = $settings['company_logo'] ?? 'default-logo.png'; // Provide a fallback
@endphp
<!-- main-header -->


<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="{{ url('/') }}"><img src="{{ URL::asset('upload/logo/2_logo.jpeg') }}" class="logo-1"
                        alt="logo"></a>
                <a href="{{ url('/') }}"><img src="{{ URL::asset('upload/logo/2_logo.jpeg') }}" class="dark-logo-1"
                        alt="logo"></a>
                <a href="{{ url('/') }}"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="logo-2"
                        alt="logo"></a>
                <a href="{{ url('/') }}"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}"
                        class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>


            </div>

            {{-- <div class="main-header-center ml-3 d-sm-none d-md-none d-lg-flex align-items-center">
                <input class="form-control" placeholder="Search for anything..." type="search" id="search-query">
                <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>

                <ul class="dropdown-menu rounded" aria-labelledby="dropdownMenuButton1" id="search-results">
                    <li class="dropdown-item">{{ __('No client found.') }}</li>
                </ul>
            </div> --}}

            <div class="main-header-center ml-3 d-sm-none d-md-none d-lg-flex align-items-center">
                <input class="form-control" placeholder="Search for anything..." type="search" id="search-query"
                    autocomplete="off">
                <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>

                <ul class="dropdown-menu rounded" id="search-results">
                    <li class="dropdown-item">No client found.</li>
                </ul>
            </div>

            @if (\Auth::user()->type !== 'technician')
                <div class="dropdown nav-ite main-header-notification">
                    <a class="new nav-link" href="#">
                        <i class="fas fa-plus text-danger"></i>
                    </a>
                    <div class="dropdown-menu"
                        style="height: 150px !important; width: 200px !important; position: absolute; left:0">
                        <div class="main-message-list chat-scroll">
                            <a class="d-flex p-1" href="{{ route('booking.create') }}">
                                <div>
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h5 class="m-2 pl-3">Booking</h5>
                            </a>

                            <a class="d-flex p-1 customModal" data-url="{{ route('client.create') }}"
                                data-title="{{ __('Create Client') }}">
                                <div>
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <h5 class="m-2 pl-3">Customer</h5>
                            </a>



                            <a class="d-flex p-1 customModal" data-url="{{ route('vehicle.create') }}"
                                data-title="{{ __('Create Vehicle') }}">
                                <div>
                                    <i class="fas fa-car"></i>
                                </div>
                                <h5 class="m-2 pl-3">Vehicle</h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="main-header-right">
            <div class="ml-3">
                <a href="{{ route('clear') }}" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Clear Cache
                </a>
            </div>

            

            @if (auth::user()->type == 'technician')
                <div class="header-element notifications-dropdown main-header-notification">
                    <a href="javascript:void(0);" class="header-link" style="position: relative;" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="messageDropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px"
                            viewBox="0 0 24 24" width="24px" fill="currentColor">
                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                            <path
                                d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z">
                            </path>
                        </svg>
                        <span class="pulse-success"></span>
                        <span id="notificationCount" class="badge bg-warning rounded-pill notification-badge"
                            style="position: absolute; top: 0px; right: -1px; font-size: 10px; "></span>
                    </a>

                    <div class="main-header-dropdown dropdown-menu dropdown-menu-end main-header-message">
                        <div class="menu-header-content bg-info text-fixed-white">
                            <h6 class="mb-0 fs-15 fw-semibold text-fixed-white">Notifications</h6>
                            <p id="notificationText" class="dropdown-title-text subtext mb-0 text-fixed-white op-6 pb-0 fs-12">Loading...</p>
                        </div>

                        <ul class="list-unstyled mb-0" id="tasksList" style="max-height: 300px; overflow-y: auto;">
                            <!-- Work orders will be dynamically loaded here -->
                            <div id="group1"></div>
                            <div id="group2"></div>
                        </ul>

                        <div class="text-center dropdown-footer">
                            <a href="/home" class="text-primary fs-13">VIEW ALL</a>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth::user()->type == 'super admin' || auth::user()->type == 'owner')
                <div class="header-element notifications-dropdown main-header-notification">
                    <a href="javascript:void(0);" class="header-link" style="position: relative;" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="messageDropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px"
                            viewBox="0 0 24 24" width="24px" fill="currentColor">
                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                            <path
                                d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z">
                            </path>
                        </svg>
                        <span class="pulse-success"></span>
                        <span id="notificationCount" class="badge bg-warning rounded-pill notification-badge"
                            style="position: absolute; top: 0px; right: -1px; font-size: 10px; "></span>
                    </a>

                    <div class="main-header-dropdown dropdown-menu dropdown-menu-end main-header-message">
                        <div class="menu-header-content bg-info text-fixed-white">
                            <h6 class="mb-0 fs-15 fw-semibold text-fixed-white">Notifications</h6>
                            <p id="notificationText" class="dropdown-title-text subtext mb-0 text-fixed-white op-6 pb-0 fs-12">Loading...</p>
                        </div>

                        <ul class="list-unstyled mb-0" id="tasksList" style="max-height: 300px; overflow-y: auto;">
                            <!-- Work orders will be dynamically loaded here -->
                            <div id="group1"></div>
                            <div id="group2"></div>
                        </ul>

                        <div class="text-center dropdown-footer">
                            <a href="/home" class="text-primary fs-13">VIEW ALL</a>
                        </div>
                    </div>
                </div>

            @endif

            <ul class="nav">
                <li class="">
                    <div class="dropdown  nav-itemd-none d-md-flex">
                        <a href="#" class="d-flex  nav-item nav-link pr-0 country-flag1" data-toggle="dropdown"
                            aria-expanded="false">
                            <span class="avatar country-Flag mr-0 align-self-center bg-transparent"><img
                                    src="{{ URL::asset('assets/img/flags/us_flag.jpg') }}" alt="img"></span>
                            <div class="my-auto">
                                <strong class="mr-2 ml-2 my-auto">English</strong>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" x-placement="bottom-end"
                            style="height: auto !important; width: 180px !important;">
                            <a href="#" class="dropdown-item d-flex ">
                                <span class="avatar  mr-3 align-self-center bg-transparent"><img
                                        src="{{ URL::asset('assets/img/flags/french_flag.jpg') }}"
                                        alt="img"></span>
                                <div class="d-flex">
                                    <span class="mt-2">French</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex">
                                <span class="avatar  mr-3 align-self-center bg-transparent"><img
                                        src="{{ URL::asset('assets/img/flags/germany_flag.jpg') }}"
                                        alt="img"></span>
                                <div class="d-flex">
                                    <span class="mt-2">Germany</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex">
                                <span class="avatar mr-3 align-self-center bg-transparent"><img
                                        src="{{ URL::asset('assets/img/flags/italy_flag.jpg') }}"
                                        alt="img"></span>
                                <div class="d-flex">
                                    <span class="mt-2">Italy</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex">
                                <span class="avatar mr-3 align-self-center bg-transparent"><img
                                        src="{{ URL::asset('assets/img/flags/russia_flag.jpg') }}"
                                        alt="img"></span>
                                <div class="d-flex">
                                    <span class="mt-2">Russia</span>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item d-flex">
                                <span class="avatar  mr-3 align-self-center bg-transparent"><img
                                        src="{{ URL::asset('assets/img/flags/spain_flag.jpg') }}"
                                        alt="img"></span>
                                <div class="d-flex">
                                    <span class="mt-2">spain</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="nav nav-item  navbar-nav-right ml-auto">
                <!-- <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default nav-link resp-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="dropdown nav-item main-header-message ">
                    <a class="new nav-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg><span class=" pulse-danger"></span></a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-left">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Messages</h6>
                                <span class="badge badge-pill badge-warning ml-auto my-auto float-right">Mark All
                                    Read</span>
                            </div>
                            <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread
                                messages</p>
                        </div>
                        <div class="main-message-list chat-scroll">
                            <a href="#" class="p-3 d-flex border-bottom">
                                <div class="  drop-img  cover-image  "
                                    data-image-src="{{ URL::asset('assets/img/faces/3.jpg') }}">
                                    <span class="avatar-status bg-teal"></span>
                                </div>
                                <div class="wd-90p">
                                    <div class="d-flex">
                                        <h5 class="mb-1 name">Petey Cruiser</h5>
                                    </div>
                                    <p class="mb-0 desc">I'm sorry but i'm not sure how to help you with that......</p>
                                    <p class="time mb-0 text-left float-left ml-2 mt-2">Mar 15 3:55 PM</p>
                                </div>
                            </a>
                            <a href="#" class="p-3 d-flex border-bottom">
                                <div class="drop-img cover-image"
                                    data-image-src="{{ URL::asset('assets/img/faces/2.jpg') }}">
                                    <span class="avatar-status bg-teal"></span>
                                </div>
                                <div class="wd-90p">
                                    <div class="d-flex">
                                        <h5 class="mb-1 name">Jimmy Changa</h5>
                                    </div>
                                    <p class="mb-0 desc">All set ! Now, time to get to you now......</p>
                                    <p class="time mb-0 text-left float-left ml-2 mt-2">Mar 06 01:12 AM</p>
                                </div>
                            </a>
                            <a href="#" class="p-3 d-flex border-bottom">
                                <div class="drop-img cover-image"
                                    data-image-src="{{ URL::asset('assets/img/faces/9.jpg') }}">
                                    <span class="avatar-status bg-teal"></span>
                                </div>
                                <div class="wd-90p">
                                    <div class="d-flex">
                                        <h5 class="mb-1 name">Graham Cracker</h5>
                                    </div>
                                    <p class="mb-0 desc">Are you ready to pickup your Delivery...</p>
                                    <p class="time mb-0 text-left float-left ml-2 mt-2">Feb 25 10:35 AM</p>
                                </div>
                            </a>
                            <a href="#" class="p-3 d-flex border-bottom">
                                <div class="drop-img cover-image"
                                    data-image-src="{{ URL::asset('assets/img/faces/12.jpg') }}">
                                    <span class="avatar-status bg-teal"></span>
                                </div>
                                <div class="wd-90p">
                                    <div class="d-flex">
                                        <h5 class="mb-1 name">Donatella Nobatti</h5>
                                    </div>
                                    <p class="mb-0 desc">Here are some products ...</p>
                                    <p class="time mb-0 text-left float-left ml-2 mt-2">Feb 12 05:12 PM</p>
                                </div>
                            </a>
                            <a href="#" class="p-3 d-flex border-bottom">
                                <div class="drop-img cover-image"
                                    data-image-src="{{ URL::asset('assets/img/faces/5.jpg') }}">
                                    <span class="avatar-status bg-teal"></span>
                                </div>
                                <div class="wd-90p">
                                    <div class="d-flex">
                                        <h5 class="mb-1 name">Anne Fibbiyon</h5>
                                    </div>
                                    <p class="mb-0 desc">I'm sorry but i'm not sure how...</p>
                                    <p class="time mb-0 text-left float-left ml-2 mt-2">Jan 29 03:16 PM</p>
                                </div>
                            </a>
                        </div>
                        <div class="text-center dropdown-footer">
                            <a href="text-center">VIEW ALL</a>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="dropdown nav-item main-header-notification">
                    <a class="new nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class=" pulse"></span></a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-left">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Notifications</h6>
                                <span class="badge badge-pill badge-warning ml-auto my-auto float-right">Mark All
                                    Read</span>
                            </div>
                            <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread
                                Notifications</p>
                        </div>
                        <div class="main-notification-list Notification-scroll">
                            <a class="d-flex p-3 border-bottom" href="#">
                                <div class="notifyimg bg-pink">
                                    <i class="la la-file-alt text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">New files available</h5>
                                    <div class="notification-subtext">10 hour ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                            <a class="d-flex p-3" href="#">
                                <div class="notifyimg bg-purple">
                                    <i class="la la-gem text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">Updates Available</h5>
                                    <div class="notification-subtext">2 days ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                            <a class="d-flex p-3 border-bottom" href="#">
                                <div class="notifyimg bg-success">
                                    <i class="la la-shopping-basket text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">New Order Received</h5>
                                    <div class="notification-subtext">1 hour ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                            <a class="d-flex p-3 border-bottom" href="#">
                                <div class="notifyimg bg-warning">
                                    <i class="la la-envelope-open text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">New review received</h5>
                                    <div class="notification-subtext">1 day ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                            <a class="d-flex p-3 border-bottom" href="#">
                                <div class="notifyimg bg-danger">
                                    <i class="la la-user-check text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">22 verified registrations</h5>
                                    <div class="notification-subtext">2 hour ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                            <a class="d-flex p-3 border-bottom" href="#">
                                <div class="notifyimg bg-primary">
                                    <i class="la la-check-circle text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h5 class="notification-label mb-1">Project has been approved</h5>
                                    <div class="notification-subtext">4 hour ago</div>
                                </div>
                                <div class="ml-auto">
                                    <i class="las la-angle-right text-right text-muted"></i>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown-footer">
                            <a href="">VIEW ALL</a>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                            class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg></a>
                </div> -->
                <div class="cu dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href="">
                        @if (\Auth::user())
                            <img alt="user-img"
                                src="{{ URL::asset(\Auth::user()->profile ?? 'assets/img/media/admin.jpg') }}">
                        @else
                            <img alt="user-img" src="{{ URL::asset('assets/img/media/admin.jpg') }}">
                        @endif
                    </a>

                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user">
                                    @if (\Auth::user())
                                        <img alt="user-img"
                                            src="{{ URL::asset(\Auth::user()->profile ?? 'assets/img/media/admin.jpg') }}">
                                    @else
                                        <img alt="user-img" src="{{ URL::asset('assets/img/media/admin.jpg') }}">
                                    @endif
                                </div>
                                <div class="ml-3 my-auto">
                                    <h6>{{ Auth::user()->first_name }}</h6><span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('personalinfo') }}"><i
                                class="bx bx-user-circle"></i>Profile</a>


                        @if (auth('web')->check())
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();"><i
                                        class="bx bx-log-out"></i>Sign Out</a>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="dropdown main-header-message right-toggle d-none">
                    <a class="nav-link pr-0" data-toggle="sidebar-right" data-target=".sidebar-right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                        <!-- <span id="comment-count" class="badge badge-danger">
                            {{ session('comment_count', 0) }}
                        </span> -->
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /main-header -->

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 1000; // Delay in ms

        $('#search-query').on('keyup', function(e) {
            clearTimeout(typingTimer);
            let query = $(this).val();

            if (query.length > 0) {
                typingTimer = setTimeout(function() {
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
                }, typingDelay);
            } else {
                $('#search-results').hide();
            }
        });

        // Hide dropdown if click outside
        $(document).click(function(e) {
            var container = $("#search-results");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });

        // Optional: Show again if focused and already has results
        $('#search-query').on('focus', function() {
            if ($('#search-results').children().length > 0) {
                $('#search-results').show();
            }
        });
    });
</script>

@if (auth::user()->type == 'super admin' || auth::user()->type == 'owner')
<script type="module">
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
    import { getDatabase, ref, onChildAdded, remove } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js';

    document.addEventListener('DOMContentLoaded', () => {
        // Firebase configuration
        const firebaseConfig = {
            databaseURL: '{{ config("firebase.database_url") }}'
        };

        // Ensure Firebase URL exists
        if (!firebaseConfig.databaseURL) {
            console.error("❌ Firebase configuration missing or invalid.");
            return;
        }
        

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);

        // Reference to alerts
        const alertsRef = ref(database, 'alerts');
        
        loadInspectionComplete();
        loadWorkComplete();
        // Listen for new alerts
        onChildAdded(alertsRef, (snapshot) => {
            const alert = snapshot.val();
            const alertKey = snapshot.key;
            
            if (alert.type === 'admin_alert' && alert.recipient_id == "{{ auth()->id() }}") {
                const audio = new Audio("{{ asset('assets/sounds/ding-alert-2.mp3') }}");
                audio.volume = 0.8;
                audio.play();
                loadInspectionComplete();
                loadWorkComplete();
                // Remove alert from Firebase once shown
                remove(ref(database, `alerts/${alertKey}`))
                    .catch((err) => console.error("Error removing alert:", err));
            }
        });

        function loadInspectionComplete() {
            fetch('/inspection-accept-alert')
                .then(response => {
                    if (!response.ok) throw new Error("Network error loading tasks");
                    return response.json();
                })
                .then(data => {
                    const { inceptionExist, inspectionCompleteList, count } = data;
                    const tasksList = document.getElementById('group1');
                    const notificationCount = document.getElementById('notificationCount');
                    const notificationText = document.getElementById('notificationText');

                    // Clear current list
                    tasksList.innerHTML = '';
                    // Update badge
                    if (count > 0) {
                        notificationCount.style.display = 'inline-block';
                        let currentCount = parseInt(notificationCount.textContent || "0", 10);
                        let newCount = currentCount + count;
                        notificationCount.textContent = newCount;
                        notificationText.textContent = `You have ${count} unread Notifications`;
                    } else {
                        notificationCount.style.display = 'none';
                        notificationText.textContent = `No new notifications`;
                    }

                    // Add work orders
                    if (inceptionExist && inspectionCompleteList.length > 0) {
                        inspectionCompleteList.forEach(task => {
                            tasksList.appendChild(createInsceptionElement(task));
                        });
                    }
                })
                .catch(error => console.error('❌ Failed to load tasks:', error));
        }

        function createInsceptionElement(task) {
            const li = document.createElement('li');
            li.className = 'dropdown-item px-3 mb-1 border-bottom';

            li.innerHTML = `
                <div class="d-flex">
                    <a href="/inspectionbooking/${
                        (() => {
                            try {
                                // Try to parse if it's a JSON string
                                const parsed = JSON.parse(task.booking);
                                // If it's an array, take the first value
                                if (Array.isArray(parsed)) return parsed[0];
                                // If it's an object, return its 'id' or fallback
                                if (typeof parsed === 'object' && parsed !== null) return parsed.id ?? '';
                                // Otherwise just return parsed value
                                return parsed;
                            } catch {
                                // If parsing fails, return the raw value
                                return task.booking;
                            }
                        })()
                    }">
                        <div class="ms-3">
                            <h5 class="notification-label text-dark mb-1">
                                Inspection Completed Workorder (ID: ${task.id})
                            </h5>
                        </div>
                    </a>
                </div>
            `;

            return li;
        }

        function loadWorkComplete() {
            fetch('/work-complete-alert')
                .then(response => {
                    if (!response.ok) throw new Error("Network error loading tasks");
                    return response.json();
                })
                .then(data => {
                    const { workExist, workCompleteList, count } = data;
                    const tasksList = document.getElementById('group2');
                    const notificationCount = document.getElementById('notificationCount');
                    const notificationText = document.getElementById('notificationText');

                    // Clear current list
                    tasksList.innerHTML = '';
                    // Update badge
                    if (count > 0) {
                        notificationCount.style.display = 'inline-block';
                        let currentCount = parseInt(notificationCount.textContent || "0", 10);
                        let newCount = currentCount + count;
                        notificationCount.textContent = newCount;
                        notificationText.textContent = `You have ${newCount} unread Notifications`;
                    } else {
                        notificationCount.style.display = 'none';
                        notificationText.textContent = `No new notifications`;
                    }

                    // Add work orders
                    if (workExist && workCompleteList.length > 0) {
                        workCompleteList.forEach(task => {
                            tasksList.appendChild(createWorkCompleteElement(task));
                        });
                    }
                })
                .catch(error => console.error('❌ Failed to load work complete:', error));
        }

        function createWorkCompleteElement(task) {
            const li = document.createElement('li');
            li.className = 'dropdown-item px-3 mb-1 border-bottom';

            li.innerHTML = `
                <div class="d-flex">
                    <a href="/workorder/${task.id}/edit">
                        <div class="ms-3">
                            <h5 class="notification-label text-dark mb-1">
                                Work Completed Workorder (ID: ${task.id})
                            </h5>
                        </div>
                    </a>
                </div>
            `;
            return li;
        }

    });
</script>
@elseif(auth::user()->type == 'technician')
<script type="module">
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
    import { getDatabase, ref, onChildAdded, remove } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js';

    document.addEventListener('DOMContentLoaded', () => {
        // Firebase configuration
        const firebaseConfig = {
            databaseURL: '{{ config("firebase.database_url") }}'
        };

        // Ensure Firebase URL exists
        if (!firebaseConfig.databaseURL) {
            console.error("❌ Firebase configuration missing or invalid.");
            return;
        }
        

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);

        // Reference to alerts
        const alertsRef = ref(database, 'alerts');
        
        newTask();
        loadQuotationComplete();
        // Listen for new alerts
        onChildAdded(alertsRef, (snapshot) => {
            const alert = snapshot.val();
            const alertKey = snapshot.key;
            
            if (alert.type === 'technican_alert' && alert.recipient_id == "{{ auth()->id() }}") {
                const audio = new Audio("{{ asset('assets/sounds/ding-alert.mp3') }}");
                audio.volume = 0.8;
                audio.play();   
                newTask();
                loadQuotationComplete();
                // Remove alert from Firebase once shown
                remove(ref(database, `alerts/${alertKey}`))
                    .catch((err) => console.error("Error removing alert:", err));
            }
        });

        function newTask() {
            fetch('/booking-alert')
                .then(response => {
                    if (!response.ok) throw new Error("Network error loading tasks");
                    return response.json();
                })
                .then(data => {
                    const { technicianExist, workOrders, count } = data;
                    const tasksList = document.getElementById('group1');
                    const notificationCount = document.getElementById('notificationCount');
                    const notificationText = document.getElementById('notificationText');

                    // Clear current list
                    tasksList.innerHTML = '';
                    // Update badge
                    if (count > 0) {
                        notificationCount.style.display = 'inline-block';
                        let currentCount = parseInt(notificationCount.textContent || "0", 10);
                        let newCount = currentCount + count;
                        notificationCount.textContent = newCount;
                        notificationText.textContent = `You have ${count} unread Notifications`;
                    } else {
                        notificationCount.style.display = 'none';
                        notificationText.textContent = `No new notifications`;
                    }

                    // Add work orders
                    if (technicianExist && workOrders.length > 0) {
                        workOrders.forEach(task => {
                            tasksList.appendChild(createNewTaskElement(task));
                        });
                    }
                })
                .catch(error => console.error('❌ Failed to load tasks:', error));
        }

        function createNewTaskElement(task) {
            const li = document.createElement('li');
            li.className = 'dropdown-item px-3 mb-1 border-bottom';

            li.innerHTML = `
                <div class="d-flex">
                    <a href="/workorder/details/${task.id}">
                        <div class="ms-3">
                            <h5 class="notification-label text-dark mb-1">
                                New Workorder (ID: ${task.id})
                            </h5>
                        </div>
                    </a>
                </div>
            `;

            return li;
        }

        function loadQuotationComplete() {
            fetch('/quatation-accept-alert')
                .then(response => {
                    if (!response.ok) throw new Error("Network error loading tasks");
                    return response.json();
                })
                .then(data => {
                    const { quotationExist, acceptedQuotations, count } = data;
                    const tasksList = document.getElementById('group2');
                    const notificationCount = document.getElementById('notificationCount');
                    const notificationText = document.getElementById('notificationText');

                    // Clear current list
                    tasksList.innerHTML = '';
                    // Update badge
                    if (count > 0) {
                        notificationCount.style.display = 'inline-block';
                        let currentCount = parseInt(notificationCount.textContent || "0", 10);
                        let newCount = currentCount + count;
                        notificationCount.textContent = newCount;
                        notificationText.textContent = `You have ${newCount} unread Notifications`;
                    } else {
                        notificationCount.style.display = 'none';
                        notificationText.textContent = `No new notifications`;
                    }

                    // Add work orders
                    if (quotationExist && acceptedQuotations.length > 0) {
                        acceptedQuotations.forEach(task => {
                            tasksList.appendChild(createQuotationCompleteElement(task));
                        });
                    }
                })
                .catch(error => console.error('❌ Failed to load approved quotation :', error));
        }

        function createQuotationCompleteElement(task) {
            const li = document.createElement('li');
            li.className = 'dropdown-item px-3 mb-1 border-bottom';

            li.innerHTML = `
                <div class="d-flex">
                    <a href="/workorder/details/${task.workorder_id}">
                        <div class="ms-3">
                            <h5 class="notification-label text-dark mb-1">
                                Quotaton Approved Workorder (ID: ${task.workorder_id})
                            </h5>
                        </div>
                    </a>
                </div>
            `;
            return li;
        }

    });
</script>
@endif
