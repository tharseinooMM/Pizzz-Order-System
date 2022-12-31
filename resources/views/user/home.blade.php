@extends('user.layout.style')

@section('content')

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Pizza Order System</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('customer/assets/favicon.ico') }}" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('customer/css/styles.css') }}" rel="stylesheet" />
    <style>

    </style>
</head>

<body> --}}
    <!-- Page Content-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <div class="container px-4 px-lg-5" id="home">
        <!-- Heading Row-->
        <div class="row gx-4 gx-lg-5 align-items-center my-5">
            <div class="col-lg-7"><img class="img-fluid rounded mb-4 mb-lg-0" id="code-lab-pizza" src="https://www.pizzamarumyanmar.com/wp-content/uploads/2019/04/chigago.jpg" alt="..." /></div>
            <div class="col-lg-5">
                <h1 class="font-weight-light" id="about">CODE LAB Pizza</h1>
                <p>This is a template that is great for small businesses. It doesn't have too much fancy flare to it, but it makes a great use of the standard Bootstrap core components. Feel free to use this template for any project you want!</p>
                <a class="btn btn-primary" href="#!">Enjoy!</a>
            </div>
        </div>

        <!-- Content Row-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <div class="d-flex justify-content-around">
            <div class="col-sm-3 me-5">
                <div class="">
                    <div class="py-5 text-center">
                        <form class="d-flex m-5" method="get" action="{{ route('user#itemSearch') }}">
                            @csrf
                                <input class="form-control me-2" type="search" name="searchData" placeholder="Search" aria-label="Search">
                                <button class="btn btn-sm btn-outline-dark" type="submit">Search</button>
                        </form>

                        <div class="">
                            <a style="text-decoration:none" class="text-dark" href="{{ route('user#index') }}"><div class="m-2 p-2">All</div></a>
                           @foreach ($category as $item)
                           <a style="text-decoration:none" class="text-dark" href="{{ route('user#categorySearch',$item->category_id) }}"><div class="m-2 p-2">{{ $item->category_name }}</div></a>
                           @endforeach
                        </div>
                        <hr>

                       <form action="{{ route('user#searchPizzaItem') }}" method="get">
                        @csrf
                            <div class="text-center m-4 p-2">
                                <h3 class="mb-3">Start Date - End Date</h3>
                                    <input type="date" name="startDate" id="" class="form-control">
                                    <input type="date" name="endDate" id="" class="form-control">
                            </div>
                            <hr>

                            <div class="text-center m-4 p-2">
                                <h3 class="mb-3">Min - Max Amount</h3>

                                <input type="number" name="minPrice" id="" class="form-control" placeholder="minimum price">
                                <input type="number" name="maxPrice" id="" class="form-control" placeholder="maximun price">
                            </div>
                            <div>
                                <input type="submit" value="Search" class="btn bg-dark text-white">
                            </div>
                       </form>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                @if ($status == 1)
                <div class="row gx-4 gx-lg-5" id="pizza">
                    @foreach ($pizza as $item)
                    <div class="col-md-4 mb-5">
                       <div class="card h-100" style="width: 220px">
                           <!-- Sale badge-->

                           @if ($item->buyOne_getOne_status == 1)
                           <div class="badge bg-danger text-white position-absolute shadow" style="top: 0.5rem; right: 0.5rem">Buy 1 Get 1</div>
                           @endif
                           <!-- Product image-->
                           <img class="card-img-top" id="pizza-image" src="{{ asset('/upload/'.$item->image) }}" alt="..." />
                           <!-- Product details-->
                           <div class="card-body p-4">
                               <div class="text-center">
                                   <!-- Product name-->
                                   <h5 class="fw-bolder">{{ $item->pizza_name }}</h5>
                                   <!-- Product price-->
                                   {{-- <span class="text-muted text-decoration-line-through">$20.00</span> $18.00 --}}
                                   <span>{{ $item->price }}</span> Kyats
                               </div>
                           </div>
                           <!-- Product actions-->
                           <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                               <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="{{ route('user#pizzaDetails', $item->pizza_id) }}">More Details</a></div>
                           </div>
                       </div>
                   </div>
                    @endforeach
                </div>
                @else
                <div id="liveAlertPlaceholder" style="width: 100%; margin-top: 300px">
                <button type="button" class="btn btn-danger" id="liveAlertBtn">There is no pizza at this moment!</button>
            </div>
                     @endif
                     <span class="pagination justify-content-center mt-3">{{ $pizza->links() }}</span>
            </div>
        </div>
    </div>

    <div class="text-center d-flex justify-content-center align-items-center" id="contact">
        <div class="col-4 border shadow-sm ps-5 pt-5 pe-5 pb-2 mb-5 ">
            <h3>Contact Us</h3>

            @if(Session::has('contactSuccess'))
              <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                  {{ Session::get('contactSuccess') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

            <form class="my-4" action="{{ route('user#createContact') }}" method="post">
                @csrf
                <input type="text" name="name" value="{{ old('name') }}" class="form-control my-3" placeholder="Name">
                @if($errors->has('name'))
                  <p class="text-danger">{{ $errors->first('name') }}</p>
                  @endif
                <input type="text" name="email"  value="{{ old('email') }}"  class="form-control my-3" placeholder="Email">
                @if($errors->has('email'))
                <p class="text-danger">{{ $errors->first('email') }}</p>
                @endif
                <textarea class="form-control my-3" id="exampleFormControlTextarea1" name="message" rows="3" placeholder="Message">{{ old('message') }}</textarea>
                @if($errors->has('message'))
                <p class="text-danger">{{ $errors->first('message') }}</p>
                @endif
                <button type="submit" class="btn btn-outline-dark">Send  <i class="fas fa-arrow-right"></i></button>
            </form>
        </div>
    </div>
</body>

</html>


@endsection
