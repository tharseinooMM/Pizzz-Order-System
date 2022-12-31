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
              <div class="card-header bg-primary">
                <h4 class="text-center mt-3">Customers Contacts</h4>

                <span class="fs-4 d-flex justify-content-center ">
                    <small><i class="text-dark me-2 fas fa-duotone fas fa-eye "></i>Total - {{ $contact->total() }}</small>
                </span>

                <div class="card-tools">
                 <form action="{{ route('admin#contactSearch') }}" method="get">
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
                      <th>Name</th>
                      <th>Email</th>
                      <th>Message</th>
                    </tr>
                  </thead>
                  <tbody>

                    @if ($status == 0)
                    <tr class="bg-warning">
                        <td colspan="4">
                            <small class="text-dark">There is no data.</small>
                        </td>
                    </tr>
                    @else
                    @foreach ($contact as $item)
                    <tr>
                        <td>{{ $item->contact_id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->message }}</td>
                      </tr>
                    @endforeach
                    @endif

                  </tbody>
                </table>

                <span class="pagination justify-content-center mt-3">{{ $contact->links() }}</span>

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
