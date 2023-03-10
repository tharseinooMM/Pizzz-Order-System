<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\OrderController;

class OrderController extends Controller
{
    // order list
    public function orderList(){

        if(Session::has('ORDER_SEARCH')){
            Session::forget('ORDER_SEARCH');
        }

        $data = Order::select('orders.*','users.name as Customer_Name','pizzas.pizza_name as Pizza_Name',DB::raw('COUNT(orders.pizza_id)as count'))
                ->join('users','users.id','orders.customer_id')
                ->join('pizzas','pizzas.pizza_id','orders.pizza_id')
                ->groupBy('orders.customer_id','orders.pizza_id')
                ->paginate(5);
                // dd($data->toArray());

        return view('admin.order.list')->with(['order' => $data]);
    }

    // order search
    public function orderSearch(Request $request){
        // dd($request->all());
        $data = Order::select('orders.*','users.name as Customer_Name','pizzas.pizza_name as Pizza_Name',DB::raw('COUNT(orders.pizza_id)as count'))
        ->join('users','users.id','orders.customer_id')
        ->join('pizzas','pizzas.pizza_id','orders.pizza_id')
        ->orWhere('users.name','like','%'.$request->searchData.'%')
        ->orWhere('pizzas.pizza_name','like','%'.$request->searchData.'%')
        ->groupBy('orders.customer_id','orders.pizza_id')
        ->paginate(5);

        $data->appends($request->all());

        Session::put('ORDER_SEARCH',$data);
        return view('admin.order.list')->with(['order' => $data]);
    }

    // order list download
    public function orderDownload(){
        if(Session::has('ORDER_SEARCH')){
            $data = Order::select('orders.*','users.name as Customer_Name','pizzas.pizza_name as Pizza_Name',DB::raw('COUNT(orders.pizza_id)as count'))
            ->join('users','users.id','orders.customer_id')
            ->join('pizzas','pizzas.pizza_id','orders.pizza_id')
            ->orWhere('users.name','like','%'.Session::get('ORDER_SEARCH').'%')
            ->orWhere('pizzas.pizza_name','like','%'.Session::get('ORDER_SEARCH').'%')
            ->groupBy('orders.customer_id','orders.pizza_id')
            ->get();
        }else{
            $data = Order::select('orders.*','users.name as Customer_Name','pizzas.pizza_name as Pizza_Name',DB::raw('COUNT(orders.pizza_id)as count'))
            ->join('users','users.id','orders.customer_id')
            ->join('pizzas','pizzas.pizza_id','orders.pizza_id')
            ->groupBy('orders.customer_id','orders.pizza_id')
            ->get();
        }
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($data, [
            'order_id' => 'ID',
            'customer_id' => 'Customer Name',
            'pizza_id' => 'Pizza ID',
            'order_time' => 'Order Time',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'orderList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
