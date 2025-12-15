@extends('layouts.backend.index')
<style type="text/css">
    .dataTables_wrapper .dataTables_filter{
        float: left !important;
    }
    #planslist_filter label{width: 100%;  margin-bottom: 0px !important}
    #planslist_filter {transform: translateY(-30px);}
    .table-responsive {overflow-x: unset !important;}
    #planslist_filter input{
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



</style>

@section('main_content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-wrapper-sub-wrapper">
                            <div class="dashboard-heading">
                                <h4>Plans</h4>
                                <a href="{{ route('plan.add') }}" class="add-article-btn">+ Add Plan</a>
                            </div>
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
                                            <table id="planslist" class="dataTable" style="width: 100%;"
                                                aria-describedby="member_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th>Sr&nbsp;No.</th>
                                                        <th>Plan Name</th>
                                                        <th>Plan Type</th>
                                                        <th>Message Type</th>
                                                        <th>Message Limit</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
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
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            var token = $("meta[name='csrf-token']").attr("content");
            var member_table = $('#planslist').DataTable({
                responsive: true,
                language: {
                        search: "",
                        // "searchPlaceholder": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i> Search here ...',
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
                ajax: {
                    url: "plans-list",
                    type: 'post',
                    data: {
                        _token: token,
                    },
                },

                columns: [{
                    data: 'ser_id',
                    name: 'id',
                },
                {
                    data: 'plan_name',
                    name: 'plan_name',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'plan_type',
                    name: 'plan_type',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'message_type',
                    name: 'message_type',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'message_count',
                    name: 'message_count',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'price',
                    name: 'price',
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
            $('.dataTables_filter input').attr('placeholder', 'Search here ...');
            $('#planslist_filter').addClass('search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12');
            $('#planslist_filter input').addClass('search-input');


            $(document).on('click', '.delete-plan-btn', function(e) {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This plan will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "plan/delete",
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
        });

        $(document).on('change', '.status-toggle', function () {

            let checkbox = $(this);
            let userId = checkbox.data('id');
            let status = checkbox.is(':checked') ? 1 : 0;

            let title = status === 1 ? 'Activate Plan?' : 'Deactivate Plan?';
            let text  = status === 1
                ? 'This plan will be displayed.'
                : 'This plan will not be displayed.';

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
                        url: "{{ route('plan.status') }}",
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
    </script>
@endsection
