<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\UserControllerInner;

class UserControllerInner extends Controller
{
    // user list page
    public function userList(){

        if(Session::has('USER_LIST')){
            Session::forget('USER_LIST');
        }
        // $respone = User::find(1);
        // dd($respone->toArray());   //using find query

        $userData = User::where('role','=','user')->paginate(5);
        // dd($userData->toArray());
        return view('admin.user.userList')->with(['user' => $userData]);
    }

    // admin list page
    public function adminList(){

        if(Session::has('ADMIN_LIST')){
            Session::forget('ADMIN_LIST');
        }

        $adminData = User::where('role','=','admin')->paginate(3);
        return view('admin.user.adminList')->with(['admin' => $adminData]);
    }

    // user data search
    public function userSearch(Request $request){
        $key = $request->searchData;
        $searchData = User::where('role','=','user')
                        ->where(function ($query) use($key) {
                            $query->orwhere('name','like','%'.$key.'%')
                            ->orwhere('email','like','%'.$key.'%')
                            ->orwhere('phone','like','%'.$key.'%')
                            ->orwhere('address','like','%'.$key.'%');
                        })


                         ->paginate(5);

                            // dd($searchData->toArray());
        $searchData->appends($request->all());

        Session::put('USER_LIST',$request->searchData);
        return view('admin.user.userList')->with(['user' => $searchData]);
    }

    // delete user list
    public function userListDelete($id){
        User::where('id',$id)->delete();
        return back()->with(['userListDelete' => 'User Deleted!']);
    }

    // search admin list
    public function adminSearch(Request $request){
        $key = $request->searchData;

        $searchData = User::where('role','=','admin')
            ->where(function ($query) use($key) {
            $query->orwhere('name','like','%'.$key.'%')
                  ->orwhere('email','like','%'.$key.'%')
                  ->orwhere('phone','like','%'.$key.'%')
                  ->orwhere('address','like','%'.$key.'%');
    })

                  ->paginate(3);
    $searchData->appends($request->all());

    Session::put('ADMIN_LIST', $request->searchData);

    return view('admin.user.adminList')->with(['admin' => $searchData]);

    }

    // delete admin list
    public function adminListDelete($id){
        User::where('id',$id)->delete();
        return back()->with(['adminListDelete' => 'Admin Deleted!']);
    }

    // edit admin
    public function adminEdit($id){
        $data = User::where('id', $id)->first();
        // dd($data->toArray());
        return view('admin.user.adminEdit')->with(['data'=>$data]);
    }

    // update admin
    public function updateAdmin($id,Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'role'  => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $updateData = $this->requestAdminData($request);
        User::where('id', $id)->update($updateData);
        return back()->with(['updateSuccess' => 'Admin Info Updated!']);
    }

    private function requestAdminData($request){
        return [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ];
    }

    //download admin list
    public function adminListDownload(){
        if(Session::has('ADMIN_LIST')){

            // $key = $request->searchData;
            $adminDownload = User::where('role','admin')
                        ->where(function ($query){
                         $query->orwhere('name','like','%'.Session::get('ADMIN_LIST').'%')
                        ->orwhere('email','like','%'.Session::get('ADMIN_LIST').'%')
                        ->orwhere('phone','like','%'.Session::get('ADMIN_LIST').'%')
                        ->orwhere('address','like','%'.Session::get('ADMIN_LIST').'%');
            })->get();
        }else{
            $adminDownload = User::where('role','=','admin')->get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($adminDownload, [
            'id' => 'No',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'role' => 'Role',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'adminList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    // download user list
    public function userListDownload(){
        if(Session::has('USER_LIST')){
            $searchData = User::where('role','=','user')
                ->where(function ($query){
                $query->orwhere('name','like','%'.Session::get('USER_LIST').'%')
                ->orwhere('email','like','%'.Session::get('USER_LIST').'%')
                ->orwhere('phone','like','%'.Session::get('USER_LIST').'%')
                ->orwhere('address','like','%'.Session::get('USER_LIST').'%');
        })->get();
        }else{
            $searchData = User::where('role','=','user')->get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($searchData, [
            'id' => 'No',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'role' => 'Role',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'userList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
