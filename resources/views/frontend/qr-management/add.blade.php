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
                        <!-- <h4>New Qr Entry</h4> -->
                       <h4>
                            @if(isset($qr_code->id) && $qr_code->id != '')
                                Edit Qr Entry
                            @else
                                New Qr Entry
                            @endif
                        </div>
                            <div class="row">
                               
                                        <div class="card-body">
                                           
                                                <form id="adduser" action="{{ route('qr-management.store') }}" method="POST"
                                                    data-parsley-validate="">
                                                    @csrf
                                                    <input type="hidden" id="qrcode_id" name="qrcode_id" value="{{$qr_code->id ?? old('qrcode_id')}}">

                                                <div class="row">  
                                                   <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                        <label class="form-label" for="exampleFormControlInput1">Price</label>
                                                        <!-- <input class="form-control" id="qr_serial_no" name="qr_serial_no"
                                                            type="text" placeholder="Please enter Price"
                                                            data-parsley-required-message="Please Enter Price"
                                                            required="" value="{{$qr_code->qr_serial_no ?? old('qr_serial_no')}}"> -->
                                                            <input class="form-control" id="qr_serial_no" name="qr_serial_no"
                                                                type="text" placeholder="Please enter Price"
                                                                pattern="^\d+(\.\d{1,2})?$"
                                                                inputmode="decimal"
                                                                data-parsley-pattern="^\d+(\.\d{1,2})?$"
                                                                data-parsley-required-message="Please Enter Price"
                                                                data-parsley-pattern-message="Enter valid price"
                                                                required
                                                                value="{{ $qr_code->qr_serial_no ?? old('qr_serial_no') }}">

                                                    </div>
                                                    <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                        <label class="form-label"
                                                            for="exampleFormControlInput1">QR Code</label>
                                                        <input class="form-control" id="qr_code" name="qr_code"
                                                            type="text" placeholder="Please enter QR code"
                                                            data-parsley-required-message="Please Enter Qr code"
                                                            required="" value="{{$qr_code->qr_code ?? old('qr_code')}}">
                                                        @error('qr_code')
                                                            <div class="alert text-danger ps-0">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    </div>
                                                   
                                                   <div class="row"> 
                                                    <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 mb-5">
                                                        <button type="submit"
                                                            class="btn btn-lg save-btn">Save</button>
                                                        
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
            
