<?php

namespace App\Jobs;

use App\Order;
use App\Schedule;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Binance;


class TradeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $schedule;
    protected $time;

    public function __construct($schedule_id, $time)
    {
        info('Initializing job bot');
        $this->schedule = Schedule::find($schedule_id);
        $this->user = $this->schedule->user;
        $this->time = $time;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if(!$this->user) {
            info('no user');
            return;
        }

        if($this->schedule->next_schedule_at >  now()) {
            info('this job is already done');
            return;
        }

        $api = $this->user->binance_api_key ? decrypt($this->user->binance_api_key) : null;
        $secret = $this->user->binance_secret ? decrypt($this->user->binance_secret) : null;

        if(!$api || !$secret) {
            info('no api or secret===' . $this->user->id);
           return;
        }

        try {
            $api = new Binance\API($api,$secret);
            $amount = $this->schedule->amount;

            info($this->schedule->side == 'buy' . '----' . $this->schedule->side);

            if($this->schedule->side == 'buy') {
                $order = $api->marketBuy($this->schedule->symbol, $amount);
            } else {
                $order = $api->marketSell($this->schedule->symbol, $amount);
            }

            Order::create([
                'user_id' => $this->user->id,
                'order_id' => $order['orderId'],
                'symbol' => $order['symbol'],
                'side' => $order['side'],
                'status' => $order['status'],
                "full_response" => json_encode($order),
            ]);

            $this->schedule->next_schedule_at = $this->time->addDay();
            $this->schedule->update();

        } catch (\Exception $e) {
            info('Trading error'. $e->getMessage());
        }

        return;
    }
}
