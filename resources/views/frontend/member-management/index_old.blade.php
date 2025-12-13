@extends('layouts.backend.index')

@section('main_content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-wrapper-sub-wrapper">
                            <div class="dashboard-heading">
                                <h4>Member Management</h4>
                                <a href="{{ route('member-management.add') }}" class="add-article-btn">+ Add Member</a>
                            </div>
                            <div class="search-wrapper">
                                <div class="search-box col-xxl-4 col-xl-4 col-lg-7 col-md-6 col-sm-12 col-12">
                                    <img src="{{ asset('assets/icons/search_icon.svg') }}" alt="Search"
                                        class="search-icon">
                                    <input type="text" class="search-input" placeholder="Search here...">
                                </div>
                            </div>
                        </div>
                        <div class="row gx-4 px-3">
                            <div class="col-xl-12 col-md-12 dashboard_users px-0">
                                <div>
                                    <div class="px-0 py-3 dashboard_fix_tables">
                                        <div class="table-responsive">
                                            <table id="articlelist" class="dataTable no-footer" style="width: 100%;"
                                                aria-describedby="articlelist_info">
                                                <thead>
                                                    <tr style="background-color: #E1EBF4 !important;">
                                                        <th>MEMBERS&nbsp;CODE</th>
                                                        <th>MEMBERS&nbsp;NAME</th>
                                                        <th>PHONE&nbsp;NUMBER</th>
                                                        <th>LOCATION</th>
                                                        <th>ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="loading-skeleton">
                                                        <td><span class="skeleton-box w-100"></span></td>
                                                        <td><span class="skeleton-box w-75"></span></td>
                                                        <td><span class="skeleton-box w-50"></span></td>
                                                        <td><span class="skeleton-box w-50"></span></td>
                                                        <td>
                                                            <span class="skeleton-box icon"></span>
                                                            <span class="skeleton-box icon"></span>
                                                        </td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>1234567890</td>
                                                        <td>UK</td>
                                                        <td>
                                                            <a href="{{ route('member-management.add') }}" title="Edit">
                                                                <span
                                                                    class="pcoded-micon">@include('icons.table_edit_icon')</span>
                                                            </a>
                                                            <a href="#" id="delete-member-btn"
                                                                class="delete-member-btn" data-bs-toggle="modal"
                                                                data-bs-target="#deleteMemberModal" data-id="001"
                                                                title="Delete">
                                                                <span
                                                                    class="pcoded-micon">@include('icons.table_trash_icon')</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>001</td>
                                                        <td>John&nbsp;Doe</td>
                                                        <td>1234567890</td>
                                                        <td>UK</td>
                                                        <td>
                                                            <a href="{{ route('member-management.add') }}" title="Edit">
                                                                <span
                                                                    class="pcoded-micon">@include('icons.table_edit_icon')</span>
                                                            </a>
                                                            <a href="#" id="delete-member-btn"
                                                                class="delete-member-btn" data-bs-toggle="modal"
                                                                data-bs-target="#deleteMemberModal" data-id="001"
                                                                title="Delete">
                                                                <span
                                                                    class="pcoded-micon">@include('icons.table_trash_icon')</span>
                                                            </a>
                                                        </td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.querySelector('#articlelist tbody'),
                searchInput = document.querySelector('.search-input'),
                editRouteTemplate = "{{ route('member-management.edit', ['id' => ':id']) }}";

            tbody.addEventListener('click', function(e) {
                const btn = e.target.closest('.delete-member-btn');
                if (!btn) return;

                const memberId = btn.getAttribute("data-id"),
                    deleteUrl = `{{ route('member-management.delete', ['id' => ':id']) }}`.replace(':id',
                        memberId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This member will be deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: "POST",
                            data: {
                                _method: 'DELETE'
                            },
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            success: function() {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "The member has been deleted.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    fetchMembers(searchInput.value, false);
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error("Deletion failed:", error);
                                Swal.fire("Error",
                                    "Something went wrong while deleting.", "error");
                            }
                        });
                    }
                });
            });

            function renderMembers(status, message, data, showToastr = true) {
                tbody.querySelector('.loading-skeleton')?.classList.add('d-none');
                tbody.querySelector('.text-center')?.classList.add('d-none');
                if (status && Array.isArray(data) && data.length > 0) {
                    tbody.innerHTML = '';
                    data.forEach(member => {
                        const row = document.createElement('tr');
                        const editUrl = editRouteTemplate.replace(':id', member.id);
                        row.innerHTML = `
                            <td>${member.member_code ?? ''}</td>
                            <td>${member.member_name ?? ''}</td>
                            <td>${member.phone ?? ''}</td>
                            <td>${member.location ?? ''}</td>
                            <td>
                                <a href="${editUrl}" title="Edit">
                                    <span class="pcoded-micon">@include('icons.table_edit_icon')</span>
                                </a>
                                <a href="javascript:;" class="delete-member-btn"
                                    data-id="${member.id}" title="Delete">
                                    <span class="pcoded-micon">@include('icons.table_trash_icon')</span>
                                </a>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = `
                        <tr class="text-center">
                            <td colspan="5">List not found.</td>
                        </tr>
                    `;
                }
                if (showToastr) {
                    toastr[status ? 'success' : 'error'](message, status ? 'Success' : 'Error');
                }
            }

            function fetchMembers(search = '', showToastr = true) {
                // Show skeleton while loading
                tbody.innerHTML = `
                    <tr class="loading-skeleton">
                        <td><span class="skeleton-box w-100"></span></td>
                        <td><span class="skeleton-box w-75"></span></td>
                        <td><span class="skeleton-box w-50"></span></td>
                        <td><span class="skeleton-box w-50"></span></td>
                        <td>
                            <span class="skeleton-box icon"></span>
                            <span class="skeleton-box icon"></span>
                        </td>
                    </tr>
                `;
                let url = `{{ route('member-list') }}`;
                if (search) {
                    url += `?search=${encodeURIComponent(search)}`;
                }
                fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(({
                        status,
                        message,
                        data
                    }) => {
                        renderMembers(status, message, data, showToastr);
                    })
                    .catch(error => {
                        toastr.error('An error occurred', 'Error');
                    });
            }

            // Debounce utility
            function debounce(fn, delay) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            // Initial fetch (show toastr)
            fetchMembers('', true);

            // Live search (do not show toastr)
            searchInput.addEventListener('input', debounce(function() {
                fetchMembers(this.value, false);
            }, 300));
        });
    </script>
@endsection
