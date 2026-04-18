@extends('layouts.master')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> Edit Inspection Template</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('inspection.templatesupdate', $template->id) }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="code">Code</label>
                                        <input type="text" class="form-control" value="{{ $template->code }}"
                                            id="code" name="code" required>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                            value="{{ $template->des }}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="groups">Group</label>
                                        <select class="selectize" id="groups" name="groups[]" multiple>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}"
                                                    {{ isset($template_groups) && in_array($group->id, $template_groups) ? 'selected' : '' }}>
                                                    {{ $group->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary col-6">Edit template</button>
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
            // Destroy existing Selectize instance (important for edit mode)
            if ($('#groups')[0].selectize) {
                $('#groups')[0].selectize.destroy();
            }

            // Initialize Selectize
            $('#groups').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: function(input) {
                    return {
                        value: input,
                        text: input
                    };
                }
            });

            // Optional: refresh preselected values after initialization
            let selectize = $('#groups')[0].selectize;
            selectize.refreshOptions(false);
        });
    </script>
@endsection