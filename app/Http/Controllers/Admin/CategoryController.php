<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Pizza;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\CategoryController;

class CategoryController extends Controller
{
    // direct home page
    // public function index(){
    //     return view('admin.home');
    // }

    // direct category page
    public function category(){

        // session remove for category search
        if(Session::has('CATEGORY_SEARCH')){
            Session::forget('CATEGORY_SEARCH');
        }
        // $response = Category::where('category_id','2')->value('category_name');
        // dd($response);   //using value query

        // $response = Category::pluck('category_name');
        // dd($response->toArray());  //using pluck query

        $data = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
                ->leftjoin('pizzas','pizzas.category_id','categories.category_id')
                ->groupBy('categories.category_id')
                ->paginate(4);
        return view('admin.category.list')->with(['category' => $data]);
    }

    // direct to create category
    public function createCategory(Request $request){

        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = [
            'category_name' => $request->name
        ];

        Category::create($data);
        return redirect()->route('admin#category')->with(['categorySuccess' => "Category Added..."]);
    }

    // direct to add category
    public function addCategory(){
        return view ('admin.category.addCategory');
    }

    // delete category
    public function deleteCategory($id){
        Category::where('category_id', $id)->delete();
        return back()->with(['deleteSuccess'=>'Category Deleted!']);
    }

    // edit category
    public function editCategory($id){
        $data = Category::where('category_id', $id)->first();
        return view('admin.category.update')->with(['category'=>$data]);
    }

    // update category
    public function updateCategory($id, Request $request){

        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $updateData = [
            'category_name' => $request->name
        ];

        Category::where('category_id', $request->id)->update($updateData);
        return redirect()->route('admin#category')->with(['updateSuccess' => 'Category Data Updated!']);
    }

    // search Data Category
    public function searchCategory(Request $request){
        // $data = Category::where('category_name', 'like', '%'. $request->searchData .'%')->paginate(4);

        $data = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
        ->leftjoin('pizzas','pizzas.category_id','categories.category_id')
        ->where('categories.category_name', 'like', '%'. $request->searchData .'%')
        ->groupBy('categories.category_id')
        ->paginate(4);

        // session put for CSV
        Session::put('CATEGORY_SEARCH', $request->searchData);

        $data->appends($request->all());
        return view('admin.category.list')->with(['category' => $data]);
    }

     // categoryItem
     public function categoryItem($id){
        $data = Pizza::select('pizzas.*','categories.category_name as categoryName')
                       ->join('categories','categories.category_id','pizzas.category_id')
                       ->where('pizzas.category_id',$id)
                       ->paginate(4);

        return view('admin.category.item')->with(['pizza' => $data]);
    }

    // category download
    public function categoryDownload(){

        if(Session::has('CATEGORY_SEARCH')){
            $category = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
            ->leftjoin('pizzas','pizzas.category_id','categories.category_id')
            ->where('categories.category_name', 'like', '%'. Session::get('CATEGORY_SEARCH').'%')
            ->groupBy('categories.category_id')
            ->get();
        }else {
            $category = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
            ->leftjoin('pizzas','pizzas.category_id','categories.category_id')
            ->groupBy('categories.category_id')
            ->get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($category, [
            'category_id' => 'စဉ်',
            'category_name' => 'အမည်',
            'count' => 'အရေအတွက်',
            'created_at' => 'စတင်သည့်ရက်',
            'updated_at' => 'အသစ်ထည့်သည့်ရက်',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'categoryList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

}
