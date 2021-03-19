<?php

namespace App\Http\Controllers;

use App\Order;
use App\Schedule;
use App\User;
use Binance;
use Illuminate\Http\Request;

class TradeController extends Controller
{

    protected $user;
    protected $schedule;

    public function set(){
        return view('schedule');

    }
    public function buy()
    {


    }
}
