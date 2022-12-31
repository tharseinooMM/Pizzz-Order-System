@extends('admin.layout.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            <div class="col-md-9">

              <div class="card">
                <div class="card-header p-2 bg-danger">
                  <legend class="text-center">Change Password</legend>
                </div>
                <div class="card-body">
                    @if(Session::has('notMatchPassword'))
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        {{ Session::get('notMatchPassword') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                      @endif

                      @if(Session::has('notSameErrors'))
                      <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                          {{ Session::get('notSameErrors') }}
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(Session::has('lengthErrors'))
                        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                            {{ Session::get('lengthErrors') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                          @endif

                          @if(Session::has('lengthSuccess'))
                          <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                              {{ Session::get('lengthSuccess') }}
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                  <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                      <form class="form-horizontal" method="post" action="{{ route('admin#changePassword', Auth()->user()->id) }}">
                        @csrf

                        <div class="form-group row mt-3">
                          <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                          <div class="col-sm-10">
                            <input type="password" class="form-control" name="oldPassword" placeholder="Old Password">
                            @if($errors->has('oldPassword'))
                            <p class="text-danger">{{ $errors->first('oldPassword') }}</p>
                            @endif
                          </div>
                        </div>


                          <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">New Password</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" name="newPassword" placeholder="New Password">
                              @if($errors->has('newPassword'))
                              <p class="text-danger">{{ $errors->first('newPassword') }}</p>
                              @endif
                            </div>
                          </div>

                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                  <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password">
                                  @if($errors->has('confirmPassword'))
                                  <p class="text-danger">{{ $errors->first('confirmPassword') }}</p>
                                  @endif
                                </div>
                            </div>

                            <div class="form-group row text-white float-right me-2">
                                <div class="offset-sm-2 col-sm-10">
                                    <input type="submit" value="Change" class="btn bg-danger text-white">
                                  {{-- <button type="submit" class="btn bg-danger text-white">Change</button> --}}
                                </div>
                              </div>
                         </form>

                         <div class="form-group row text-white float-right me-2">
                            <div class="offset-sm-2 col-sm-10">
                              <a href="{{ route('admin#profile') }}"><button class="btn bg-dark text-white">back</button></a>
                            </div>
                          </div>
                      </div>
                    </div>
                   </div>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>

@endsection
