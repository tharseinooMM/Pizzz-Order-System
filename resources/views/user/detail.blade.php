@extends('user.layout.style')

@section('content')

<div class="row mt-5 d-flex justify-content-center">

    <div class="col-4 ">
        <img src="{{ asset('/upload/'.$pizza->image) }}" class="img-thumbnail" width="100%">            <br>
        <a href="{{ route('user#order') }}"><button class="btn btn-success float-end mt-2 col-12"><i class="fas fa-shopping-cart"></i> Order</button></a>
        <a href="{{ route('user#index') }}">
            <button class="btn bg-dark text-white" style="margin-top: 20px;">
                <i class="fas fa-backspace"></i> Back
            </button>
        </a>
    </div>
    <div class="col-6">
        <h5>Name</h5>
        <small class="text-muted">{{ $pizza->pizza_name }}</small> <hr>
        <h5>Price</h5>
        <small class="text-muted">{{ $pizza->price }} Kyats</small> <hr>
        <h5>Discount Price</h5>
        <small class="text-danger">{{ $pizza->discount_price }} Kyats</small> <hr>
        <h5>Buy one Get one</h5>
        <small class="text-muted">
            @if ($pizza->buyOne_getOne_status == 0)
                Not Have
            @else
                Have
            @endif
        </small> <hr>
        <h5>Waiting Time</h5>
        <small class="text-muted">{{ $pizza->waiting_time }} Minutes</small> <hr>
        <h5>Description</h5>
        <small class="text-muted">{{ $pizza->description }}</small> <hr>
        <h3 class="fw-bolder">Total Price</h3>
        <small class="text-success">{{ $pizza->price - $pizza->discount_price }} Kyats</small> <hr>


    </div>
</div>

@endsection
