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



</style>

@section('main_content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-wrapper-sub-wrapper">
                            <div class="dashboard-heading">
                                <h4>Member Management</h4>
                                <a href="{{ route('member-management.add') }}" class="add-article-btn">+ Add Member</a>
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
                                            <table id="memberlist" class="dataTable" style="width: 100%;"
                                                aria-describedby="member_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th>Sr&nbsp;No.</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>Assign Plan</th>
                                                        <th>Start Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Status</th>
                                                        <th>Plan Assign</th>
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

    @include('frontend.member-management.plan-assign-modal')
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            var token = $("meta[name='csrf-token']").attr("content");
            var member_table = $('#memberlist').DataTable({
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
                    url: "member-list",
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
                    data: 'name',
                    name: 'name',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'email',
                    name: 'email',
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
                    data: 'assign_plan',
                    name: 'assign_plan',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'end_date',
                    name: 'end_date',
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
                    data: 'plan_assign',
                    name: 'plan_assign',
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
                rowCallback: function (row, data) {
                    if (data.end_date !== '-') {
                        let parts = data.end_date.split('-');
                        let endDate = new Date(parts[2], parts[1] - 1, parts[0]);
                        let today = new Date();
                        today.setHours(0,0,0,0);
                        if (endDate <= today) {
                             $(row).css('cssText', 'background-color: #ffe5e5 !important;');
                        } else {
                            $(row).css('cssText', 'background-color: #e6ffed !important;');
                        }
                    }
                }
                 

            });
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
                            url: "member-management/delete",
                            type: "post",
                            data: {
                                _token: token,
                                id: id,
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    toastr.success(data.message);
                                    member_table.ajax.reload();
                                } else {
                                    toastr.error(data.message);
                                }

                            }
                        });
                    }
                });
            });

            $(document).on('submit', '#assignPlanModal form', function (e) {
                e.preventDefault();

                let form = $(this);
                let formData = form.serialize();

                // Get modal instance
                let modalEl = document.getElementById('assignPlanModal');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);

                // HIDE modal first (this removes backdrop issue)
                modalInstance.hide();

                Swal.fire({
                    title: 'Assign Plan?',
                    text: 'Are you sure you want to assign this plan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Assign',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Reset form
                                form[0].reset();
                                $('#modal-plan-details').hide();

                                // OPTIONAL: Reload DataTable only
                                member_table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error',
                                    xhr.responseJSON?.message || 'Something went wrong',
                                    'error'
                                );
                            }
                        });

                    } else {
                        // If cancelled → reopen modal
                        modalInstance.show();
                    }
                });
            });



        });

        $(document).on('change', '.status-toggle', function () {

            let checkbox = $(this);
            let userId = checkbox.data('id');
            let status = checkbox.is(':checked') ? 1 : 0;

            let title = status === 1 ? 'Activate User?' : 'Deactivate User?';
            let text  = status === 1
                ? 'This user will be able to login.'
                : 'This user will not be able to login.';

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
                        url: "{{ route('member-management.status') }}",
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

      
        $(document).on('click', '.openPlanModal', function () {

            let userId = $(this).data('user-id');
            let userName = $(this).data('user-name');


            // Set user id in hidden field
            $('#modal_user_id').val(userId);
            $('#modal_user_name').val(userName);

            // Reset modal data
            $('#modal_plan_name').val('');
            $('#modal-plan-details').hide();

            // Open modal (Bootstrap 5)
            let modal = new bootstrap.Modal(document.getElementById('assignPlanModal'));
            modal.show();
        });


        // Show plan details inside modal
        $(document).on('change', '#modal_plan_name', function () {

            let selected = $(this).find(':selected');

            if (!$(this).val()) return;

            $('#modal-plan-type').text(selected.data('type'));
            $('#modal-plan-price').text(selected.data('price'));

            $('#modal-plan-limit').text(
                selected.data('limit') ? selected.data('limit') : 'Unlimited'
            );

            $('#modal-plan-details').slideDown();
        });




    </script>
@endsection
