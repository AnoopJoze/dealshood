@extends('layouts.user_type.auth')

@section('content')

    @push('css')
        <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
        <style type="text/css">
        </style>
    @endpush
<div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

                    <div>
                        <h4 class="mb-1 fw-bold text-dark">Users Management</h4>
                        <p class="text-sm text-muted mb-0">
                            Manage all registered users from here
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-light border rounded-3 px-3">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>


            <button class="btn bg-gradient-primary"
            data-bs-toggle="modal"
            data-bs-target="#localityModal">

        <i class="fas fa-plus"></i>
        Add Locality
    </button>
                    </div>

                </div>
            </div>

            <!-- Body -->
            <div class="card-body pt-0 px-4 pb-4">

                <!-- Search & Stats -->
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="input-group input-group-outline">
                            <span class="input-group-text bg-gray-100 border-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-0 bg-gray-100"
                                   placeholder="Search users...">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="d-flex flex-wrap justify-content-md-end gap-2">

                            <div class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                Total Users : 245
                            </div>

                            <div class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                Active : 210
                            </div>

                            <div class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                Inactive : 35
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Table -->
                <div class="table-responsive">

                    <table id="datatable"
                           class="table align-middle table-hover mb-0">

                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3">
                                    Action
                                </th>

                                <th class="text-uppercase text-secondary text-xxs fw-bolder">
                                    Parent
                                </th>

                                <th class="text-uppercase text-secondary text-xxs fw-bolder">
                                    Name
                                </th>

                                <th class="text-uppercase text-secondary text-xxs fw-bolder">
                                    Status
                                </th>

                                <th class="text-uppercase text-secondary text-xxs fw-bolder">
                                    Created Date
                                </th>
                            </tr>

                            <!-- Filters -->
                            <tr>
                                <th></th>

                                <th>
                                    <input type="text"
                                           class="form-control form-control-sm border"
                                           placeholder="Search Parent">
                                </th>

                                <th>
                                    <input type="text"
                                           class="form-control form-control-sm border"
                                           placeholder="Search Locality">
                                </th>

                                <th>
                                    <select class="form-select form-select-sm border">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </th>

                                <th>
                                    <div class="d-flex gap-2">
                                        <input type="date"
                                               class="form-control form-control-sm border">

                                        <input type="date"
                                               class="form-control form-control-sm border">
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
</div>
<!-- MODAL -->
<div class="modal fade" id="localityModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Add Locality
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- NAME -->
                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input type="text"
                           class="form-control"
                           id="locality_name"
                           placeholder="Enter locality name">

                    <small class="text-danger d-none"
                           id="name_error"></small>
                </div>

                <!-- TYPE -->
                <div class="mb-3">

                    <label class="form-label">
                        Type
                    </label>

                    <select class="form-select"
                            id="locality_type">

                        <option value="">
                            Select Type
                        </option>

                        <option value="country">
                            Country
                        </option>

                        <option value="state">
                            State
                        </option>

                        <option value="city">
                            City
                        </option>

                        <option value="area">
                            Area
                        </option>

                    </select>

                    <small class="text-danger d-none"
                           id="type_error"></small>
                </div>

                <!-- PARENT -->
                <div class="mb-3">

                    <label class="form-label">
                        Parent Locality
                    </label>

                    <select class="form-select"
                            id="parent_id">

                        <option value="">
                            None
                        </option>

                        @foreach($localities as $locality)

                            <option value="{{ $locality->id }}">

                                {{ ucfirst($locality->type) }}
                                →
                                {{ $locality->name }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-danger d-none"
                           id="parent_error"></small>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">

                <button class="btn bg-gradient-secondary"
                        data-bs-dismiss="modal">

                    Cancel
                </button>

                <button class="btn bg-gradient-primary"
                        id="saveLocality">

                    Save Locality
                </button>

            </div>

        </div>

    </div>

</div>
@endsection

        @push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.blockUI.js"></script>
<script src="{{ asset('assets') }}/vendor/moment.min.js"></script>
<script src="{{ asset('assets') }}/jquery-validate/jquery.validate.js"></script>
<script src="{{ asset('assets') }}/jquery-validate/additional-methods.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.mousewheel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<script type="text/javascript">
$('#saveLocality').click(function () {

    $('.text-danger').addClass('d-none').text('');

    $.ajax({

        url: "{{ route('localities.ajaxStore') }}",

        type: "POST",

        data: {
            _token: "{{ csrf_token() }}",

            name: $('#locality_name').val(),

            type: $('#locality_type').val(),

            parent_id: $('#parent_id').val()
        },

        success: function (res) {

            if (res.success) {

                // close modal
                $('#localityModal').modal('hide');

                // reset form
                $('#locality_name').val('');
                $('#locality_type').val('');
                $('#parent_id').val('');

                // refresh datatable
                $('#localities-table')
                    .DataTable()
                    .ajax.reload(null, false);
            }
        },

        error: function (xhr) {

            let errors = xhr.responseJSON.errors;

            if (errors.name) {
                $('#name_error')
                    .removeClass('d-none')
                    .text(errors.name[0]);
            }

            if (errors.type) {
                $('#type_error')
                    .removeClass('d-none')
                    .text(errors.type[0]);
            }

            if (errors.parent_id) {
                $('#parent_error')
                    .removeClass('d-none')
                    .text(errors.parent_id[0]);
            }
        }
    });

});
$(document).on('change', '.inline-edit', function () {

    let id = $(this).data('id');
    let field = $(this).data('field');
    let value = $(this).val();

    let url = '';

    // detect module based on table/page
    url = "{{ route('localities.inlineUpdate') }}";

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            field: field,
            value: value
        },
        success: function (res) {
            if (res.success) {
                console.log('Updated');
            }
        },
        error: function () {
            alert('Update failed');
        }
    });

});
                scrollDatatable();

                function scrollDatatable() {
                    setTimeout(function() {
                        $(".dataTables_scrollBody").mousewheel(function(event, delta) {
                            this.scrollLeft -= (delta * 100);
                            event.preventDefault();
                        });
                        $.unblockUI();
                    }, 600);
                }

                $(document).on('click', '.exporttoexcel', function(e) {
                    $.blockUI({
                        css: {
                            backgroundColor: 'transparent',
                            border: 'none'
                        },
                        message: '',
                        baseZ: 1500,
                        overlayCSS: {
                            backgroundColor: '#000',
                            opacity: 0.7,
                            cursor: 'wait'
                        }
                    });
                    setTimeout(function() {
                        $.unblockUI();
                    }, 800);

                });
                $(document).on('click', '.refresh', function(e) {
                    $.blockUI({
                        css: {
                            backgroundColor: 'transparent',
                            border: 'none'
                        },
                        message: '',
                        baseZ: 1500,
                        overlayCSS: {
                            backgroundColor: '#000',
                            opacity: 0.7,
                            cursor: 'wait'
                        }
                    });
                    document.getElementById("search_tech").reset();
                    $('#datatable').DataTable().state.clear();
                    fetch_datatable_data();
                });

                getDatatable();

                function getDatatable() {

                    var table = $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('localities.data') }}',
                            type: 'POST',
                            data: function (d) {
                                d._token = '{{ csrf_token() }}'; // 🔒 CSRF token

                                $('#datatable thead tr:eq(1) th').each(function(index) {
                                    const input = $(this).find('input.column_filter');
                                    if (input.length > 0) {
                                        d.columns[index] = d.columns[index] || {};
                                        d.columns[index].data = input.data('column');
                                        d.columns[index].search = { value: input.val() };
                                    }
                                });

                                // send date range
                                d.start_date = $('#start_date').val();
                                d.end_date = $('#end_date').val();
                                d.action_start_date = $('#action_start_date').val();
                                d.action_end_date = $('#action_end_date').val();
                                d.status = $('#status').val();
                            }
                        },
                        columns: [
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                            { data: 'parent', name: 'parent' },
                            { data: 'name', name: 'name' },
                            { data: 'status', name: 'status', searchable: false },
                            { data: 'created_at', name: 'created_at', searchable: false },
                        ],
                        order: [[1, 'desc']],
                        pageLength: 25,
                        lengthMenu: [10, 25, 50, 100],
                        orderCellsTop: true,
                        fixedHeader: true,
                    });

                    // Apply column filters
                    $('#datatable thead tr:eq(1) th').each(function (i) {
                        $('input', this).on('keyup change', function () {
                            table.column(i).search(this.value).draw();
                        });
                    });

                   $('#exportForm').on('submit', function () {

                        $.blockUI({
                            css: {
                                backgroundColor: 'transparent',
                                border: 'none'
                            },
                            message: '',
                            baseZ: 1500,
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.7,
                                cursor: 'wait'
                            }
                        });
                        var filters = {};

                        // Loop through each filter input in the header
                        $('#datatable thead tr:eq(1) th').each(function (i) {
                            var input = $(this).find('input');
                            if (input.length > 0) {
                                var columnName = table.column(i).dataSrc(); // get the real data name
                                filters[columnName] = input.val();
                            }
                        });

                        // Also include date filters
                        filters['start_date'] = $('#start_date').val();
                        filters['end_date'] = $('#end_date').val();
                        filters['status'] = $('#status').val();

                        // Store JSON in hidden input
                        $('#filterInput').val(JSON.stringify(filters));
                    });
                }

                $(document).ready(function() {

                    $(document).on('click', '#clear_filter', function(e) {
                        e.preventDefault();
                        dropdownActive = true;

                        $.blockUI({
                            css: {
                                backgroundColor: 'transparent',
                                border: 'none'
                            },
                            message: '',
                            baseZ: 1500,
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.7,
                                cursor: 'wait'
                            }
                        });

                        $("#exportForm")[0].reset();
                        $('#datatable thead tr:eq(1) th input').val('');
                        $('.column-search').val('');
                        let table = $('#datatable').DataTable();
                        table.search('').columns().search('').draw();

                        setTimeout($.unblockUI, 500);

                        fetch_datatable_data();
                    });
                });

                function newexportaction(e, dt, button, config) {
                    var self = this;
                    var oldStart = dt.settings()[0]._iDisplayStart;
                    dt.one('preXhr', function(e, s, data) {
                        // Just this once, load all data from the server...
                        data.start = 0;
                        data.length = 2147483647;
                        dt.one('preDraw', function(e, settings) {
                            // Call the original action function
                            if (button[0].className.indexOf('buttons-copy') >= 0) {
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                            }
                            dt.one('preXhr', function(e, s, data) {
                                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                                // Set the property to what it was before exporting.
                                settings._iDisplayStart = oldStart;
                                data.start = oldStart;
                            });
                            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                            setTimeout(dt.ajax.reload, 0);
                            // Prevent rendering of the full data to the DOM
                            return false;
                        });
                    });
                    // Requery the server with the new one-time export settings
                    dt.ajax.reload();
                }
</script>
                @endpush
