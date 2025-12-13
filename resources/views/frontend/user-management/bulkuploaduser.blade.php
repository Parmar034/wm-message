    @extends('layouts.backend.index')
    @section('main_content')
        <style>
            .checkbox-label {
                display: flex;
                align-items: center;
                font-size: 18px;
            }

            .checkbox-input {
                margin-right: 10px;
            }
        </style>
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper new-class">
                            <div class="dashboard-heading">
                                   <h4>Bulk User Upload</h4>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <form id="bulkupload" action="{{route('bulk-user.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                <label class="form-label" for="">Upload CSV/ Excel</label>
                                                <input class="form-control @error('file') is-invalid @enderror"
                                                    id="file" name="file" type="file" accept=".xlsx,.xls,.csv">
                                                @error('file')
                                                    <div class="alert text-danger ps-0">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 mb-5">
                                                <button type="submit" class="btn btn-lg save-btn">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row gx-4 px-3">
                                <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                    <div>
                                        <div class="px-0 py-3 dashboard_fix_tables">
                                            <div class="table-responsive">
                                                <table id="bulkmemberlist" class="dataTable" style="width: 100%;"
                                                    aria-describedby="member_info">
                                                    <thead>
                                                        <tr style="background-color: #E1EBF4 !important;">
                                                            <th>Sr&nbsp;No.</th>
                                                            <th>File Name</th>
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
                var table = $('#bulkmemberlist').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    lengthChange: false,
                    pageLength: 10,
                    language: {
                        search: "",
                        processing: '<i class="fa fa-spinner fa-spin" style="font-size:24px;color:rgb(75, 183, 245);"></i>',
                        paginate: {
                            next: '&gt;',
                            previous: '&lt;'
                        }
                    },
                    ajax: {
                        url: "{{ route('bulk-list') }}",
                        type: 'POST',
                        data: { _token: token }
                    },
                    columns: [
                        { data: 'serial_no', name: 'serial_no' },
                        { data: 'file_name', name: 'file_name' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });

                $('.dataTables_filter input').attr('placeholder', 'Search here ...');
                $('#memberlist_filter').addClass('search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12');
                $('#memberlist_filter input').addClass('search-input');
            });
        </script>
    @endsection
