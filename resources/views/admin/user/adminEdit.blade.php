@extends('admin.layout.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            <div class="col-md-9">

              <div class="card">
                <div class="card-header p-2 bg-primary">
                  <legend class="text-center">Edit Admin</legend>
                </div>
                <div class="card-body">
                    @if(Session::has('updateSuccess'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ Session::get('updateSuccess') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                      @endif

                  <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                      <form class="form-horizontal" method="post" action="{{ route('admin#updateAdmin',$data->id) }}">
                        @csrf
                        <div class="form-group row mt-3">
                          <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="{{ old('name', $data->name) }}" placeholder="Name">
                            @if($errors->has('name'))
                            <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                          </div>
                        </div>


                          <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                              <input type="email" class="form-control" name="email" value="{{ old('email', $data->email) }}" placeholder="Email">
                              @if($errors->has('email'))
                              <p class="text-danger">{{ $errors->first('email') }}</p>
                              @endif
                            </div>
                          </div>

                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">phone</label>
                                <div class="col-sm-10">
                                  <input type="number" class="form-control" name="phone" value="{{ old('phone', $data->phone) }}" placeholder="Phone Number">
                                  @if($errors->has('phone'))
                                  <p class="text-danger">{{ $errors->first('phone') }}</p>
                                  @endif
                                </div>
                            </div>

                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" name="address" value="{{ old('address', $data->address) }}" placeholder="Address">
                            @if($errors->has('address'))
                            <p class="text-danger">{{ $errors->first('address') }}</p>
                            @endif
                          </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select name="role" class="form-control">
                                    <option value="">Choose Role</option>
                                    @if($data->role == 'user')
                                    <option value="admin">Admin</option>
                                    <option value="user" selected>User</option>
                                    @else
                                    <option value="admin" selected>Admin</option>
                                    <option value="user">User</option>
                                    @endif
                                </select>

                                @if($errors->has('role'))
                                <p class="text-danger">{{ $errors->first('role') }}</p>
                                @endif
                          </div>

                            <div class="form-group row mt-4">
                                <div class="offset-sm-2 col-sm-10">
                                  <button type="submit" class="btn bg-dark text-white">Update</button>
                                </div>
                              </div>
                         </form>
                      </div>
                    </div>

                        <!-- Button trigger modal -->
                        {{-- <a href="{{ route('admin#changePasswordPage') }}"><p class="text-danger float-right">
                            Change Password
                          </p></a> --}}

                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>

@endsection
