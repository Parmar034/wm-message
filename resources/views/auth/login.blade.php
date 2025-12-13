@extends('layouts.app')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="mb-5 text-center">
                <img src="{{ asset('images/newlogo.webp') }}" height="80px" class="">
            </div>
            <div class="card">

                <div class="card-body text-center">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <h3 class="mt-3 mb-2">Log in</h3>
                        <div class="text-left mb-3">
                            <label class="mb-0">User Name</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">@include('icons.person')</span>
                                <input type="text" class="form-control" placeholder="Enter user name" name="user_name"
                                    @if (isset($_COOKIE['user_name'])) value="{{ $_COOKIE['user_name'] }}" @endif>
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <label class="mb-0">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">@include('icons.lock')</span>
                                <input type="password" id="password" class="form-control border-e-0" placeholder="password"
                                    name="password"
                                    @if (isset($_COOKIE['password'])) value="{{ $_COOKIE['password'] }}" @endif>
                                <span id="show_password" style="cursor: pointer;" class="input-group-text">
                                    @include('icons.eye')
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for=""></label>
                            <button class="btn btn-primary mb-4 rounded-0" type="submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
