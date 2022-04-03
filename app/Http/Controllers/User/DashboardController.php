<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $checkouts = Checkout::with('Camp')->where('user_id', Auth::id())->get();

        return view('user.dashboard', [
            'checkouts' => $checkouts
        ]);
    }
}
