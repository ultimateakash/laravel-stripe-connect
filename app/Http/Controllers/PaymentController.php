<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe\OAuth;
use Stripe\Stripe;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
        Stripe::setApiKey(config('stripe.api_keys.secret_key'));
    }

    public function index()
    {
        $queryData = [
            'response_type' => 'code',
            'client_id' => config('stripe.client_id'),
            'scope' => 'read_write',
            'redirect_uri' => config('stripe.redirect_uri')
        ];
        $connectUri = config('stripe.authorization_uri').'?'.urldecode(http_build_query($queryData));
        return view('index', compact('connectUri'));
    }

    public function redirect(Request $request)
    {
        $token = $this->getToken($request->code);
        if(!empty($token['error'])) {
            $request->session()->flash('danger', $token['error']);
            return response()->redirectTo('/');
        }
        $connectedAccountId = $token->stripe_user_id;
        $account = $this->getAccount($connectedAccountId);
        if(!empty($account['error'])) {
            $request->session()->flash('danger', $account['error']);
            return response()->redirectTo('/');
        }
        return view('account', compact('account'));
    }

    private function getToken($code)
    {
        $token = null;
        try {
            $token = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $code
            ]);
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    private function getAccount($connectedAccountId)
    {
        $account = null;
        try {
            $account = $this->stripe->accounts->retrieve(
                $connectedAccountId,
                []
            );
        } catch (Exception $e) {
            $account['error'] = $e->getMessage();
        }
        return $account;
    }
}
