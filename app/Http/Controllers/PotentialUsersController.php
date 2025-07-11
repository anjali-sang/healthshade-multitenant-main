<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PotentialUsersController extends Controller
{
    public function index(){
        if(auth()->user()->role_id != 1){
            return redirect('/dashboard')->with('error', 'You are not authorized to access this module.');
        }
        return view('admin.users.potential-users');
    }
}
