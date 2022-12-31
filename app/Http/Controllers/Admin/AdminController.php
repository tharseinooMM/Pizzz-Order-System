<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\AdminController;

class AdminController extends Controller
{
    // direct profile page
    public function profile(){
        $id = auth()->user()->id;
        $userData = User::where('id', $id)->first();
        //dd($userData->toArray());
        return view('admin.profile.index')->with([ 'user' => $userData ]);
    }

    // update profile
    public function updateProfile($id, Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $updateData = $this->requestUserData($request);
        User::where('id', $id)->update($updateData);
        return back()->with(['updateSuccess' => 'User Information Updated!']);
    }

    // Change Password
    public function changePassword($id, Request $request){
        // dd($id);
        $validator = Validator::make($request->all(),
        [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $data = User::where('id', $id)->first();
        $hashedPassword = $data['password'];

        if (Hash::check($oldPassword, $hashedPassword)) {
            if($newPassword != $confirmPassword){
                return back()->with(['notSameErrors' => "Confirm Password Not Same! Try Again!"]);
            }else{
                if(strlen($newPassword) <= 6 || strlen($confirmPassword <= 6)){
                    return back()->with(['lengthErrors' => "Password must be greater than 6!"]);
                }else{

                    $hash = Hash::make($newPassword);    //hash password
                    User::where('id', $id)->update(['password' => $hash]);      //update password
                }
                return back()->with(['lengthSuccess' => "Password Changed!"]);
            }
        }else{
            return back()->with(['notMatchPassword' => "Password Do Not Match! Try Again!"]);
        }

    }

    // Change password page
    public function changePasswordPage(){
        return view('admin.profile.changePassword');
    }


    // update profile (function)
    private function requestUserData($request){
        return [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];
    }
}
