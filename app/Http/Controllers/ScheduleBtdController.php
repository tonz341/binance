<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleSetRequest;
use App\Schedule;
use Binance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScheduleBtdController extends Controller
{

    public function index(){
        $schedules = auth()->user()->schedules_btd;
        return view('schedule_btd', ['schedules' => $schedules]);
    }
}
