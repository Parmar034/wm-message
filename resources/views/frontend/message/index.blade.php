@extends('layouts.backend.index')
<style type="text/css">
    .dataTables_wrapper .dataTables_filter{
        float: left !important;
    }
    #massagelist_filter label{margin-bottom: 0px !important; font-size: 14px; float: left; padding-bottom: 5px;}
    #massagelist_filter {transform: translateY(-30px);}
    .table-responsive {overflow-x: unset !important;}
    #massagelist_filter .search-input{
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
    .dataTables_filter #massagelist_filter .filter-group label{
        font-size: 14px !important;
    }
    #exportExcelBtn{
        margin-top: 25px;
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
                                <h4>Message History</h4> 
                            </div>
                        </div>
                        <div class="row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div class="px-0 py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="massagelist" class="dataTable" style="width: 100%;"
                                                aria-describedby="member_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th style="padding-left: 24px;"><input type="checkbox" id="select_all"></th>
                                                        <th>Sr&nbsp;No.</th>
                                                        <th>MEMBER</th>
                                                        <th>NAME</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>MESSAGE</th>
                                                        <th>CREATED&nbsp;DATE</th>
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

                                                <select id="usersFilter" class="form-select search-dropdown d-none">
                                                    <option value="">All Users</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->member_name }}</option>
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
@endsection
@section('script')
<script>

    $(document).ready(function () {
        var token = $("meta[name='csrf-token']").attr("content");
        var isSelectAll;

        var table = $('#massagelist').DataTable({
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
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: false,
            pageLength: 10,
            ajax: {
                url: "{{ route('message-list') }}",
                type: "POST",
                data: function (d) {
                    d._token = token;
                    d.member_id = $('#membersFilter').val();
                    d.user_id = $('#usersFilter').val();
                },
                "dataSrc": function(response) {
                            isSelectAll = $('#select_all').is(':checked');
                            return response.data;
                        }
            },
            columns: [
                {
                    data: 'member_checkbox',
                    name: 'member_checkbox',
                    orderable: false,
                    searchable: false,
             
                },
                { data: 'id' },
                { data: 'member_name',visible: {{ Auth::user()->role != 'Admin' ? 'true' : 'false' }}, },
                { data: 'user_name' },
                { data: 'phone' },
                { data: 'message_text' },
                { data: 'created_at' },
            ]
        });

        table.on('draw', function () {
                    $('[data-toggle="popover"]').popover();
                    if (isSelectAll) {
                        $('.member_checkbox').prop('checked', true);
                    }
                });

        $('.dataTables_filter input').attr('placeholder', 'Search here ...');
        $('.dataTables_filter input').addClass('search-input');
        $('#massagelist_filter').addClass('search-box col-xxl-12 col-xl-12 col-lg-7 col-md-6 col-sm-12 col-12 d-flex gap-3');    

             const searchWrap = `
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                </div>
            `;

            // Members Filter Wrapper
            const membersFilterWrap = `
                <div class="filter-group">
                    <label class="filter-label">Members</label>
                </div>
            `;

            // Date Filter Wrapper
            const usersFilterWrap = `
                <div class="filter-group">
                    <label class="filter-label">Users</label>
                </div>
            `;



            $('#massagelist_filter').prepend(searchWrap);
            $('#massagelist_filter .filter-group:first')
                .append($('.dataTables_filter input'));

            // Append wrappers
            @if(Auth::user()->role != 'Admin')
            $('#massagelist_filter').append(membersFilterWrap);
            $('#massagelist_filter .filter-group:last').append($('#membersFilter').removeClass('d-none'));
            @endif

            $('#massagelist_filter').append(usersFilterWrap);
            $('#massagelist_filter .filter-group:last').append($('#usersFilter').removeClass('d-none'));

            // Export Button
            $('#exportExcelBtn').removeClass('d-none').appendTo('#massagelist_filter');


        $(document).on('change', '#membersFilter', function () {

            let member_id = $(this).val();

            $.ajax({
                url: "{{ route('get.user.list') }}",
                type: "POST",
                data: {
                    member_id: member_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    console.log(data);
                    let users = data.users;
                    let usersFilter = $('#usersFilter');
                    usersFilter.empty();
                    usersFilter.append('<option value="">All Users</option>');
                    users.forEach(function(user) {
                        usersFilter.append('<option value="' + user.id + '">' + user.member_name + '</option>');
                    });
                    usersFilter.trigger('change');  
                    table.ajax.reload();
                },
                error: function () {
                    toastr.error("Failed to fetch users!");
        
                }
            });
        });

        $(document).on('click', '#select_all', function(e) {
            var isChecked = $(this).prop('checked');
            $('.member_checkbox').prop('checked', isChecked);
        });
        

     


     $(document).on('click', '#exportExcelBtn', function () {

            var membersFilter = $('#membersFilter').val() || '';
            var usersFilter = $('#usersFilter').val() || '';


            // Check Select All checkbox
            var isSelectAll = $('#select_all').is(':checked');

            var selectedItemsString = '';

            if (isSelectAll) {
                // Special flag for backend
                selectedItemsString = 'all';
            } else {
                // Collect checked rows from DataTable (all pages)
                var selectedItems = $('#massagelist')
                    .DataTable()
                    .$('input[name="selected_items[]"]:checked')
                    .map(function () {
                        return $(this).val();
                    }).get();

                if (selectedItems.length === 0) {
                    alert('No Message selected or filters applied.');
                    return;
                }

                selectedItemsString = selectedItems.join(',');
            }


            // Build export URL
            var exportUrl = "{{ route('message.history.export.excel') }}"
                + "?selectedItems=" + encodeURIComponent(selectedItemsString)
                + "&members_filter=" + encodeURIComponent(membersFilter)
                + "&users_filter=" + encodeURIComponent(usersFilter);
            // Trigger Excel download
            window.location.href = exportUrl;
    });

    $(document).on('change', '#usersFilter', function () {
         table.ajax.reload();
    });


 });  
 
   


</script>
@endsection
