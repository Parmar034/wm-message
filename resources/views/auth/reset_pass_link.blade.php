@extends('layouts.app')

@section('content')
    <style>
        .parsley-errors-list {
            padding: 0 !important;
        }

        .parsley-errors-list li {
            text-align: left !important;
        }
    </style>
    <div class="auth-wrapper">
        <div class="auth-content custom_login">
            <div class="mb-4 text-center">
                <img src="{{ asset('images/logo.png') }}" width="100px" height="80px" class="">
            </div>
            <div class="card">
                <div class="card-body text-center">

                    <form method="POST" action="{{ route('reset-password') }}" data-parsley-validate="">
                        @csrf
                        <h3 class="mb-3">Reset Password</h3>
                        <div class="text-left mb-3">
                            <label class="mb-0">Email</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">{{ __('@') }}</span>
                                <input type="email" class="form-control" placeholder="Enter email address" name="email"
                                    @if (isset($_COOKIE['email'])) value="{{ $_COOKIE['email'] }}" @endif>
                            </div>
                        </div>
                        <span id="content_required"></span>
                        <button class="btn btn-primary mb-4 rounded-0" type="submit">Send Password Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
