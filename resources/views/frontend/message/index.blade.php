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
    .suggest-box-name {
        margin-top: 5px;
        border: 1px solid #ddd;
        max-height: 180px;
        overflow-y: auto;
        background: #fff;
        display: none;
        padding: 8px;
        border-radius: 5px;
        position: absolute;
        /*display: block;*/
        z-index: 9999;
        /* max-width: 100%; */
        width: 94%
    }
    .suggest-box-phone {
        margin-top: 5px;
        border: 1px solid #ddd;
        max-height: 180px;
        overflow-y: auto;
        background: #fff;
        display: none;
        padding: 8px;
        border-radius: 5px;
        position: absolute;
        /*display: block;*/
        z-index: 9999;
        /* max-width: 100%; */
        width: 94%
    }


    .suggest-item-name{
        padding: 6px;
        cursor: pointer;
    }

    .suggest-item-name:hover {
        background: #f2f2f2;
    }

    .suggest-item-phone{
        padding: 6px;
        cursor: pointer;
    }

    .suggest-item-phone:hover {
        background: #f2f2f2;
    }
    .input-wrapper {
        position: relative;
        width: 100%;
    }

    .clear-icon {
        position: absolute;
        right: 25px;
        top: 45%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 20px;
        color: #888;
        display: none;
    }

    .clearable:not(:placeholder-shown) + .clear-icon {
        display: block;
    }
    .filter-search{
        width: 100%;
        padding: 8px 10px 8px 20px;
        font-size: 16px;
        outline: none;
        background: #fff;
        border: none;
        color: #737791;
        /* margin-left: 10px; */
        border: 1px solid #ced4da;
    }

    .suggest-item-name.no-click,
    .suggest-item-phone.no-click {
        cursor: default;
        color: #999;
        background: #f9f9f9;
    }

    .serach-img input{
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
    .button-mrg{
        display: flex;
        justify-content: space-between;
        align-items: center;
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
                            <div class="row">
                                <div class="col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12">
                                    <div class="input-wrapper serach-img">
                                        <input type="text" id="filter_name" placeholder="Search Name" name="name" class="filter-search clearable mb-2">
                                        <span class="clear-icon" data-target="filter_name">&times;</span>
                                    </div>
                                    <div id="nameSuggestions" class="suggest-box-name"></div>
                                </div>
                                <div class="col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12">
                                    <div class="input-wrapper serach-img">
                                        <input type="text" id="filter_phone" placeholder="Search Phone" name="phone" class="filter-search clearable mb-2">
                                        <span class="clear-icon" data-target="filter_phone">&times;</span>
                                    </div>   
                                    <div id="phoneSuggestions" class="suggest-box-phone"></div>
                                </div>
                                 <div class="col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12 button-mrg">
                                    <!-- <a href="javascript:void(0)" class="" id="clearFilter" style="color: #ff0000;">&times; </a> -->
                                    <div>
                                        <a href="" class="add-article-btn me-2" id="filter">Filter</a>
                                        <span id="clearFilter" style="color: #ff0000; cursor: pointer; display: none;" >&times; Clear</span>
                                        
                                    </div>
                                    
                                    <a href="" class="add-article-btn" id="exportExcelBtn">Export</a>
                                </div>
                                
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
                                                        <th>Sr&nbsp;No.</th>
                                                        <th>NAME</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>MESSAGE</th>
                                                        <th>CREATED&nbsp;DATE</th>
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

    $(document).ready(function () {
        var token = $("meta[name='csrf-token']").attr("content");

        var table = $('#massagelist').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            pageLength: 10,
            ajax: {
                url: "{{ route('message-list') }}",
                type: "POST",
                data: function (d) {
                    d._token = token;
                    d.name = $("#filter_name").val();
                    d.phone = $("#filter_phone").val();
                }
            },
            columns: [
                { data: 'id' },
                { data: 'member_name' },
                { data: 'phone' },
                { data: 'message_text' },
                { data: 'created_at' },
            ]
        });

        $("#clearFilter").click(function () {

            $("#filter_name").val('');
            $("#filter_phone").val('');

            table.ajax.reload(); 
        });



    function searchSuggestions(which) {
        let name = $("#filter_name").val();
        let phone = $("#filter_phone").val();

        // If both empty → reload table
        if (!name && !phone) {
            table.ajax.reload(null, false);
            $("#nameSuggestions, #phoneSuggestions").hide();
            return;
        }
            clearFilterToggle();

        $.post("{{ route('message.search-get') }}", {
            _token: token,
            name: name,
            phone: phone
        }, function(res) {

            // NAME search suggestions
            if (which === "name") {
                if (name !== "") {
                    let html = res.data.length
                        ? res.data.map(m =>
                            `<div class="suggest-item-name" data-name="${m.member_name}" data-phone="${m.phone}">
                                ${m.member_name}
                            </div>`
                          ).join('')
                        : `<div class="suggest-item-name no-click">No results found</div>`;

                    $("#nameSuggestions").html(html).show();
                } else {
                    $("#nameSuggestions").hide();
                }
                $("#phoneSuggestions").hide();
            }

            // PHONE search suggestions
            if (which === "phone") {
                if (phone !== "") {
                    let html2 = res.data.length
                        ? res.data.map(m =>
                            `<div class="suggest-item-phone" data-name="${m.member_name}" data-phone="${m.phone}">
                                ${m.phone}
                            </div>`
                          ).join('')
                        : `<div class="suggest-item-phone no-click">No results found</div>`;

                    $("#phoneSuggestions").html(html2).show();
                } else {
                    $("#phoneSuggestions").hide();
                }
                $("#nameSuggestions").hide();
            }
        });
    }

    // Input listeners
    $("#filter_name").on("keyup", function () {
        searchSuggestions("name");
    });

    $("#filter_phone").on("keyup", function () {
        searchSuggestions("phone");
    });

    // CLICK SUGGESTION → AUTO-FILL BOTH FIELDS
    $(document).on("click", ".suggest-item-name, .suggest-item-phone", function () {
        if ($(this).hasClass("no-click")) return;

        let name = $(this).data("name");
        let phone = $(this).data("phone");

        $("#filter_name").val(name);
        $("#filter_phone").val(phone);

        $("#nameSuggestions").hide();
        $("#phoneSuggestions").hide();

        // table.ajax.reload(null, false);
    });

        $("#filter").on("click", function(e){
            e.preventDefault();
            table.ajax.reload();
        });

        $(document).click(function(e){
            if(!$(e.target).closest('#filter_name, #filter_phone, .suggest-box-name,.suggest-box-phone').length){
                $(".suggest-box-name,.suggest-box-phone").hide();
            }
        });
    
        $(document).on("click", ".clear-icon", function () {
            let inputId = $(this).data("target");

            $("#" + inputId).val("");
clearFilterToggle();
            // // Also trigger live search reset if using search
            // $('#filter_name, #filter_phone').trigger('keyup');

            // For DataTable reload
            // table.ajax.reload(null, false);
        });
    });    


    $('#exportExcelBtn').click(function (e) {
        e.preventDefault(); 

        let name  = $('#filter_name').val();
        let phone = $('#filter_phone').val();

        $.ajax({
            url: "{{ route('export.messages') }}",
            type: "POST",
            data: {
                name: name,
                phone: phone,
                _token: "{{ csrf_token() }}"
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data) {
                let blob = new Blob([data]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "message_user.xlsx";
                link.click();
                toastr.success("Export successful!");
            },
            error: function () {
                 toastr.error("Export failed!");
            }
        });

    });

function clearFilterToggle(){

        let name  = $('#filter_name').val();
        let phone = $('#filter_phone').val();
        if(name !== '' || phone !== ''){
            $("#clearFilter").show();
        }else{
        $("#clearFilter").hide();

        }
}

  $(document).on("change input", "#filter_name, #filter_phone", clearFilterToggle);
   


</script>
@endsection
