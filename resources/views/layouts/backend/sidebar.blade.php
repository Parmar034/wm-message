{{-- filepath: resources/views/layouts/sidebar.blade.php --}}
<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo" style="height: 155px !important;">
            <!-- <img class="mt-0 img-fluid" src="{{ asset('images/logo.png') }}"style="width:209px !important;height:auto !important;"
                alt="My Lapo QR System"> -->
                <!-- <img class="mt-0 img-fluid" src="{{ asset('images/logo.webp') }}"style="width:209px !important;height:auto !important;"
                alt="My Lapo QR System"> -->
                <img class="mt-0 img-fluid" src="{{ asset('images/newlogo.webp') }}"style="width:209px !important;height:auto !important;"
                alt="My Lapo QR System">
            {{-- <h4 class="mb-0 fw-bold">My&nbsp;Lapo&nbsp;QR&nbsp;System</h4> --}}
        </div>


        <div class="navbar-content scroll-div ps ps--active-y">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item {{ \Request::route()->getName() == 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <span class="pcoded-micon">@include('icons.Dashboard')</span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
                @if(Auth::user()->role == 'SuerAdmin')
                <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['member-management', 'member-management.add', 'member-management.edit']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('member-management') }}">
                        <span class="pcoded-micon">@include('icons.member-management')</span>
                        <span class="pcoded-mtext">Member&nbsp;Management</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->role == 'SuerAdmin')
                <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['user-management', 'user-management.add', 'user-management.edit']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('user-management') }}">
                        <span class="pcoded-micon">@include('icons.member-management')</span>
                        <span class="pcoded-mtext">User&nbsp;Management</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->role == 'SuerAdmin')
                <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['bulk-user.upload']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('bulk-user.upload') }}">
                        <span class="pcoded-micon">@include('icons.Bulkupload')</span>
                        <span class="pcoded-mtext">Bulk&nbsp;Upload Users</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->role == 'SuerAdmin')
                <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['message.index']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('message.index') }}">
                        <span class="pcoded-micon">@include('icons.message')</span>
                        <span class="pcoded-mtext">Message&nbsp;History</span>
                    </a>
                </li>
                @endif

                <!-- <li class="nav-item pcoded-hasmenu {{ in_array(\Request::route()->getName(), ['user-management', 'user-management.add', 'user-management.edit', 'bulk-user.upload']) ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)" class="nav-link">
                        <span class="pcoded-micon">@include('icons.member-management')</span>
                        <span class="pcoded-mtext">User Management</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('user-management') ? 'active' : '' }}">
                            <a href="{{ route('user-management') }}">All Users</a>
                        </li>
                        <li class="{{ request()->routeIs('bulk-user.upload') ? 'active' : '' }}">
                            <a href="{{ route('bulk-user.upload') }}">Bulk Upload Users</a>
                        </li>
                    </ul>
                </li> -->

<!--                 <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['wm-management.add']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('wm-management.add') }}">
                        <span class="pcoded-micon">@include('icons.member-management')</span>
                        <span class="pcoded-mtext">WM&nbsp;Management</span>
                    </a>
                </li>
                <li
                    class="nav-item {{ in_array(\Request::route()->getName(), ['qr-management', 'qr-management.add', 'qr-management.edit', 'qr-management.bulk-qr-entry-report']) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('qr-management') }}">
                        <span class="pcoded-micon">@include('icons.qr-management')</span>
                        <span class="pcoded-mtext">QR&nbsp;Management</span>
                    </a>
                </li>
                <li class="nav-item {{ \Request::route()->getName() == 'reports' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('reports') }}">
                        <span class="pcoded-micon">@include('icons.reports')</span>
                        <span class="pcoded-mtext">Reports</span>
                    </a>
                </li>  -->
                <li class="nav-item">
                    <a class="nav-link" href="javascript:;" id="signoutBtn">
                        <span class="pcoded-micon">@include('icons.Dashboard')</span>
                        <span class="pcoded-mtext">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@section('side-bar-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logoutBtn = document.getElementById("signoutBtn");

            logoutBtn.addEventListener("click", function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will be logged out.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Sign Out"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('logout') }}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            success: function() {
                                // Redirect to login or homepage after logout
                                window.location.href = "/login";
                            },
                            error: function(xhr, status, error) {
                                console.error("Logout failed:", error);
                                Swal.fire("Error", "Logout failed.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
