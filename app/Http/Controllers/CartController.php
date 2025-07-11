<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        if (auth()->user()->role_id <= 2 || $role?->hasPermission('view_cart')) {
            return view('organization.cart.index');
        }

    }
}
