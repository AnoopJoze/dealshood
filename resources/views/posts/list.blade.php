@extends('layouts.user_type.auth')

@section('content')

    @push('css')
        <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
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
            data-bs-target="#postModal">

        <i class="fas fa-plus"></i>
        Add Post
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
                                    Category
                                </th>

                                <th class="text-uppercase text-secondary text-xxs fw-bolder">
                                    Sub Category
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
                                           placeholder="Search Category">
                                </th>

                                <th>
                                    <input type="text"
                                           class="form-control form-control-sm border"
                                           placeholder="Search Subcategory">
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
<div class="modal fade" id="postModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <!-- TITLE -->
                    <div class="col-md-6 mb-3">
                        <label>Title</label>
                        <input type="text" id="title" class="form-control">
                        <small class="text-danger d-none" id="title_error"></small>
                    </div>

                    <!-- CATEGORY -->
                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <select id="category_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SUBCATEGORY -->
                    <div class="col-md-6 mb-3">
                        <label>Subcategory</label>
                        <select id="subcategory_id" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>

                    <!-- LOCALITY -->
                    <div class="col-md-6 mb-3">
                        <label>Locality</label>
                        <select id="locality_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($localities as $loc)
                                <option value="{{ $loc->id }}">
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- EXPIRY DATE -->
                    <div class="col-md-6 mb-3">
                        <label>Expiry Date</label>
                        <input type="date" id="expiry_date" class="form-control">
                    </div>

                    <!-- MAP LOCATION -->
                    <div class="col-md-6 mb-3">
                        <label>Map Location</label>
                        <input type="text" id="map_location" class="form-control"
                               placeholder="Google Maps link or lat,lng">
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea id="description" class="form-control" rows="4"></textarea>
                    </div>
                    <!-- DESCRIPTION (RICH TEXT) -->

                    <!-- DROPZONE IMAGE UPLOAD -->
                    <div class="col-md-12 mt-3">

                        <label>Images</label>

                        <form action="{{ route('posts.uploadImage') }}"
                            class="dropzone"
                            id="postDropzone">

                            @csrf

                        </form>

                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn bg-gradient-primary" id="savePost">Save Post</button>
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
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script type="text/javascript">
CKEDITOR.replace('description', {
    height: 200
});
Dropzone.autoDiscover = false;

let postId = null;

let myDropzone = new Dropzone("#postDropzone", {

    url: "{{ route('posts.mediaUpload') }}",
    autoProcessQueue: false,   // 🔥 IMPORTANT FIX
    uploadMultiple: false,
    parallelUploads: 5,
    maxFilesize: 5,
    acceptedFiles: "image/*",

    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },

    sending: function (file, xhr, formData) {
        formData.append("post_id", postId);
    }
});

$('#savePost').click(function () {

    $.ajax({
        url: "{{ route('posts.ajaxStore') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            title: $('#title').val(),
            category_id: $('#category_id').val(),
            subcategory_id: $('#subcategory_id').val(),
            locality_id: $('#locality_id').val(),
            expires_at: $('#expires_at').val(),
            description: CKEDITOR.instances.description.getData()
        },

        success: function (res) {

            postId = res.data.id; // ✅ now we have post ID

            // 🔥 NOW START IMAGE UPLOAD
            myDropzone.processQueue();

            $('#postModal').modal('hide');

            $('#posts-table').DataTable().ajax.reload(null, false);
        }
    });

});

myDropzone.on("processing", function(file) {

    if (!postId) {
        this.removeFile(file);
        alert("Please save post first");
    }
});

$('#category_id').on('change', function () {

    let id = $('#category_id').val();

$.ajax({
    url: "{{ url('admin/get-subcategories') }}/" + id,
    type: "GET",

        success: function (res) {

            $('#subcategory_id').html('<option value="">Select</option>');

            res.forEach(function (item) {
                $('#subcategory_id').append(
                    `<option value="${item.id}">${item.name}</option>`
                );
            });
        }
    });

});
$(document).on('change', '.inline-edit', function () {

    let id = $(this).data('id');
    let field = $(this).data('field');
    let value = $(this).val();

    $.ajax({
        url: "{{ route('posts.inlineUpdate') }}",

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
                            url: '{{ route('posts.data') }}',
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
                            { data: 'category', name: 'category' },
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
