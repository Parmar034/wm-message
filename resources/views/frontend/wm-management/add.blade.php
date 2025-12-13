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
        </style>
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper new-class">
                            <div class="dashboard-heading">
                                <!-- <h4>{{ request()->segment(2) == 'edit' ? 'Edit' : 'Add' }} Member</h4> -->
                                @if(isset($member->id) && $member->id != '')
                                   <h4>Edit User</h4>
                                @else
                                   <h4>Add User</h4>
                                @endif
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <form id="updateUser" action="{{ route('user-management') }}" method="POST">
                                        @csrf
                                        <input type="hidden" id="member_id" name="member_id"
                                            value="{{ $member->id ?? old('member_id') }}">
                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                <label class="form-label" for="exampleFormControlInput1">Mobile
                                                    Number</label>
                                                <input class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone" type="number" placeholder="1234567890"
                                                    value="{{ $member->phone ?? old('phone') }}">
                                                @error('phone')
                                                    <div class="alert text-danger ps-0">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-5">
                                                <label class="form-label" for="exampleFormControlInput1">Description</label>
                                                <textarea class="form-control" name="description" id="description">{{ old('description', $member->description ?? '') }}</textarea>
                                                @error('phone')
                                                    <div class="alert text-danger ps-0">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xxl-2 col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 mb-5">
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
        <script></script>
    @endsection
