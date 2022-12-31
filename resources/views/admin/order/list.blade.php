@extends('admin.layout.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <span class="fs-4 d-flex justify-content-center ">
                    <small><i class="text-green me-2 fas fa-duotone fas fa-eye "></i>Total -{{{ $order->total() }}}
                </span>
                <a href="{{ route('admin#orderDownload') }}"><button class="btn btn-sm bg-success text-whitcomposer require usmanhalalit/laracsv:^2.0e ms-1">Download Order List</button></a>

                <div class="card-tools float-end">
                 <form action="{{ route('admin#orderSearch') }}" method="get">
                    @csrf
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="searchData" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                 </form>
                </div>
              </div>
              <!-- /.card-header -->
               <div class="card-body table-responsive p-0">

                <table class="table table-hover text-nowrap text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer Name</th>
                      <th>Pizza Name</th>
                      <th>Pizza Count</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($order as $item)
                    <tr>
                        <td>{{ $item -> order_id }}</td>
                        <td>{{ $item -> Customer_Name }}</td>
                        <td>{{ $item -> Pizza_Name }}</td>
                        <td>{{ $item -> count }} pieces</td>
                        <td>{{ $item -> order_time }}</td>
                        <td></td>


                    @endforeach

                  </tbody>
                </table>

                <span class="pagination justify-content-center mt-3">{{ $order->links() }}</span>

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
