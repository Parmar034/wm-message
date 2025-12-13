@extends('layouts.backend.index')
<style type="text/css">
    .dataTables_wrapper .dataTables_filter{
        float: left !important;
    }
    #memberlist_filter label{width: 100%;  margin-bottom: 0px !important}
    #memberlist_filter {transform: translateY(-30px);}
    .table-responsive {overflow-x: unset !important;}
    #memberlist_filter input{
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
    .send-message-btn{
        color: #fff !important;
        margin-left: 15px;
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
                        <!--     <div class="dashboard-heading">
                                <h4></h4>
                                <a data-id='All'  class="add-article-btn send-message-btn">Send Message</a>
                            </div> -->
                            <!-- <div class="search-wrapper">
                                <div class="search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12">
                                    <img src="{{ asset('assets/icons/search_icon.svg') }}" alt="Search"
                                        class="search-icon">
                                    <input type="text" class="search-input" placeholder="Search here...">
                                </div>
                            </div> -->
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
                                                        <th>NAME</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>ACTIONS</th>
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
                    data: {
                        _token: token,
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
                    data: 'member_name',
                    name: 'member_name',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'phone',
                    name: 'phone',
                    // orderable: false,
                    // searchable: false
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
        }


            loadDattable();
            $('.dataTables_filter input').attr('placeholder', 'Search here ...');
            $('#memberlist_filter').addClass('search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12');
            $('#memberlist_filter input').addClass('search-input');


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
                        return toastr.error('Data not selected');
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
    </script>
@endsection
