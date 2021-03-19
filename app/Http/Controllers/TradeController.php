<?php

namespace App\Http\Controllers;

use App\Order;
use App\Schedule;
use App\User;
use Binance;
use Carbon\Carbon;
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

        $time = now();

        if($day = $time->isWeekend()) {
            $sequence = ['daily','weekends'];
        } else {
            $sequence = ['daily','weekdays'];
        }

        $target_time = (int)$time->format('H');
        $target_min = (int)$time->format('i');

        $schedules =  Schedule::where(function($q) use ($target_time, $sequence) {
            $q->where('time',$target_time, $sequence)
                ->whereIn('sequence',$sequence);
        })->orWhere('sequence','daily')
            ->where('minutes','<=', $target_min)
            ->where(function($q){
                $q->where('next_schedule_at','<',now())
                    ->orWhereNull('next_schedule_at');
            })
            ->where('status',1)
            ->limit(5)
            ->get();

        dd($schedules);


    }
}
