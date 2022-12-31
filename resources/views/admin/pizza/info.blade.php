@extends('admin.layout.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            <div class="col-md-9">
                <a href="{{ route('admin#pizza') }}"><div class="btn btn-sm bg-danger text-white mb-3">Back</div></a>
              <div class="card">
                <div class="card-header p-2 bg-success text-white">
                  <legend class="text-center">Pizza Info</legend>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane d-flex" id="activity">
                        <div class="text-center mt-2">
                            <img class="border rounded shadow" src="{{ asset('upload/'.$pizza->image) }}" style="width : 200px; height : 200px">
                        </div>
                       <div class="ms-4">
                        <div class="#">
                            <b>Name</b> : <span>{{ $pizza->pizza_name }}</span>
                        </div>
                        <div class="mt-2">
                            <b>Price</b> : <span>{{ $pizza->price }} Kyats</span>
                        </div>
                        <div class="mt-2">
                            <b>Publish Status</b> :
                            <span>
                                @if ( $pizza->publish_status == 1 )
                                    Yes
                                    @else
                                    No
                                @endif
                            </span>
                        </div>
                        <div class="mt-2">
                            <b>Discount Price</b> : <span>{{ $pizza->discount_price }} Kyats</span>
                        </div>
                        <div class="mt-2">
                            <b>Buy One Get One Status</b> :
                            <span>
                                @if ( $pizza->buyOne_getOne_status == 1 )
                                    Yes
                                    @else
                                    No
                                @endif
                            </span>
                        </div>
                        <div class="mt-2">
                            <b>Waiting Time</b> : <span>{{ $pizza->waiting_time }} hours</span>
                        </div>
                        <div class="mt-2">
                            <b>Description</b> : <span>{{ $pizza->description }}</span>
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
