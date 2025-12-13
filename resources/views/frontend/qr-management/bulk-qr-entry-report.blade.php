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
                        <div class="page-wrapper">
                            <div class="dashboard-heading">
                            <h4>Bulk QR Entry Import</h4>
                             <a href="javascript:;" id="sample-file-download" class="add-article-btn">@include('icons.download_icon')Download Sample File</a>
                      
                            </div>
                            <div class="row">
                               
                                        <div class="card-body">
                                           
                                                <form id="adduser" action="{{ route('qr-code-import.store') }}" method="POST"
                                                    data-parsley-validate="" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" id="user_id" name="user_id">

                                                {{-- <div class="row">  

                                                   <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-5">
                                                        <label class="form-label" for="exampleFormControlInput1">Select and upload an Excel file (.xlsx, .xls or .csv).</label>
                                                        <input class="form-control" id="member_code" name="member_code"
                                                            type="file" placeholder="No file selected."
                                                            data-parsley-required-message="Please Upload Excel File"
                                                            required="">
                                                    </div>
                                                </div> --}}

                                                <div class="row">
                                                        <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 mb-4">
                                                            <label class="form-label" for="excel_file">Select and upload an Excel file (.xlsx, .xls or .csv).</label>
                                                            <input class="form-control" id="excel_file" name="excel_file"
                                                                type="file" accept=".xlsx,.xls"
                                                                required data-parsley-required-message="Please upload an Excel file" value="{{old('excel_file')}}">
                                                        </div>
                                                        @error('qr_code')
                                                            <div class="alert text-danger ps-0">{{ $message }}</div>
                                                        @enderror
                                                </div>

                                                    <!-- Show selected file preview -->
                                                    <div id="selected-file-preview" class="mb-3" style="display: none;">
                                                        <span id="file-name" class="file_name_color"></span>
                                                        <button type="button" id="remove-file" class="btn btn-sm">@include('icons.file_remove')</button>
                                                    </div>

                                                   


                                                   
                                                   
                                                   <div class="row"> 
                                                    <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 mb-5">
                                                        <button type="submit"
                                                            class="btn btn-lg save-btn">Save</button>
                                                        
                                                    </div>
                                                   </div>
                                                </div>
                                                </form>
                                           
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
    // document.addEventListener("DOMContentLoaded", function () {
    //     const fileInput = document.getElementById('excel_file');
    //     const previewContainer = document.getElementById('selected-file-preview');
    //     const fileNameSpan = document.getElementById('file-name');
    //     const removeFileBtn = document.getElementById('remove-file');

    //     fileInput.addEventListener('change', function () {
    //         const file = fileInput.files[0];
    //         if (file) {
    //             fileNameSpan.textContent = file.name;
    //             previewContainer.style.display = 'inline-block';
    //         } else {
    //             previewContainer.style.display = 'none';
    //         }
    //     });

    //     removeFileBtn.addEventListener('click', function () {
    //         fileInput.value = ''; // Clear input
    //         fileNameSpan.textContent = '';
    //         previewContainer.style.display = 'none';
    //     });
    // });

    $(document).on('click', '#sample-file-download', function() {

            // var exportUrl = "{{ route('sample-file-download') }}";
        var exportUrl = "{{ route('sample-file-download') }}?t=" + new Date().getTime();
             window.location.href = exportUrl;
        });


</script>

    @endsection
