<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserCheckMiddleware;
use App\Http\Middleware\AdminCheckMiddleware;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PizzaController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserControllerInner;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        if(Auth::check()){
            if(Auth::user()->role == 'admin'){
                return redirect()->route('admin#profile');
            } else if(Auth::user()->role == 'user'){
                return redirect()->route('user#index');
            }
        }
    })->name('dashboard');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => [AdminCheckMiddleware::class]], function(){
    // Route::get('/', [ AdminController::class, 'index'])->name('admin#index');
    Route::get('profile', [ AdminController::class, 'profile'])->name('admin#profile');
    Route::post('update/{id}', [ AdminController::class, 'updateProfile' ])->name('admin#updateProfile');
    Route::post('changePassword/{id}', [ AdminController::class, 'changePassword' ])->name('admin#changePassword');
    Route::get('changePassword', [ AdminController::class, 'changePasswordPage' ])->name('admin#changePasswordPage');

    Route::get('category', [ CategoryController::class, 'category'])->name('admin#category');   //list
    Route::get('addCategory', [ CategoryController::class, 'addCategory' ])->name('admin#addCategory');
    Route::post('createCategory', [ CategoryController::class, 'createCategory' ])->name('admin#createCategory');
    Route::get('deleteCategory/{id}', [ CategoryController::class, 'deleteCategory' ])->name('admin#deleteCategory');
    Route::get('editCategory/{id}', [ CategoryController::class, 'editCategory' ])->name('admin#editCategory');
    Route::post('updateCategory/{id}', [ CategoryController::class, 'updateCategory' ])->name('admin#updateCategory');
    Route::get('category/search', [ CategoryController::class, 'searchCategory' ])->name('admin#searchCategory');
    Route::get('categoryItem/{id}', [ CategoryController::class, 'categoryItem' ])->name('admin#categoryItem');
    Route::get('category/download', [ CategoryController::class, 'categoryDownload' ])->name('admin#categoryDownload');

    Route::get('pizza', [ PizzaController::class, 'pizza'])->name('admin#pizza');
    Route::get('createPizza', [ PizzaController::class, 'createPizza' ])->name('admin#createPizza');
    Route::post('insertPizza', [ PizzaController::class, 'insertPizza' ])->name('admin#insertPizza');
    Route::get('deletePizza/{id}', [ PizzaController::class, 'deletePizza' ])->name('admin#deletePizza');
    Route::get('pizzaInfo/{id}', [ PizzaController::class, 'pizzaInfo' ])->name('admin#pizzaInfo');
    Route::get('editPizza/{id}', [ PizzaController::class, 'editPizza' ])->name('admin#editPizza');
    Route::post('updatePizza/{id}', [ PizzaController::class, 'updatePizza' ])->name('admin#updatePizza');
    Route::get('Pizza/search', [ PizzaController::class, 'searchPizza' ])->name('admin#searchPizza');
    Route::get('pizza/download', [ PizzaController::class, 'pizzaDownload' ])->name('admin#pizzaDownload');

    Route::get('userList', [ UserControllerInner::class, 'userList' ])->name('admin#userList');
    Route::get('adminList', [ UserControllerInner::class, 'adminList' ])->name('admin#adminList');
    Route::get('userList/Search', [ UserControllerInner::class, 'userSearch' ])->name('admin#userSearch');
    Route::get('userList/delete/{id}', [ UserControllerInner::class, 'userListDelete' ])->name('admin#userListDelete');
    Route::get('adminList/Search', [ UserControllerInner::class, 'adminSearch' ])->name('admin#adminSearch');
    Route::get('adminList/delete/{id}', [ UserControllerInner::class, 'adminListDelete' ])->name('admin#adminListDelete');
    Route::get('adminList/Download', [ UserControllerInner::class, 'adminListDownload' ])->name('admin#adminListDownload');
    Route::get('userList/Download', [ UserControllerInner::class, 'userListDownload' ])->name('admin#userListDownload');
    Route::get('admin/edit/{id}', [ UserControllerInner::class, 'adminEdit' ])->name('admin#adminEdit');
    Route::post('update/admin/{id}', [ UserControllerInner::class, 'updateAdmin' ])->name('admin#updateAdmin');

    Route::get('contact/list', [ ContactController::class, 'contactList' ])->name('admin#contactList');
    Route::get('contact/search', [ ContactController::class, 'contactSearch' ])->name('admin#contactSearch');

    Route::get('order/list', [ OrderController::class, 'orderList' ])->name('admin#orderList');
    Route::get('order/search', [ OrderController::class, 'orderSearch' ])->name('admin#orderSearch');
    Route::get('order/download', [ OrderController::class, 'orderDownload' ])->name('admin#orderDownload');
});

Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => [ UserCheckMiddleware::class ]],function(){
    Route::get('/', [ UserController::class, 'index'])->name('user#index');
    Route::get('pizza/details/{id}', [ UserController::class, 'pizzaDetails' ])->name('user#pizzaDetails');
    Route::get('category/search/{id}', [ UserController::class, 'categorySearch' ])->name('user#categorySearch');
    Route::get('item/search', [ UserController::class, 'itemSearch' ])->name('user#itemSearch');
    Route::get('search/pizzaItem', [ UserController::class, 'searchPizzaItem' ])->name('user#searchPizzaItem');
    Route::get('order', [ UserController::class, 'order' ])->name('user#order');
    Route::get('orderConfirm', [ UserController::class, 'orderConfirm' ])->name('user#orderConfirm');

    Route::post('contact/create', [ ContactController::class, 'createContact' ])->name('user#createContact');
});

