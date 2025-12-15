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
                                @if(isset($plan->id) && $plan->id != '')
                                   <h4>Edit Plan</h4>
                                @else
                                   <h4>Add Plan</h4>
                                @endif
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <form id="updateUser" action="{{ route('plan.store') }}" method="POST" data-parsley-validate>
                                        @csrf
                                        <input type="hidden" id="plan_id" name="plan_id"
                                            value="{{ $plan->id ?? old('plan_id') }}">
                                        
                                        <div class="row">    
                                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label class="form-label">Plan Name</label>
                                                <input class="form-control"
                                                    id="plan_name" name="plan_name" type="text"
                                                    placeholder="Please Plan name"
                                                    value="{{ $plan->plan_name ?? old('plan_name') }}"
                                                    required
                                                    data-parsley-required-message="Name is required"
                                                    data-parsley-errors-container="#error-member_name">
                                                <div class="error-message" id="error-member_name">
                                                    @error('plan_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row">
                                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label class="form-label">Plan Type</label>

                                                <select class="form-control" name="plan_type" required data-parsley-required-message="Plan type is required"
                                                        data-parsley-errors-container="#error-plan_type" @if(isset($plan)) disabled @endif>
                                                    <option value="">-- Select Plan Type --</option>
                                                    <option value="Monthly" {{ (old('plan_type', $plan->plan_type ?? '') == 'Monthly') ? 'selected' : '' }}> Monthly </option>
                                                    <option value="Annual" {{ (old('plan_type', $plan->plan_type ?? '') == 'Annual') ? 'selected' : '' }}> Annual </option>

                                                </select>

                                                <div class="error-message" id="error-plan_type">
                                                    @error('plan_type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label class="form-label d-block">Message Limit</label>

                                                <!-- Unlimited -->
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input billing-cycle"
                                                        type="radio"
                                                        name="billing_cycle"
                                                        id="billing_unlimited"
                                                        value="Unlimited"
                                                        @if((isset($plan->message_type) && $plan->message_type == 'Unlimited') || !isset($plan)) checked @endif
                                                    <label class="form-check-label" for="billing_unlimited">
                                                        Unlimited
                                                    </label>
                                                </div>

                                                <!-- Fixed -->
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input billing-cycle"
                                                        type="radio"
                                                        name="billing_cycle"
                                                        id="billing_fixed"
                                                        value="Fixed"
                                                        @if(isset($plan->message_type) && $plan->message_type == 'Fixed') checked @endif
                                                    <label class="form-check-label" for="billing_fixed">
                                                        Fixed
                                                    </label>
                                                </div>

                                                <!-- Fixed Input -->
                                                <div class="mt-2" id="fixedLimitBox" @if(isset($plan->message_type) && $plan->message_type == 'Fixed') style="display: block;" @else style="display: none;" @endif>
                                                    <input type="text"
                                                        name="message_limit" id="message_limit"
                                                        class="form-control"
                                                        placeholder="Enter message limit"
                                                        value="{{ old('message_limit', $plan->message_count ?? '') }}" data-parsley-required-message="Message limit is required">
                                                </div>

                                                <div class="error-message mt-1" id="error-billing_cycle">
                                                    @error('billing_cycle')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                @error('message_limit')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">    
                                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label class="form-label">Plan Price</label>
                                                <input class="form-control"
                                                    id="plan_price" name="plan_price" type="text"
                                                    placeholder="Please Plan Price"
                                                    value="{{ $plan->price ?? old('price') }}"
                                                    required
                                                    data-parsley-required-message="Plan Price is required"
                                                    data-parsley-errors-container="#error-plan_price">
                                                <div class="error-message" id="error-plan_price">
                                                    @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">    
                                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label class="form-label">Plan Description</label>
                                                <textarea class="form-control"
                                                    id="editor" name="plan_description" type="text"
                                                    placeholder="Please Plan Description"
                                                    required
                                                    data-parsley-required-message="Plan Description is required"
                                                    data-parsley-errors-container="#error-plan_description">{{ $plan->description ?? old('plan_description') }}</textarea>
                                                <div class="error-message" id="error-plan_description">
                                                    @error('plan_description')
                                                        <div class="text-danger">{{ $message }}</div>
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
        $(document).ready(function () {

            const editor = document.getElementById('editor');
            new RichTextEditor(editor);

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

            $(document).on('change', '.billing-cycle', function () {
                const selected = document.querySelector('input[name="billing_cycle"]:checked').value;
                const fixedBox = document.getElementById('fixedLimitBox');
                const fixedInput = fixedBox.querySelector('input');
                    if (selected === 'Fixed') {
                        fixedBox.style.display = 'block';
                        $('#message_limit').attr('required', true);
                    } else {
                        fixedBox.style.display = 'none';
                        fixedInput.value = '';
                        $('#message_limit').attr('required', false);
                    }
            });


        });
    </script>
    @endsection
