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

    public function set(Request $request){

        $request->validate([
            'symbol' => 'required',
            'side' => 'required',
            'amount' => 'required',
            'sequence' => 'required',
            'trigger_percentage' => 'required',
            'window_hour' => 'required'
        ]);

        Schedule::create([
            'user_id' => Auth::user()->id,
            'symbol' => $request->symbol,
            'side' => $request->side,
            'amount' => $request->amount,
            'sequence' => $request->sequence,
            'time' => 0,
            'minutes' => 0,
            'status' => 1,
            'trigger_percentage' => $request->trigger_percentage,
            'type' => 'BTD',
            'window_hour' => $request->window_hour,
        ]);

        return redirect('schedule-btd');
    }

    public function delete(Request $request){

        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->delete();
        return redirect('schedule-btd');
    }

    public function activate(Request $request){
        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->status = 1;
        $sched->update();

        return redirect('schedule-btd');
    }

    public function deactivate(Request $request){
        $sched = Schedule::find($request->id);

        if(auth()->user()->id !== $sched->user_id) {
            return response()->json(['error' => 'Not authorized.'],403);
        }

        $sched->status = 0;
        $sched->update();

        return redirect('schedule-btd');
    }
}
