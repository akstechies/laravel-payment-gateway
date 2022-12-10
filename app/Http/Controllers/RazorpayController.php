<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function razorpay()
    {
        return view('razorpay');
    }

    public function payment(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount'=>$payment['amount']]);
            } catch (\Exception $e) {
                Session::put('error', $e->getMessage());
                return redirect()->back()->with(["msg" =>  "Unable to accept payment at this time"]);
            }
        }

        Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        return redirect()->back()->with(["msg" =>  "Payment successful!!"]);
    }
}
