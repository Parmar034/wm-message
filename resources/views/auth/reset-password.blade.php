@extends('layouts.app')

@section('content')
<style>
    .parsley-errors-list{
        padding: 0 !important;
    }
    .parsley-errors-list li{
        text-align: left !important;
    }
</style>
<div class="auth-wrapper">
    <div class="auth-content">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-5 text-center">
                    <img src="{{ asset('images/newlogo.webp') }}" height="80px" class="">
                </div>
                    <form method="POST" action="{{ url('/reset-password') }}" id="resetPasswordForm">
                        @csrf

                        <div class="text-left mb-3">
                            <label class="mb-0">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">@include('icons.lock')</span>
                                <input type="password" name="password" id="reset-password"
                                    class="form-control border-e-0"
                                    placeholder="New Password" required>
                                <span id="reset_password" style="cursor: pointer;" class="input-group-text">
                                    @include('icons.eye')
                                </span>
                            </div>
                        </div>

                        <div class="text-left mb-3">
                            <label class="mb-0">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text">@include('icons.lock')</span>
                                <input type="password" name="password_confirmation"
                                    id="confirm-reset-password"
                                    class="form-control border-e-0"
                                    placeholder="Confirm Password" required>
                                <span id="confirm_reset_password" style="cursor: pointer;" class="input-group-text">
                                    @include('icons.eye')
                                </span>
                            </div>

                            <!-- Error message -->
                            <div class="text-danger mt-1 d-none" id="passwordError">
                                Password and Confirm Password do not match.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-4 rounded-0" id="resetPasswordBtn">
                            Reset Password
                        </button>
                    </form>
                 
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

    <script>
        $('#reset_password').click(function() {
            var attr = $('#reset-password').attr('type');
            if (attr == 'password') {
                $('#reset-password').prop('type', 'text');
                $('#hide-eye').hide();
            } else {
                $('#reset-password').prop('type', 'password');
                $('#hide-eye').show();
            }
        });

         $('#confirm_reset_password').click(function() {
            var attr = $('#confirm-reset-password').attr('type');
            if (attr == 'password') {
                $('#confirm-reset-password').prop('type', 'text');
                $('#hide-eye').hide();
            } else {
                $('#confirm-reset-password').prop('type', 'password');
                $('#hide-eye').show();
            }
        });


        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            let password = document.getElementById('reset-password').value;
            let confirmPassword = document.getElementById('confirm-reset-password').value;
            let errorBox = document.getElementById('passwordError');
            let btn = document.getElementById('resetPasswordBtn');

            if (password !== confirmPassword) {
                e.preventDefault(); // stop form submit
                errorBox.classList.remove('d-none');
                return false;
            }

            // hide error & disable button on success
            errorBox.classList.add('d-none');
            btn.disabled = true;
            btn.innerText = 'Processing...';
        });
    </script>
@endsection