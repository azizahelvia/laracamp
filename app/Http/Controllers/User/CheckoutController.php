<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\Checkout;
use App\Http\Requests\User\Checkout\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkout\AfterCheckout;
use Auth;
use Exception;
use Midtrans;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Midtrans\Config::$serverKey     = env('MIDTRANS_SERVERKEY');
        Midtrans\Config::$isProduction  = env('MIDTRANS_IS_PRODUCTION');
        Midtrans\Config::$isSanitized   = env('MIDTRANS_IS_SANITITZED');
        Midtrans\Config::$is3ds         = env('MIDTRANS_IS_3DS');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Camp $camp)
    {
        if ($camp->isRegistered) {
            $request->session()->flash('error', "You already registered on {$camp->title} camp.");
            return redirect(route('user.dashboard'));
        }

        return view('checkout.create', [
            'camp'  => $camp
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Camp $camp)
    {
        // return $request->all();
        // Mapping request data
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;

        // Update user data
        $user = Auth::user();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->occupation = $data['occupation'];
        $user->save();

        // Create chekcout
        $checkout = Checkout::create($data);
        $this->getSnapRedirect($checkout);

        // Sending email
        Mail::to(Auth::user()->email)->send(new AfterCheckout($checkout));

        return redirect(route('checkout.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        //
    }

    public function success()
    {
        return view('checkout.success');
        
    }

    /**
     * Midtrans Handler
     */
    public function getSnapRedirect(Checkout $checkout)
    {
        $orderId = $checkout->id.'-'.Str::random(5);
        $price = $checkout->Camp->price * 1000;

        $checkout->midtrans_booking_code = $orderId;

        $transaction_details = [
            'order_id'      => $orderId,
            'gross_amount'  => $price,
        ];

        $item_details[] = [
            'id'        => $orderId,
            'price'     => $price,
            'quantity'  => 1,
            'name'      => "Payment for {$checkout->Camp->title} Camp",
        ];

        $user_data = [
            'first_name'    => $checkout->User->name,
            'last_name'     => "",
            'address'       => $checkout->User->address,
            'city'          => "",
            'postal_code'   => "",
            'phone'         => $checkout->User->phone_number,
            'country_code'  => "IDN",
        ];

        $customer_details = [
            'first_name'        => $checkout->User->name,
            'last_name'         => "",
            'email'             => $checkout->Camp->email,
            'phone'             => $checkout->Camp->phone_number,
            'billing_address'   => $user_data,
            'shipping_address'  => $user_data,
        ];

        $midtrans_params = [
            'transaction_details'   => $transaction_details,
            'customer_details'      => $customer_details,
            'item_details'          => $item_details,
        ];

        try {
            // Get snap payment page URL
            $paymenturl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $checkout->midtrans_url = $paymenturl;
            $checkout->save();

            return $paymenturl;
        } catch (Exception $e) {
            return false;
        }
    }
}
