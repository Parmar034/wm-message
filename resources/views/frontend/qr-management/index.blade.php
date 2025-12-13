@extends('layouts.backend.index') @section('main_content')
<style type="text/css">
  /*  .dataTables_wrapper .dataTables_filter{
        float: left !important;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 1fr;
        grid-column-gap: 10px;
        grid-row-gap: 0px;
    }*/
    #qr_management_filter{
        display: flex;
    }
    #qr_management_filter label{width: 100%; margin-bottom: 0px !important}
    #qr_management_filter {transform: translateY(-30px);}
    .table-responsive {overflow-x: unset !important;}
    #qr_management_filter input{
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

/*  input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}*/



</style>
<div class="pcoded-wrapper">
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-wrapper-sub-wrapper">
                        <div class="dashboard-heading qr-dashboard-heading">
                            <h4>QR Management</h4>
                            <div class="button-group">
                                <a href="{{ route('qr-management.bulk-qr-entry-report') }}" class="Bulk-article-btn">+ Bulk QR Entry Import</a>
                                <a href="{{ route('qr-management.add') }}" class="add-article-btn">+ Add QR Entry</a>
                            </div>
                        </div>

                        <!-- <div class="search-wrapper px-3">
                            <div class="row align-items-center box-padding">
                                <div class="search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12">
                                    <img src="{{ asset('assets/icons/search_icon.svg') }}" alt="Search" class="search-icon" />
                                    <input type="text" class="search-input" placeholder="Search here..." />
                                </div>

                                <div class="search-box col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <select class="form-select status-col" id="qr_code" name="qr_code">
                                        <option value="disabled">Select Status</option>
                                        <option value="john_doe">All</option>
                                        <option value="jane_smith">Used</option>
                                        <option value="mark_jones">Unused</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div> -->
                    </div>

                        <div class="row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div class="px-0 py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="qr_management" class="dataTable no-footer" style="width: 100%;" aria-describedby="articlelist_info">
                                                <thead>
                                                    <tr style="background-color: #e1ebf4 !important;">
                                                        <th>No.</th>

                                                        <th>Price</th>

                                                        <th>Qr code</th>

                                                        <th>DATE</th>
                                                        <th>Status</th>

                                                        <th>ACTIONS</th>
                                                    </tr>
                                                </thead>

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

        <div class="modal fade" id="usedQRDeleteModal" tabindex="-1" aria-labelledby="usedQRDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="usedQRDeleteModalLabel">Delete Used QR Codes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="usedQRDeleteForm" method="POST" data-parsley-validate="">
                  <div class="mb-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input class="form-control date" id="start_date" name="start_date" type="text" required data-parsley-required-message="Please Enter From Date"/>
                  </div>
                  <div class="mb-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input class="form-control date" id="end_date" name="end_date" type="text" required data-parsley-required-message="Please Enter To Date"/>
                  </div>
                  <button type="submit" class="btn btn-danger w-100">Delete QR Codes</button>
                </form>
              </div>
            </div>
          </div>
        </div>
   
    @endsection 
    @section('script')
   
    <script>
        $(document).ready(function() {

                let today = new Date();

                // Initialize Start Datepicker
                $('#start_date').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                }).on('changeDate', function (selected) {
                    let minDate = new Date(selected.date.valueOf());
                    $('#end_date').datepicker('setStartDate', minDate);
                    
                    // Optional: only reset end date if it’s before selected start
                    const endDateVal = $('#end_date').datepicker('getDate');
                    if (!endDateVal || endDateVal < minDate) {
                        $('#end_date').datepicker('setDate', minDate);
                    }
                });

                // Initialize End Datepicker
                $('#end_date').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    startDate: today // prevent past dates
                }); // Set default to today
            var token = $("meta[name='csrf-token']").attr("content");
            var member_table = $('#qr_management').DataTable({
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
                    url: "qr-management-list",
                    type: 'post',
                    data: function (d) {
                        d.status = $('#qr_code').val(); // get dropdown value
                    }
                },

                columns: [{
                    data: 'ser_id',
                    name: 'id',
                },
                {
                    data: 'qr_serial_no',
                    name: 'qr_serial_no',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'qr_code',
                    name: 'qr_code',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        if (!data) return '';
                        
                        let date = new Date(data);
                        let day = String(date.getDate()).padStart(2, '0');
                        let month = String(date.getMonth() + 1).padStart(2, '0');
                        let year = date.getFullYear();
                        
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
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

            $(document).on('keyup', '.search-input', function(e) {
                member_table.search(this.value).draw();
            });


           
            $('#qr_management_filter').append('<select class="form-select status-col" id="qr_code" name="qr_code"><option value="disabled">Select Status</option><option value="All">All</option><option value="Used">Used</option><option value="Unused">Unused</option></select>');
            $('.dataTables_filter input').attr('placeholder', 'Search here ...');
            $('#qr_management_filter').addClass('search-box col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 justify-content-space-between');
            $('#qr_management_filter input').addClass('search-input');
            $('#qr_management_filter').append('<div class=""><button type="button" class="add-article-btn used_QR_delete" id="used_QR_delete">Used QR Delete</button></div>');

              let $label = $('#qr_management_filter label');
            let $select = $('#qr_management_filter select#qr_code');
             let $wrapper = $('<div class="d-flex "></div>');
             $wrapper.append($label.clone()).append($select.detach());
              $label.replaceWith($wrapper);


            $(document).on('click', '.delete-qrcode-btn', function(e) {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This QR code will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/qr-management/delete",
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

            $(document).on('change', '#qr_code', function(e) {
                // let value = $(this).val();
                // member_table.column(4).search(value).draw();
                member_table.ajax.reload(null, false);
            });

            $(document).on('click', '#used_QR_delete', function () {
                $('#usedQRDeleteModal').modal('show');
            });

            $(document).on('submit', '#usedQRDeleteForm', function (e) {
                e.preventDefault();
                $('#usedQRDeleteModal').modal('hide');

                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                 Swal.fire({
                    title: "Are you sure?",
                    text: "Used QR code will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/qr-management/used-delete",
                            type: "post",
                            data: {
                                _token: token,
                                start_date: start_date,
                                end_date: end_date,

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

            $(document).on('hidden.bs.modal', '#usedQRDeleteModal', function (e) {
                $('#usedQRDeleteForm').parsley().reset();
                $('#usedQRDeleteForm')[0].reset();
            });
        });
    </script>
    @endsection

