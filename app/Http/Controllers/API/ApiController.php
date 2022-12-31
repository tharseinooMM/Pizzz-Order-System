<?php

namespace App\Http\Controllers\API;

use Response;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ApiController;

class ApiController extends Controller
{
    // list category
    public function categoryList(){
        $category = Category::get();

        $response = [
            'status' => 200,
            'message' => 'success',
            'data' => $category,
        ];
        return Response::json($response);
    }

    // create category
    public function createCategory(Request $request){
        $data = [
            'category_name' => $request->categoryName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        Category::create($data);

        $response = [
            'status' => 200,
            'message' => 'success',
        ];

        return Response::json($response);
    }

    // details category
    public function categoryDetails($id){
        //  $id = $request->id;

        $data = Category::where('category_id',$id)->first();

        if(!empty($data)){
            return Response::json([
                'status' => 200,
                'message' => 'success',
                'data' => $data,
            ]);
        }else{
            return Response::json([
                'status' => 200,
                'message' => 'fail',
                'data' => $data,
            ]);
        }
    }

    // category delete
    public function categoryDelete($id){
        $data = Category::where('category_id', $id)->first();

        if(empty($data)){
            return Response::json([
                'status' => 200,
                'message' => "There is no data in table!",
            ]);
        }

        Category::where('category_id',$id)->delete();

        return Response::json([
            'status' => 200,
            'message' => 'delete success!',
        ]);
    }

    // category update
    public function categoryUpdate(Request $request){
        $updateData = [
            'category_id' => $request->id,
            'category_name' => $request->categoryName,
        ];

        $check = Category::where('category_id', $request->id)->first();

        if(!empty($check)){
            Category::where('category_id', $request->id)->update($updateData);
            return Response::json([
                'status' => 200,
                'message' => 'update success',
            ]);
        }
        return Response::json([
            'status' => 200,
            'message' => 'There is no data',
        ]);
    }
}
