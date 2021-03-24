<?php

namespace App\Jobs;

use App\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScheduleBtdGrouper implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $percentage;
    protected $window_hour;

    public function __construct($percentage, $window_hour)
    {
        $this->percentage = $percentage;
        $this->window_hour = $window_hour;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schedules =  Schedule::where('trigger_percentage','>',$this->percentage)
            ->where('window_hour',$this->window_hour)
            ->where(function($q){
                $q->where('next_schedule_at','<',now())
                    ->orWhereNull('next_schedule_at');
            })
            ->where('type','BTD')
            ->where('status',1)
            ->limit(20)
            ->get();

        foreach($schedules as $schedule) {
            info('triggers hit'.$schedule->id);
            TradeJob::dispatch($schedule->id, now());
        }
    }
}
