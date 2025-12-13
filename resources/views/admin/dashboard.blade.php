@extends('layouts.backend.index')
@section('main_content')

    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="dashboard-heading">
                            <h4>Dashboard</h4>
                        </div>
                        <!-- [ Main Content ] start -->
                        <div class="row gx-4">
                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #E01D531A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Today's QR Scans</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.today_qr_icon')</span>
                                            <h3>{{isset($qrcode_details['today_used_qrcode']) ? $qrcode_details['today_used_qrcode'] : ''}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #0060AA1A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Today's Members</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.members_icon')</span>
                                            <h3>{{isset($qrcode_details['today_members']) ? $qrcode_details['today_members'] : ''}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #E01D531A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Today's QR Scan Price</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.today_price')</span>
                                            <h3>{{isset($qrcode_details['today_price']) ? $qrcode_details['today_price'] : '0'}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #0060AA1A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Overall QR Scans</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.qr_scan')</span>
                                            <h3>{{isset($qrcode_details['overall_qr_scans']) ? $qrcode_details['overall_qr_scans'] : ''}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4 -->
                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #0060AA1A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Overall Members</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.overall_icon')</span>
                                            <h3>{{isset($qrcode_details['overall_members']) ? $qrcode_details['overall_members'] : ''}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Card 5 -->
                            

                            <!-- Card 6 -->
                            <div class="col-xxl-3 col-xl-3 col-md-6 col-lg-6 col-sm-12 col-12 mb-4">
                                <div class="card border-0 h-100" style="background: #E01D531A;">
                                    <div class="card-body" style="padding:20px;">
                                        <h5 class="card-title">Overall QR Scan Price</h5>
                                        <div class="d-flex w-100 justify-content-between icon_padding">
                                            <span class="pcoded-micon">@include('icons.overall_today_price')</span>
                                            <h3>{{isset($qrcode_details['overall_price']) ? $qrcode_details['overall_price'] : ''}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div>
                                        <h5>Recent QR Scans</h5>
                                    </div>
                                    <div class="py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="recent_qr_scans" class="dataTable" style="width: 100%;"
                                                aria-describedby="recent_qr_scans_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th>No.</th>

                                                        <th>DATE</th>

                                                        <th>MEMBER&nbsp;CODE</th>

                                                        <th>MEMBER&nbsp;NAME</th>

                                                        <th>Price</th>

                                                        <th>QR&nbsp;Code</th>

                                                        <th>Scan&nbsp;By</th>

                                                        <th>Note</th>


                                                    </tr>
                                                </thead>
                                               <!--  <tbody>
                                                    <tr>
                                                        <td>29-03-2025</td>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>qr_01</td>
                                                        <td>warehouse&nbsp;zone</td>
                                                    </tr>
                                                    <tr>
                                                        <td>29-03-2025</td>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>qr_01</td>
                                                        <td>warehouse&nbsp;zone</td>
                                                    </tr>
                                                    <tr>
                                                        <td>29-03-2025</td>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>qr_01</td>
                                                        <td>warehouse&nbsp;zone</td>
                                                    </tr>
                                                    <tr>
                                                        <td>29-03-2025</td>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>qr_01</td>
                                                        <td>warehouse&nbsp;zone</td>
                                                    </tr>
                                                </tbody> -->
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
<script type="text/javascript">
    $(document).ready(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var member_table = $('#recent_qr_scans').DataTable({
                responsive: true,
                language: {
                        search: "",
                        "processing": '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>',
                        paginate: {
                        next: '&gt;', // or '→'
                        previous: '&lt;' // or '←' 
                    }
                },
                lengthChange: false,
                searching: false,
                processing: true,
                bAutoWidth: false,
                ajax: {
                    url: "recent-qr-scans-list",
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
                    data: 'date',
                    name: 'date',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'member_id',
                    name: 'member_id',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'member_name',
                    name: 'member_name',
                    // orderable: false,
                    // searchable: false
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
                    data: 'scan_by_name',
                    name: 'scan_by_name',
                    // orderable: false,
                    // searchable: false
                },
                {
                    data: 'message',
                    name: 'message',
                    // orderable: false,
                    // searchable: false
                }
                ],
                 

            });
    });
</script>
@endsection
