<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home',['orders' => auth()->user()->orders()->latest()->limit(20)->get() ]);
    }

    public function prices()
    {
        $symbol = 'BTCUSDC';
        $prices = Price::where('symbol',$symbol)->latest()->limit(24)->get();

        return view('price',['prices' => $prices ]);
    }
}
