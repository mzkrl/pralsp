<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\db;
use Illuminate\Http\Request;
use App\Models\User;
class login extends Controller
{
    //
    public function loginForm(){
        return view("user.login");
    }
    
    public function login(Request $request){
        $user=DB::table('users')
            ->where("name", $request->name)
            ->where("email", $request->email)
            ->where("password",md5($request->password))
            ->first();
        if($user){
            session(['login' => true, 'name'=>$request->name]);
            return redirect("/user");
        }else{
            return back()->with("error","login gagal");
        }
    }
    public function signup(Request $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => md5($request->password),
        ]);
        return redirect("user/login");
    }
    public function signupForm(){
        return view("user.signup");
    }
    public function logout(){
        session()->flush();
        return redirect("/user/login");
    }
    public function index(){
        $name=session("name");
        return view("user.index", compact("name"));
    }
}
