@extends('admin.layout.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <div class="row mt-4">
          <div class="col-8 offset-2 mt-3">
            <h3 class="text-danger">{{ $pizza[0]->categoryName }}</h3>
            <div class="card">
              <div class="card-header bg-secondary shadow">
                <span class="fs-4 d-flex justify-content-center ">
                    <small><i class="text-green me-2 fas fa-duotone fas fa-eye "></i>Total - {{ $pizza->total() }}</small>
                </span>
              </div>
              <!-- /.card-header -->
               <div class="card-body table-responsive p-0">

                <table class="table table-hover text-nowrap text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Pizza Name</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($pizza as $item)
                    <tr>
                        <td>{{ $item -> pizza_id }}</td>
                        <td>
                            <img src="{{ asset('./upload/'.$item->image) }}" width="120px;" height="120px" class="border rounded">
                        </td>
                        <td>{{ $item -> pizza_name }}</td>
                        <td>{{ $item -> price }}</td>
                        <td></td>
                      </tr>

                    @endforeach

                  </tbody>
                </table>

                <span class="pagination justify-content-center mt-3">{{ $pizza->links() }}</span>

              </div>
              <div class="card-footer">
                <a href="{{ route('admin#category') }}">
                <button class="btn btn-danger text-white float-end">Back</button>
                </a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection
