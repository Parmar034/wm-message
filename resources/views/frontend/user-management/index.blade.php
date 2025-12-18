@extends('layouts.backend.index')
<style type="text/css">
    .dataTables_wrapper .dataTables_filter{
        float: left !important;
    }
    #memberlist_filter label{margin-bottom: 0px !important; font-size: 14px; float: left; padding-bottom: 5px;}
    #memberlist_filter {transform: translateY(-30px);}
    .table-responsive {overflow-x: unset !important;}
    #memberlist_filter .search-input{
        margin-left: 0px;
        border-radius: 0px;
        background-image: url(https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/svgs/solid/magnifying-glass.svg) !important;
        background-size: 18px !important;
        background-position: 10px center !important;
        background-repeat: no-repeat !important;
        padding: 8px 30px 8px 50px !important;
        background-color: #fff !important;
        border: 1px solid #ced4da;
    }
    .dataTables_filter #memberlist_filter .filter-group label{
        font-size: 14px !important;
    }
    #exportExcelBtn{
        margin-top: 25px;
    }
    .add-article-btn{
        margin-left: 5px;
        color: #fff !important;
        cursor: pointer;
    }




</style>

@section('main_content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-wrapper-sub-wrapper">
                            <div class="dashboard-heading">
                                <h4>User Management</h4>
                                <div><a href="{{ route('user-management.add') }}" class="add-article-btn">+ Add Member</a><a data-id='All'  class="add-article-btn send-message-btn">Send Message</a></div>
                                
                            </div>
       
                        </div>
                        <div class="row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div class="px-0 py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="memberlist" class="dataTable" style="width: 100%;"
                                                aria-describedby="member_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th style="padding-left: 24px;"><input type="checkbox" id="select_all"></th>
                                                        <th>Sr&nbsp;No.</th>
                                                        <th>Member</th>
                                                        <th>NAME</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>STATUS</th>
                                                        <th>ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   
                                                </tbody>
                                            </table>

                                                <select id="membersFilter" class="form-select search-dropdown d-none">
                                                    <option value="">All Members</option>
                                                    @foreach($members as $member)
                                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                    @endforeach
                                                </select>

                                                <a class="ml-3" id="exportExcelBtn" style="cursor: pointer;"><i class="fas fa-file-excel" style="font-size: 35px;"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="email_send_modal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="dateModalLabel">Send Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- <div class="error_top" style="display:none"></div> -->

                    <form id="dateForm" data-parsley-validate="">
                        <input type="hidden" name="memberId[]" id="memberId">

                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="message">Message</label>
                                <textarea class="form-control" id="message" name="message" required data-parsley-required-message="Message is required."></textarea>
                            </div>
                            <div class="error_top" style="display:none; color: darkred;">Message is required.</div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer justify-content-start">
                    <button type="button" class="btn btn-primary" id="send_message_customer">Send</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            var token = $("meta[name='csrf-token']").attr("content");
             var member_table;
             var isSelectAll;
        function loadDattable() {
               if ($.fn.DataTable.isDataTable('#memberlist')) {
                    $('#memberlist').DataTable().destroy();
                }
               member_table = $('#memberlist').DataTable({
                responsive: true,
                language: {
                        search: "",
                        // "searchPlaceholder": "Search",
                        "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>',
                        paginate: {
                        next: '&gt;', // or '→'
                        previous: '&lt;' // or '←' 
                    }
                },
                lengthChange: false,
                searching: true,
                processing: true,
                bAutoWidth: false,
                pageLength: 10,
                serverSide: true,
                ajax: {
                    url: "user-list",
                    type: 'post',
                    data: function (d) {
                        d._token = token;
                        d.members_filter = $('#membersFilter').val();
                    },
                    "dataSrc": function(response) {
                            isSelectAll = $('#select_all').is(':checked');
                            return response.data;
                        }
                },

                columns: [{
                    data: 'member_checkbox',
                    name: 'member_checkbox',
                    orderable: false,
                    searchable: false,
                    // render: function (data, type, row) {
                    //     return `<input type="checkbox" class="member-checkbox" value="${row.id}">`;
                    // }
                },
                {
                    data: 'ser_id',
                    name: 'id',
                },
                {
                    data: 'user_name',
                    name: 'user_name',
                    visible: {{ Auth::user()->role != 'Admin' ? 'true' : 'false' }},
                },
                {
                    data: 'member_name',
                    name: 'member_name',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'user_phone',
                    name: 'user_phone',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ],
                 

            });
        
            member_table.on('draw', function () {
                    $('[data-toggle="popover"]').popover();
                    if (isSelectAll) {
                        $('.member_checkbox').prop('checked', true);
                    }
                });


                $('.dataTables_filter input').attr('placeholder', 'Search here ...');
                $('.dataTables_filter input').addClass('search-input');

                $('#memberlist_filter')
                    .addClass('search-box col-xxl-12 col-xl-12 col-lg-7 col-md-6 col-sm-12 col-12 d-flex gap-3');

                const searchWrap = `
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                    </div>
                `;
                @if(Auth::user()->role != 'Admin')
                // Plan Filter Wrapper
                const membersFilterWrap = `
                    <div class="filter-group">
                        <label class="filter-label">Member</label>
                    </div>
                `;    
                @endif

                 $('#memberlist_filter').prepend(searchWrap);
                $('#memberlist_filter .filter-group:first')
                    .append($('.dataTables_filter input'));

                // Append wrappers
                @if(Auth::user()->role != 'Admin')
                $('#memberlist_filter').append(membersFilterWrap);
                $('#memberlist_filter .filter-group:last').append($('#membersFilter').removeClass('d-none'));

                @endif
                $('#exportExcelBtn').removeClass('d-none').appendTo('#memberlist_filter');

        }


            loadDattable();
            $('.dataTables_filter input').attr('placeholder', 'Search here ...');
            $('#memberlist_filter').addClass('search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12');
            $('#memberlist_filter input').addClass('search-input');


            $('#membersFilter').on('change', function () {
                member_table.ajax.reload();
            });


            $(document).on('click', '.delete-member-btn', function(e) {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This member will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "user-management/delete",
                            type: "post",
                            data: {
                                _token: token,
                                id: id,
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    toastr.success(data.message);
                                    member_table.ajax.reload(null, false);
                                } else {
                                    toastr.error(data.message);
                                }

                            }
                        });
                    }
                });
            });

            $(document).on('click', '.send-message-btn', function(e) {
                var id = $(this).attr("data-id");

                if(id == 'All'){
                    var selectedItems = $('#memberlist').DataTable().$('input[name="selected_items[]"]:checked').map(function(){
                       return $(this).val();
                    }).get();
                    var selectedItemsString = selectedItems.join(',');
                    if (selectedItemsString == '') {
                        return toastr.error('Please choose user.');
                    }
                
                }
                
                // $('#memberId').val(id);
                $('#email_send_modal').modal('show');
            });

    $(document).on('click', '#send_message_customer', function(e) {
        e.preventDefault();

        var message_text = $('#message').val().trim();
        var selectedItems = $('#memberlist').DataTable()
            .$('input[name="selected_items[]"]:checked')
            .map(function() {
                return $(this).val();
            }).get();

        if (message_text === '' || selectedItems.length === 0) {
            $('.error_top').text('Please enter a message and select members.').show();
            return;
        }

        $('.error_top').hide();

        $('#send_message_customer').prop('disabled', true).text('Sending...');

        $.ajax({
            url: "user-management/send-message",
            type: "POST",
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                selectedItemsString: selectedItems.join(','),
                message_text: message_text
            },
            success: function(data) {
                if (data.status == 1) {
                    toastr.success(data.message);
                    $('#email_send_modal').modal('hide');
                    $('#message').val('');
                    member_table.ajax.reload(null, false);
                } else {
                    toastr.error(data.message);
                }
            },
            error: function() {
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function() {
                $('#send_message_customer').prop('disabled', false).text('Send');
            }
        });
    });

        $(document).on('click', '#select_all', function(e) {
                var isChecked = $(this).prop('checked');
                $('.member_checkbox').prop('checked', isChecked);
            });
            $('#email_send_modal').on('hidden.bs.modal', function () {
                $('.error_top').hide();
                $(this).find('textarea').val('').trigger('change');
            });
        });

        $(document).on('change', '.status-toggle', function () {

            let checkbox = $(this);
            let userId = checkbox.data('id');
            let status = checkbox.is(':checked') ? 1 : 0;

            let title = status === 1 ? 'Activate User?' : 'Deactivate User?';
            let text  = status === 1
                ? 'This user can receive messages.'
                : 'This user cannot receive messages.';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('user-management.status') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: userId,
                            status: status
                        },
                        success: function (res) {

                            if (res.status) {
                                Swal.fire('Success!', res.message, 'success');
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                                checkbox.prop('checked', !status);
                            }

                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                            checkbox.prop('checked', !status);
                        }
                    });

                } else {
                    // Revert toggle if cancelled
                    checkbox.prop('checked', !status);
                }

            });

        });

        $(document).on('click', '#exportExcelBtn', function () {

            // Get filter values FIRST
            var membersFilter = $('#membersFilter').val() || '';


            // Check Select All checkbox
            var isSelectAll = $('#select_all').is(':checked');

            var selectedItemsString = '';

            if (isSelectAll) {
                // Special flag for backend
                selectedItemsString = 'all';
            } else {
                // Collect checked rows from DataTable (all pages)
                var selectedItems = $('#memberlist')
                    .DataTable()
                    .$('input[name="selected_items[]"]:checked')
                    .map(function () {
                        return $(this).val();
                    }).get();

                if (selectedItems.length === 0 && !membersFilter) {
                    alert('No user selected or filters applied.');
                    return;
                }

                selectedItemsString = selectedItems.join(',');
            }

            // Build export URL
            var exportUrl = "{{ route('user.export.excel') }}"
                + "?selectedItems=" + encodeURIComponent(selectedItemsString)
                + "&members_filter=" + encodeURIComponent(membersFilter);
            // Trigger Excel download
            window.location.href = exportUrl;
        });
    </script>
@endsection
