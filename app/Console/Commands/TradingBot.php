<?php

namespace App\Console\Commands;

use App\Jobs\TradeJob;
use App\Schedule;
use Illuminate\Console\Command;
use Binance;

class TradingBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trading:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run trading schedules on binance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                $q->where(function($s_q) use ($target_time, $sequence){
                    $s_q->where('time',$target_time, $sequence)
                        ->whereIn('sequence',$sequence);
                })->orWhere('sequence','hourly');
            })
            ->where('minutes','<=', $target_min)
            ->where(function($q){
                $q->where('next_schedule_at','<',now())
                    ->orWhereNull('next_schedule_at');
            })
            ->where('status',1)
            ->limit(5)
            ->get();


        foreach($schedules as $schedule) {
            TradeJob::dispatch($schedule->id, $time);
        }
    }
}
