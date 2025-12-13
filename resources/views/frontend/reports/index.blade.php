@extends('layouts.backend.index') @section('main_content')
<style type="text/css">
  /*  .form-control input {
        border: none;
        box-sizing: border-box;
        outline: 0;
        padding: .75rem;
        position: relative;
        width: 100%;
    }*/
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
</style>
<div class="pcoded-wrapper">
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-wrapper-sub-wrapper">
                        <div class="dashboard-heading">
                            <h4>Reports</h4>
                            <div class="button-group">
                                <a href="javascript:;" class="add-article-btn" id="export_to_excel">@include('icons.download_icon') Export to Excel</a>
                            </div>
                        </div>

                        <!-- <div class="card-body"> -->
                            <form id="adduser" action="#" method="POST" data-parsley-validate="">
                                @csrf
                                <input type="hidden" id="user_id" name="user_id" />

                                <div class="row">
                                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
                                        <label class="form-label" for="exampleFormControlInput1">Member Code </label>
                                        <!-- <input class="form-control" id="member_code" name="member_code" type="text" placeholder="001"/> -->
                                        <select class="form-select select2" id="member_code" name="member_code">
                                            <option value="disabled" data-id="disabled">Select Member Code</option>
                                            @foreach($m_codes as $m_code)
                                            <option value="{{$m_code->member_code}}" data-id="{{$m_code->member_name}}">{{$m_code->member_code}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                   <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
    
                                            <label class="form-label dropdown_report" for="member_name">Member Name</label>
                                                <select class="form-select select2" id="member_name" name="member_name">
                                                    <option value="disabled" data-id="disabled">Select Member Name</option>
                                                    @foreach($m_codes as $m_code)
                                                    <option value="{{$m_code->member_name}}" data-id="{{$m_code->member_code}}">{{$m_code->member_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
                                        <label class="form-label dropdown_report" for="exampleFormControlInput1">Price</label>
                                        <select class="form-select select2" id="sr_no" name="sr_no">
                                            <option value="disabled" data-id="disabled">Select Price</option>
                                            @foreach($qr_code_sr_nos as $qr_code_sr_no)
                                                <option value="{{$qr_code_sr_no->qr_serial_no}}" data-id="{{$qr_code_sr_no->qr_code}}">{{$qr_code_sr_no->qr_serial_no}}</option>
                                            @endforeach
                                        </select>        
                                    </div>
                                </div>

                                <div class="row">
                                   

                                      <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
    
                                            <label class="form-label" for="qr_code">QR Code</label>
                                                <select class="form-select select2" id="qr_code" name="qr_code">
                                                    <option value="disabled" data-id="disabled">Select QR Code</option>
                                                    @foreach($qr_codes as $qr_code)
                                                        <option value="{{$qr_code->qr_code}}" data-id="{{$qr_code->qr_serial_no}}">{{$qr_code->qr_code}}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
                                        <label class="form-label" for="exampleFormControlInput1">From Date</label>
                                        <input class="form-control date" id="start_date" name="start_date" type="date" />
                                    </div>

                                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-5">
                                        <label class="form-label" for="exampleFormControlInput1">To Date</label>
                                        <input class="form-control date" id="end_date" name="end_date" type="date"/>
                                    </div>
                                </div>
                            </form>
                        <!-- </div> -->

                        <div class="row row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div class="px-0 py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="report_list" class="dataTable" style="width: 100%;" aria-describedby="report_list_info">
                                                <thead>
                                                    <tr style="background-color: #e1ebf4 !important;">
                                                        <th>No.</th>

                                                        <th>Date</th>

                                                        <th>Member Code</th>

                                                        <th>Member Name</th>

                                                        <th>Price</th>

                                                        <th>QR Code</th>
                                                        <th>Scan By</th>
                                                        <th>Note</th>
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

</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $("#member_code").select2();
        $("#member_name").select2();
        $("#sr_no").select2();
        $("#qr_code").select2();
        // $(".date").datepicker();

        var token = $("meta[name='csrf-token']").attr("content");
        var member_table = $('#report_list').DataTable({
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
                searching: false,
                processing: true,
                bAutoWidth: false,
                ajax: {
                    url: "report-list",
                    type: 'post',
                    data: function (d) {
                        d.member_code = $('#member_code').val();
                        d.member_name = $('#member_name').val(); 
                        d.sr_no = $('#sr_no').val(); 
                        d.qr_code = $('#qr_code').val(); 
                        d.start_date = $('#start_date').val(); 
                        d.end_date = $('#end_date').val(); 

                    }
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

        $(document).on('change', '#member_code', function(e) {
            var member_name =  $(this).find(':selected').attr('data-id');
           
                $('#member_name').val(member_name).trigger('change.select2');
            member_table.ajax.reload(null, false);
        });
        $(document).on('change', '#member_name', function(e) {
            var member_code =  $(this).find(':selected').attr('data-id');
                $('#member_code').val(member_code).trigger('change.select2');
            member_table.ajax.reload(null, false);
        });
        $(document).on('change', '#sr_no', function(e) {
            var qr_code =  $(this).find(':selected').attr('data-id');
                $('#qr_code').val(qr_code).trigger('change.select2');
            member_table.ajax.reload(null, false);
        });
        $(document).on('change', '#qr_code', function(e) {
            var sr_no =  $(this).find(':selected').attr('data-id');
                $('#sr_no').val(sr_no).trigger('change.select2');
            member_table.ajax.reload(null, false);
        });
        $(document).on('change', '#start_date', function(e) {
            member_table.ajax.reload(null, false);
        });
        $(document).on('change', '#end_date', function(e) {
            member_table.ajax.reload(null, false);
        });


        $(document).on('click', '#export_to_excel', function() {
                var member_code = $('#member_code').val();
                var member_name = $('#member_name').val(); 
                var sr_no = $('#sr_no').val(); 
                var qr_code = $('#qr_code').val(); 
                var start_date = $('#start_date').val(); 
                var end_date = $('#end_date').val(); 

            var exportUrl = "{{ route('report-excel-export') }}" + "?member_code=" + member_code + "&member_name=" + member_name + "&sr_no=" + sr_no + "&qr_code=" + qr_code + "&start_date=" + start_date + "&end_date=" + end_date;
             window.location.href = exportUrl;
        });
    });

</script>
@endsection


