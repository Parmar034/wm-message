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
                                   <h4>Plan Assign</h4>
                                @else
                                   <h4>Plan Assign</h4>
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
                                                <label class="form-label">Plan Name</label>
                                                <select class="form-control" name="modal_plan_name" id="modal_plan_name" required data-parsley-required-message="Plan is required"
                                                        data-parsley-errors-container="#error-modal_plan_name">
                                                    <option value="">-- Select Plan --</option>
                                                    @foreach($plans as $plan)
                                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" data-type="{{ $plan->plan_type }}" data-limit="{{ $plan->message_count }}" {{ (old('plan_name', $member->plan_id ?? '') == $plan->id) ? 'selected' : '' }}> {{ $plan->plan_name }} </option>
                                                    @endforeach
                                                </select>

                                                    @error('plan_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">    
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div id="plan-details" style="display:none;" class="mt-3">
                                                    <p><strong>Plan Type:</strong> <span id="plan-type"></span></p>
                                                    <p><strong>Plan Price:</strong> ₹<span id="plan-price"></span></p>
                                                    <p><strong>Message Limit:</strong> <span id="plan-limit"></span></p>
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

            $(document).on('change', '#plan_name', function () {

                let selected = $(this).find(':selected');

                let price = selected.data('price');
                let limit = selected.data('limit');
                let type = selected.data('type');


                if ($(this).val()) {
                    $('#plan-price').text(price);
                    $('#plan-limit').text(limit ? limit : 'Unlimited');
                    $('#plan-type').text(type);
                    $('#plan-details').slideDown();
                } else {
                    $('#plan-details').slideUp();
                }
            });


        });
    </script>
    @endsection
