<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleSetRequest;
use App\Schedule;
use Binance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ScheduleRsiController extends Controller
{

    public function index(){
        $schedules = auth()->user()->schedules_rsi;
        return view('schedule_rsi', ['schedules' => $schedules]);
    }

    public function set(Request $request){

        $request->validate([
            'symbol' => 'required',
            'side' => 'required',
            'amount' => 'required',
            'auto_cycle' => 'required',
            'rsi' => 'required',
            'rsi_period' => 'required',
            'rsi_interval' => 'required',
            'target_sell' => 'required',
        ]);

        $t = Schedule::create([
            'user_id' => Auth::user()->id,
            'symbol' => $request->symbol,
            'side' => $request->side,
            'amount' => $request->amount,
            'sequence' => 'none',
            'time' => 0,
            'minutes' => 0,
            'status' => 1,
            'type' => 'RSI',
            'auto_cycle' => $request->auto_cycle,
            'rsi' => $request->rsi,
            'rsi_period' => $request->rsi_period,
            'rsi_interval' => $request->rsi_interval,
            'target_sell' => $request->target_sell,
        ]);

        return redirect('schedule-rsi');
    }

    public function delete(Request $request){

        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->delete();
        return redirect('schedule-rsi');
    }

    public function activate(Request $request){
        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->status = 1;
        $sched->update();

        return redirect('schedule-rsi');
    }

    public function deactivate(Request $request){
        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->status = 0;
        $sched->update();

        return redirect('schedule-rsi');
    }
}
