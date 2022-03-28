<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $checkouts = Checkout::with('Camp')->where('user_id', Auth::id())->get();

        // dd($checkouts);

        return view('user.dashboard', [
            'checkouts' => $checkouts
        ]);
    }
}
