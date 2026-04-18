@extends('layouts.master')

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Inspection Group Points</h4>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')

    <form method="POST" action="{{ route('inspection.grouppointsstore', ['id' => $groupInspection->id]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <h3 class="text-muted" id="">ING-{{ $groupInspection->id }}</h3>

                <div class="h5 bold mb-4 pb-1" style="border-bottom: 1px solid">
                    General
                </div>

                <div class="row d-flex mb-2">
                    <div class="col-md-6 col-12">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <label for="customer" class="form-label fw-bold">Group ID</label>
                            <input type='text' class="form-control w-75" value="ING-{{ $groupInspection->id }}"
                                readonly />
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <label for="customer" class="form-label fw-bold">Group Code</label>
                            <input type='text' class="form-control w-75" value="{{ $groupInspection->code }}" readonly />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <table id="newrow" class="table table-bordered table-hover">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>Sl</th>
                                <th class="text-left">Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody" class="text-center">

                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-between">
                    <div class="col-4 ml-0 pl-0">
                        <button type="button" class="btn btn-secondary float-left" id="new">Create new</button>
                    </div>
                    <div class="col-4 pr-0">
                        <input class="btn btn-primary float-right" type="submit" id="save" value="Save" />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>
        let points = [];
        const groupInspection = @json($groupInspection->id);

        async function fetchPoints() {
            try {
                const response = await fetch(
                    `/grouppoint2/inspection/${groupInspection}`
                );
                const points = await response.json();
                console.log(points);
                return points.length > 0 ? points : null;
            } catch (error) {
                console.error('Error fetching the quote points:', error);
                return null;
            }
        }

        async function fetchAndShowPoints() {
            // if (items.length === 0) {
            const pointProducts = await fetchPoints();

            if (pointProducts.length > 0) {

                for (let i = 0; i < pointProducts.length; i++) {
                    addPointToTable(pointProducts[i], 1);
                }
            }

        }

        function addPointToTable(pointData) {
            points.push(pointData.point_des);
            renderTable();
        }

        function renderTable() {
            const tableBody = document.getElementById('productTableBody');
            tableBody.innerHTML = '';

            points.forEach((item, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${index + 1}</td>
                    <td class="name-cell text-left" data-index="${index}">${item ?? ''}</td>
                    
                    <td>
                        <button class="btn btn-danger delete-row" data-index="${index}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });

            document.querySelectorAll('.name-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    console.log(1);
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDescription = points[index] ?? '';

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentDescription;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDescription = this.value.trim();
                        points[index] = newDescription;
                        renderTable();
                    });
                });
            });

            //  console.log(document.querySelectorAll('.name-cell'));
            handleRowDelete();
            // handleDoubleClickEdit();
            // handleItemTypeChange();
        }

        function handleRowDelete() {
            document.querySelectorAll('.delete-row').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const index = parseInt(this.getAttribute('data-index'));
                    points.splice(index, 1);
                    renderTable();

                });
            });
        }

        fetchAndShowPoints();
        document.getElementById('new').addEventListener('click', function() {
            addPointToTable('');
        });

        function collectPointDetails() {
            let products = [];

            $('#productTableBody tr').each(function() {

                products.push($(this).find('.name-cell').text());
            });

            return products;
        }

        $('#save').click(function(e) {
            e.preventDefault();

            let formData = collectPointDetails();

            $.ajax({
                url: `/grouppoint/save/inspection/${groupInspection}`,
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Points updated successfully');

                },
                error: function(error) {
                    alert('Error');
                    console.error(error);
                }
            });
        });
    </script>

    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
