<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Pizza;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $data = Pizza::where('publish_status',1)->paginate(6);
        $status = count($data) == 0 ? 0 : 1;
        $category= Category::get();
        return view('user.home')->with(['pizza' => $data, 'category' => $category, 'status' => $status]);
    }

    // pizza Details
    public function pizzaDetails($id){
        $data = Pizza::where('pizza_id',$id)->first();
        Session::put('PIZZA_INFO',$data);
        return view('user.detail')->with(['pizza' => $data]);
    }

    // category search
    public function categorySearch($id){
        $data = Pizza::where('category_id',$id)->paginate(6);
        $status = count($data) == 0 ? 0 : 1;
        $category= Category::get();
        return view('user.home')->with(['pizza'=>$data, 'category' => $category, 'status' => $status]);
    }

    // item search
    public function itemSearch(Request $request){
        $data = Pizza::where('pizza_name','like','%'.$request->searchData.'%')->paginate(6);
        $status = count($data) == 0 ? 0 : 1;
        $category= Category::get();
        return view('user.home')->with(['pizza'=>$data, 'category' => $category, 'status' => $status]);
    }

    // search pizza item
    public function searchPizzaItem(Request $request){
        $min = $request->minPrice;
        $max = $request->maxPrice;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $query = Pizza::select('*');

        if(!is_null($startDate) && is_null($endDate)){
                $query = $query->whereDate('created_at','>=',$startDate);
        }else if(is_null($startDate) && !is_null($endDate)){
            $query = $query->whereDate('created_at','<=',$endDate);
        }else if(!is_null($startDate) && !is_null($endDate)){
            $query = $query->whereDate('created_at','>=',$startDate)
                           ->whereDate('created_at','<=',$endDate);
        }

        if(!is_null($min) && is_null($max)){
                $query = $query->where('price','>=',$min);
        }else if(is_null($min) && !is_null($max)){
            $query = $query->where('price','<=',$max);
        }else if(!is_null($min) && !is_null($max)){
            $query = $query->where('price','>=',$min)
                           ->where('price','<=',$max);
        }

        $query = $query->paginate(6);
        $query->appends($request->all());

        $status = count($query) == 0 ? 0 : 1;
        $category= Category::get();
        return view('user.home')->with(['pizza'=>$query, 'category' => $category, 'status' => $status]);
    }

    // order page
    public function order(){
        $pizzaInfo = Session::get('PIZZA_INFO');
        return view('user.order')->with(['pizza'=>$pizzaInfo]);
    }

    // order confirm
    public function orderConfirm(Request $request){
        $pizzaInfo = Session::get('PIZZA_INFO');
        $userId = auth()->user()->id;
        $count = $request->pizzaCount;

        $validator = Validator::make($request->all(),
        [
            'pizzaCount' => 'required',
            'paymentType' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $orderData = $this->requestOrderData($pizzaInfo,$userId,$request);

        for($i=0; $i<$count; $i++){
            Order::create($orderData);
        }

        $watingTime = $pizzaInfo['waiting_time'] * $count;
        return back()->with(['totalTime' => $watingTime]);
    }

    private function requestOrderData($pizzaInfo,$userId,$request){
        return [
            'customer_id' => $userId,
            'pizza_id' => $pizzaInfo['pizza_id'],
            'carrier_id' => 0,
            'payment_status' => $request->paymentType,
            'order_time' => Carbon::now(),
        ];
    }
}
