<?php

namespace App\Console\Commands;

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

        info('running');

        if($day = $time->isWeekend()) {
            $sequence = ['daily','weekends'];
        } else {
            $sequence = ['daily','weekdays'];
        }

        $target_time = (int)$time->format('H');

        $schedules =  Schedule::where('time',$target_time)
            ->whereIn('sequence',$sequence)
            ->where(function($q){
                $q->where('next_schedule_at','<',now())
                    ->orWhereNull('next_schedule_at');
            })
            ->get();


        foreach($schedules as $schedule) {
            info($schedule->time. '-'. $schedule->sequence);
            
            $user = $schedule->user;

            $api = $user->binance_api_key ? decrypt($schedule->user->binance_api_key) : null;
            $secret = $user->binance_secret ? decrypt($schedule->user->binance_secret) : null;

            if($api && $secret) {
                $api = new Binance\API($api,$secret);
                info($api->price($schedule->symbol));
            } else {
                info ('No binance found');
            }
//
            $schedule->next_schedule_at = now()->addDay();
            $schedule->update();
        }
    }
}
