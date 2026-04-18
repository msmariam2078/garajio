<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
    @include('layouts.head')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">

    <style>
        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }

        .modal {
            overflow-y: auto;
        }

        #mapmodal {
            z-index: 1050;
        }

        #customModal {
            z-index: 1060;
            /* Ensure it's higher than the first modal */
        }
    </style>

</head>

<body class="main-body app sidebar-mini">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    @include('layouts.main-sidebar')
    <!-- main-content -->
    <div class="main-content app-content">
        @include('layouts.main-header')
        <!-- container -->
        <div class="container-fluid">
            <div class="modal fade" id="customModal">
                <div class="modal-dialog ">
                    <div class="modal-content custom">
                        <div class="modal-header pt-0 px-0">
                            <h5 class="modal-title"></h5>
                            <a href="javascript:void(0);" class="customModal_close" data-dismiss="modal"
                                data-bs-dismiss="modal">
                                <i class="ti-close"></i>
                            </a>
                        </div>
                        <div class="body">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="customModal2">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <a href="javascript:void(0);" class="customModal_close" data-dismiss="modal" data-bs-dismiss="modal">
								 <i class="ti-close"></i>
                            </a>
                        </div>
                        <div class="modal-body body">
                        </div>                       
                    </div>
                </div>
            </div>


            @yield('page-header')
            @yield('content')
            @include('layouts.sidebar-right')
            @include('layouts.models')
            @include('layouts.footer')
            @include('layouts.footer-scripts')
            <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

            <script>
                document.querySelector('[data-dismiss="modal"]').addEventListener('click', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('customModal'));
                    myModal.hide();
                });

                $(document).on('click', '.customModal_close', function() {
                    $('#customModal').modal('hide');
                    $('.modal-backdrop').remove();
                });

                // Stop scrolling on the right side when hovering over the sidebar
                document.querySelector('.app-sidebar').addEventListener('mouseenter', function() {
                    document.body.classList.add('stop-scroll'); // Disable right-side scroll
                });

                // Re-enable scrolling when mouse leaves the sidebar
                document.querySelector('.app-sidebar').addEventListener('mouseleave', function() {
                    document.body.classList.remove('stop-scroll'); // Re-enable right-side scroll
                });
            </script>
</body>

</html>
