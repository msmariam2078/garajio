@extends('layouts.master')
@section('title','Scrap Module')
@section('css')
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Module Setup') }}</h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/Scrap Module</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header flex items-center justify-between d-none">
                    <div class="card-title">Product Details</div>
                    <label class="custom-switch">
                        <input type="hidden" name="allproducts" id="allproducts" value="0">
                        <input type="checkbox" name="is_active" class="toggle-status" onchange="updateStatus(this)">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="card-body">
					@php
						$scrapEnabled = $scrapModule?->scrap_module == 1;
					@endphp
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="scrap_module" {{ $scrapEnabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="scrap_module">Scrap Module</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="scrap_stock"
							{{ $scrapModule?->scrap_stock == 1 ? 'checked' : '' }}
        					{{ !$scrapEnabled ? 'disabled' : '' }}>
                        <label class="form-check-label" for="scrap_stock">Scrap part of stock</label>
                    </div>
					<div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="scrap_default" 
							{{ $scrapModule?->scrap_default == 1 ? 'checked' : '' }}
        					{{ !$scrapEnabled ? 'disabled' : '' }}>
                        <label class="form-check-label" for="scrap_default">Default item type</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#scrap_module').on('change', function() {
            $.ajax({
                url: "{{ route('check.scrap') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    checked: $(this).is(':checked') ? 1 : 0 
                },
                success: function(response) {
                    if (response.status === true) {
						$('#scrap').prop('checked', true);
						$('#scrap_stock').prop('disabled', false);
						$('#scrap_default').prop('disabled', false);						
                    } else {
                        $('#scrap').prop('checked', false);
						$('#scrap_stock').prop('disabled', true);
						$('#scrap_default').prop('disabled', true);	
                    }
                }
            });
        });

		$('#scrap_stock').on('change', function() {
            $.ajax({
                url: "{{ route('check.scrap.stock') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    checked: $(this).is(':checked') ? 1 : 0 
                },
                success: function(response) {

                    if (response.status === true) {
						$('#scrap_stock').prop('checked', true);
                    } else {
                        $('#scrap_stock').prop('checked', false);
                    }
                }
            });
        });

		$('#scrap_default').on('change', function() {
            $.ajax({
                url: "{{ route('check.scrap.default') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    checked: $(this).is(':checked') ? 1 : 0 
                },
                success: function(response) {

                    if (response.status === true) {
						$('#scrap_default').prop('checked', true);
                    } else {
                        $('#scrap_default').prop('checked', false);
                    }
                }
            });
        });
    </script>   
    @push('script-page')
    @endpush
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@endsection
