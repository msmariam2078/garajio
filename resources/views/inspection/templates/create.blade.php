@extends('layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> Create Inspection Template</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <div class="col-12 ">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('inspection.templatesstore') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="name">Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            placeholder="" required>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="groups">Group</label>
                                        <select class="selectize" id="groups" name="groups[]" multiple>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary col-6">Create template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.selectize').selectize({
                plugins: ['remove_button'], // enables remove button for multi-select
                maxItems: null, // allow multiple selections
            });
        });
    </script>
@endsection