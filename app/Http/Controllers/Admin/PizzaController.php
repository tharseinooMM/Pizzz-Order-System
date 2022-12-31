<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pizza;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\PizzaController;

class PizzaController extends Controller
{
    // direct pizza page
    public function pizza(){

        if(Session::has('PIZZA_SEARCH')){
            Session::forget('PIZZA_SEARCH');
        }

        // $response = Pizza::select('pizza_name as pName','price as PP')->get();
        // dd($response->toArray());   //using max query  //max,min,avg,sum(keywords)

        $data = Pizza::paginate(4);

        if(count($data) == 0){
            $emptyStatus = 0;
        } else{
            $emptyStatus = 1;
        }

        return view('admin.pizza.list')->with(['pizza'=>$data, 'status' => $emptyStatus]);
    }

    public function createPizza(){
        $category = Category::get();
        return view('admin.pizza.create')->with(['category' => $category]);
    }

    // insert pizza
    public function insertPizza(Request $request){
        // dd($request->all());

        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
            'image' => 'required',
            'price' => 'required',
            'publish' => 'required',
            'category' => 'required',
            'discount' => 'required',
            'buyOneGetOne' => 'required',
            'waitingTime' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $file = $request->file('image');
        $fileName = uniqid().'_gg'.$file->getClientOriginalName();
        $file->move(public_path().'/upload/', $fileName);

        $data = $this->requestPizzaData($request, $fileName);
        Pizza::create($data);
        return redirect()->route('admin#pizza')->with(['createSuccess' => "Insert Pizza Success"]);
    }

    // delete Pizza
    public function deletePizza($id){
        $data = Pizza::select('image')->where('pizza_id',$id)->first();
        $fileName = $data['image'];

        Pizza::where('pizza_id',$id)->delete();   //delete form dataBase

        // project delete from upload folder
        if(File::exists(public_path().'/upload/'.$fileName)){
            File::delete(public_path().'/upload/'.$fileName);
        }

        return back()->with(['deleteSuccess' => 'Pizza deleted!']);
    }

    // Pizza Info Page
    public function pizzaInfo($id){
        $data = Pizza::where('pizza_id', $id)->first();
        return view('admin.pizza.info')->with(['pizza' => $data ]);
    }

    // Edit Pizza Page
    public function editPizza($id){
        $category = Category::get();
        $data = Pizza::select('pizzas.*', 'categories.category_id', 'categories.category_name')
                ->join('categories','pizzas.category_id','=','categories.category_id')
                ->where('pizza_id', $id)
                ->first();
        return view('admin.pizza.edit')->with(['pizza' => $data, 'category' => $category]);
    }

    // search pizza
    public function searchPizza(Request $request){
        $searchKey = $request->table_search;
        $searchData = Pizza::orwhere('pizza_name','like','%'.$searchKey.'%')
                            ->orwhere('price',$searchKey)
                            ->paginate(4);

        $searchData->appends($request->all());

        Session::put('PIZZA_SEARCH', $searchKey);

        if(count($searchData) == 0){
            $emptyStatus = 0;
        } else{
            $emptyStatus = 1;
        }

        return view('admin.pizza.list')->with(['pizza'=>$searchData, 'status' => $emptyStatus]);

    }

    // update Pizza
    public function updatePizza($id, Request $request){

        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
            'price' => 'required',
            'publish' => 'required',
            'category' => 'required',
            'discount' => 'required',
            'buyOneGetOne' => 'required',
            'waitingTime' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $updateData = $this->requestUpdatePizzaData($request);
        // dd($updateData['image']);

        if(isset($updateData['image'])){
            // get old image name
            $data = Pizza::select('image')->where('pizza_id',$id)->first();
            $fileName = $data['image'];

            // delete old image
            if(File::exists(public_path().'/upload/'.$fileName)){
                File::delete(public_path().'/upload/'.$fileName);
            }

            // get new image data
            $file = $request->file('image');
            $fileName = uniqid().'_gg'.$file->getClientOriginalName();
            $file->move(public_path().'/upload/', $fileName);

            // ပတ်လမ်းကြောင်းမပေါ်စေချင်လို့သုံးတာ
            $updateData['image'] = $fileName;
        }

            Pizza::where('pizza_id', $id)->update($updateData);
            return redirect()->route('admin#pizza')->with(['updateSuccess' => 'Pizza Updated!']);

    }

    private function requestUpdatePizzaData($request){
        $arr = [
            'pizza_name' => $request->name,
            'price' => $request->price,
            'publish_status' => $request->publish,
            'category_id' => $request->category,
            'discount_price' => $request->discount,
            'buyOne_getOne_status' => $request->buyOneGetOne,
            'waiting_time' => $request->waitingTime,
            'description' => $request->description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        if(isset($request->image)){
            $arr['image'] = $request->image;
        }
        return $arr;
    }

    // pizza list download
    public function pizzaDownload(){

        if(Session::has('PIZZA_SEARCH')){
            $pizza = Pizza::orwhere('pizza_name','like','%'.Session::get('PIZZA_SEARCH').'%')
            ->orwhere('price',Session::get('PIZZA_SEARCH'))
            ->get();
        }else {
            $pizza = Pizza::get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($pizza, [
            'pizza_id' => 'No',
            'pizza_name' => 'Name',
            'price' => 'Price',
            'publish_status' => 'Pulish or Unpublish',
            'discount_price' => 'Discount Price',
            'buyOne_getOne_status' => 'Buy1 Get 1',
            'waiting_time' => 'Waiting Time',
            'description' => 'Description',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'pizzaList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');

    }


    // insert pizza's privated class
    private function requestPizzaData($request, $fileName){
        return [
            'pizza_name' => $request->name,
            'image' => $fileName,
            'price' => $request->price,
            'publish_status' => $request->publish,
            'category_id' => $request->category,
            'discount_price' => $request->discount,
            'buyOne_getOne_status' => $request->buyOneGetOne,
            'waiting_time' => $request->waitingTime,
            'description' => $request->description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

}
