@extends('admin.layout.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            <div class="col-md-9">
                <a href="{{ route('admin#category') }}"><div class="btn btn-sm bg-danger text-white mb-3">Back</div></a>
              <div class="card">
                <div class="card-header p-2">
                  <legend class="text-center">Edit Category</legend>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                      <form class="form-horizontal" method="post" action="{{ route('admin#updateCategory', $category->category_id) }}">
                        @csrf
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name', $category->category_name) }}">
                          </div>
                        </div>

                    @if($errors->has('name'))
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                    @endif

                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn bg-dark text-white">Update</button>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>

@endsection
