<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function index(){
        $keys = [
            'api_key' => auth()->user()->binance_api_key ? decrypt(auth()->user()->binance_api_key) : '',
            'api_secret' => auth()->user()->binance_secret ? str_repeat('*', strlen(decrypt(auth()->user()->binance_secret)) - 10) . substr(decrypt(auth()->user()->binance_secret), -10) : ''
        ];

        return view('keys', $keys);
    }


    public function set(Request $request){

        $user = auth()->user();
        $user->binance_api_key = encrypt($request->api_key);
        $user->binance_secret = encrypt($request->api_secret);
        $user->update();


        return redirect('keys');
    }
}
