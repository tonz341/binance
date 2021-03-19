<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleSetRequest;
use App\Schedule;
use Binance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function index(){

        $schedules = auth()->user()->schedules;
        return view('schedule', ['schedules' => $schedules]);
    }


    public function set(Request $request){

        Schedule::create([
            'user_id' => Auth::user()->id,
            'symbol' => $request->symbol,
            'side' => $request->side,
            'amount' => $request->amount,
            'sequence' => $request->sequence,
            'time' => $request->time,
            'minutes' => $request->minutes,
            'status' => 1,
        ]);

        return redirect('schedule');
    }

    public function delete(Request $request){
        $sched = Schedule::find($request->id);
        $sched->delete();

        return redirect('schedule');
    }

    public function activate(Request $request){
        $sched = Schedule::find($request->id);
        $sched->status = 1;
        $sched->update();

        return redirect('schedule');
    }

    public function deactivate(Request $request){
        $sched = Schedule::find($request->id);
        $sched->status = 0;
        $sched->update();

        return redirect('schedule');
    }
}
