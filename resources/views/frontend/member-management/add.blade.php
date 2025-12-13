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
        </style>
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper new-class">
                            <div class="dashboard-heading">
                                <!-- <h4>{{ request()->segment(2) == 'edit' ? 'Edit' : 'Add' }} Member</h4> -->
                                @if(isset($member->id) && $member->id != '')
                                   <h4>Edit Member</h4>
                                @else
                                   <h4>Add Member</h4>
                                @endif
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <form id="updateUser" action="{{ route('member-management.store') }}" method="POST" data-parsley-validate>
                                        @csrf
                                        <input type="hidden" id="user_id" name="user_id"
                                            value="{{ $member->id ?? old('user_id') }}">
                                        
                                        <div class="row">    
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="form-label">Name</label>
                                                <input class="form-control"
                                                    id="member_name" name="name" type="text"
                                                    placeholder="Please member name"
                                                    value="{{ $member->name ?? old('name') }}"
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
                                                    value="{{ $member->email ?? old('email') }}"
                                                    required
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
                                                <label class="form-label" for="exampleFormControlInput1">Mobile
                                                    Number</label>
                                                <input class="form-control"
                                                    id="phone" name="phone" type="number" placeholder="1234567890"
                                                    value="{{ $member->phone ?? old('phone') }}"
                                                    required
                                                    data-parsley-required-message="Phone number is required"
                                                    data-parsley-errors-container="#error-phone">
                                                <div class="error-message" id="error-phone">
                                                    @error('phone')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($member))
                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                                                    <label class="form-check-label" for="change_password">
                                                        Change Password
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div id="password-section" @if(!empty($member)) style="display: none;" @endif>
                                            <div class="row">
                                                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="form-label">Password</label>
                                                    <div class="input-group">
                                                        <input type="password"
                                                            class="form-control"
                                                            id="password"
                                                            name="password"
                                                            placeholder="Enter password"
                                                            @if(empty($member)) required @endif
                                                            data-parsley-minlength="8"
                                                            data-parsley-required-message="Password is required"
                                                            data-parsley-minlength-message="Password must be at least 8 characters"
                                                            data-parsley-errors-container="#error-password">
                                                        <span class="input-group-text toggle-password" data-target="#password">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                    </div>
                                                    <div class="error-message" id="error-password">
                                                        @error('password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="form-label">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password"
                                                            class="form-control"
                                                            id="confirm_password"
                                                            name="password_confirmation"
                                                            placeholder="Confirm password"
                                                            @if(empty($member)) required @endif
                                                            data-parsley-equalto="#password"
                                                            data-parsley-required-message="Confirm password is required"
                                                            data-parsley-equalto-message="Passwords do not match"
                                                            data-parsley-errors-container="#error-confirm_password">
                                                        <span class="input-group-text toggle-password" data-target="#confirm_password">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                    </div>
                                                    <div class="error-message" id="error-confirm_password">
                                                        @error('confirmed')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
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
        $(document).ready(function () {

            $(document).on('change', '#change_password', function () {
                if ($(this).is(':checked')) {
                    $('#password-section').slideDown();

                    $('#password').attr('required', true);
                    $('#password_confirmation').attr('required', true);

                } else {
                    $('#password-section').slideUp();

                    $('#password').removeAttr('required').val('');
                    $('#password_confirmation').removeAttr('required').val('');

                    // Reset parsley errors
                    $('#password').parsley().reset();
                    $('#password_confirmation').parsley().reset();
                }
            });


            $(document).on('click', '.toggle-password', function () {
                const input = $($(this).data('target'));
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
    @endsection
