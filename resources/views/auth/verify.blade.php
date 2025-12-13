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
                <form method="POST" action="{{ route('verifyOtp') }}" id="verifyForm">
                    @csrf
                    <h3>Verify OTP</h3>

                    <input type="text" name="otp" class="form-control mb-2"
                        placeholder="Enter OTP" required>

                    @error('otp') <div class="text-danger">{{ $message }}</div> @enderror

                    <button class="btn btn-primary mb-4 rounded-0" id="verifyBtn">Verify</button>
                </form>

                 
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    document.getElementById('verifyForm').addEventListener('submit', function () {
        let btn = document.getElementById('verifyBtn');
        btn.disabled = true;
        btn.innerText = 'Sending...';
    });
</script>
@endsection