<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\API\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group(['middleware'=>'auth:sanctum'],function(){
//     Route::get('user', function(){
//         return User::all();
//     });
// });

Route::post('register', [ AuthController::class, 'register' ]);
Route::post('login', [ AuthController::class, 'login' ]);

Route::get('category/list',function(){
    $category = Category::get();

    $response = [
        'status' => "Success",
        'data' => $category,
    ];
    return Response::json($response);
});

Route::group(['prefix' => 'category' ,'namespace' => 'API','middleware'=>'auth:sanctum'],function(){
    Route::get('list', [ ApiController::class, 'categoryList' ]);     //list
    Route::post('create', [ ApiController::class, 'createCategory' ]);     //create
    // Route::post('details', [ ApiController::class, 'categoryDetails' ]);     //details post methodနဲ့ဖမ်းပြီး data ယူတဲ့နည်း Requestထဲကယူတာ
    Route::get('details/{id}', [ ApiController::class, 'categoryDetails' ]);
    Route::get('delete/{id}', [ ApiController::class, 'categoryDelete' ]);
    Route::post('update', [ ApiController::class, 'categoryUpdate' ]);
});

Route::group([ 'middleware' => 'auth:sanctum' ],function(){
    Route::get('logout', [ AuthController::class, 'logout' ]);
});
