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
            .error-message {
                height: 30px;
            }
            .iti {
                width: 100%;
            }

            .iti__country-list {
                z-index: 9999; /* dropdown above modal */
            }

            .iti--separate-dial-code .iti__selected-flag {
                background-color: #f8f9fa;
                border-right: 1px solid #ced4da;
            }
            #phone {
                padding-left: 100px !important;
            }
            .iti__flag-container{
                width: 100px;
            }
        </style>
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper new-class">
                            <div class="dashboard-heading">
                                <!-- <h4>{{ request()->segment(2) == 'edit' ? 'Edit' : 'Add' }} Member</h4> -->
                                @if(isset($member->id) && $member->id != '')
                                   <h4>Edit User</h4>
                                @else
                                   <h4>Add User</h4>
                                @endif
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <form id="updateUser" action="{{ route('user-management.store') }}" method="POST" data-parsley-validate>
                                        @csrf
                                        <input type="hidden" id="user_id" name="user_id"
                                            value="{{ $member->id ?? old('user_id') }}">


                                        @if(Auth::user()->role == 'Super Admin')    
                                        <div class="row">    
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label">Member</label>
                                                <select class="form-control" name="member" required data-parsley-required-message="Member is required"
                                                        data-parsley-errors-container="#error-member">
                                                    <option value="">-- Select Plan Type --</option>
                                                    @foreach($members as $mem)
                                                        <option value="{{ $mem->id }}" {{ (isset($member->user_id) && $member->user_id == $mem->id) ? 'selected' : (old('Member') == $mem->id ? 'selected' : '') }}>{{ $mem->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="error-message" id="error-member">
                                                    @error('member')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>  
                                        @endif  
                                        
                                        <div class="row">    
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label">Name</label>
                                                <input class="form-control"
                                                    id="member_name" name="name" type="text"
                                                    placeholder="Please member name"
                                                    value="{{ $member->member_name ?? old('name') }}"
                                                    required
                                                    data-parsley-required-message="Name is required"
                                                    data-parsley-errors-container="#error-member_name">
                                                <div class="error-message" id="error-member_name">
                                                    @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label" for="exampleFormControlInput1">Email</label>
                                                <input class="form-control"
                                                    id="email" name="email" type="email"
                                                    placeholder="Please enter email"
                                                    value="{{ $member->member_email ?? old('email') }}"
                                                    data-parsley-required-message="Email is required"
                                                    data-parsley-errors-container="#error-email">
                                                <div class="error-message" id="error-email">
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label">Mobile Number</label>

                                                <input type="tel"
                                                    class="form-control"
                                                    id="phone"
                                                    name="phone"
                                                    value="{{ old('phone', $member->phone ?? '') }}"
                                                    required
                                                    data-parsley-required-message="Phone number is required"
                                                    data-parsley-errors-container="#error-phone">

                                                
                                                <input type="hidden" name="country_code" id="country_code"
                                                    value="{{ old('country_code', $member->country_code ?? '') }}">

                                                <div class="error-message" id="error-phone" class="text-danger"></div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                <label class="form-label" for="exampleFormControlInput1">Description</label>
                                                <textarea class="form-control" name="description" id="description">{{ old('description', $member->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="alert text-danger ps-0">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4">
                                                <button type="submit" class="btn btn-lg save-btn">Save</button>
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
            const phoneInput = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#country_code");

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "auto",
                separateDialCode: true,
                preferredCountries: ["in", "us", "gb"],
                geoIpLookup: function (callback) {
                    fetch("https://ipapi.co/json/")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("in"));
                },
                utilsScript:
                    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            });

            // SET VALUE ON EDIT
            @if(!empty($member?->phone))
                iti.setNumber("{{ $member->country_code . $member->phone }}");
            @endif

            // UPDATE hidden country code
            phoneInput.addEventListener("countrychange", function () {
                countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
            });

            // SET ON LOAD
            countryCodeInput.value = '+' + iti.getSelectedCountryData().dialCode;
        </script>

    @endsection
