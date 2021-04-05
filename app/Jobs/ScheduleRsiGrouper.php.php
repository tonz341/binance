<?php

namespace App\Jobs;

use App\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScheduleRsiGrouper implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $rsi;
    protected $interval;
    protected $period;
    protected $price;

    public function __construct($rsi, $interval, $period, $price)
    {
        $this->rsi = $rsi;
        $this->interval = $interval;
        $this->period = $period;
        $this->price = $price;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schedules =  Schedule::where('rsi_period',$this->period)
            ->where('rsi_interval',$this->interval)
            ->where('type','RSI')
            ->where('status',1)
            ->where(function($q){
                $q->where('next_schedule_at','<',now())
                    ->orWhereNull('next_schedule_at');
            })
            ->limit(20)
            ->get();

        foreach($schedules as $schedule) {
            info('RSI hit'.$schedule->id);
            TradeJobRsi::dispatch($schedule->id, $this->price, $this->rsi);
        }
    }
}
