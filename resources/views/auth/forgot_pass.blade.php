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
                <form method="POST" action="{{ route('forgot-password-send-otp') }}"  id="forgotForm">
                    @csrf
                    <h3>Forgot Password</h3>

                    <input type="email" name="email" class="form-control mb-2"
                        placeholder="Enter your email" required>

                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror

                    <button class="btn btn-primary" id="sendOtpBtn">Send OTP</button>
                </form>

                 
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')

<script>
    document.getElementById('forgotForm').addEventListener('submit', function () {
        let btn = document.getElementById('sendOtpBtn');
        btn.disabled = true;
        btn.innerText = 'Sending...';
    });
</script>
@endsection

