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
                                <h4>Report Send Member</h4> 
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
@endsection
@section('script')
    <script>
    </script>
@endsection
