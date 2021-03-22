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
            $this->notes = 'No binance key has been set';
            $this->status = 0;
            $this->schedule->update();
           return;
        }
        

        try {
            $api = new Binance\API($api,$secret);

            if($this->schedule->side == 'buy') {
                $ticker = $api->prices(); // Make sure you have an updated ticker object for this to work
                $balances = $api->balances($ticker);
                $available_balance = (float)$balances[config('wallet')['BTCUSDC']['currency']]['available'];

                if($available_balance < $this->schedule->amount) {
                    info('Not enough balance');
                    $this->notes = 'Balance is not enough';
                    $this->status = 0;
                    $this->schedule->update();
                    return;
                }
            }

            //if balance is enough, then continue

            $price = $api->price($this->schedule->symbol);
            $final_qty = round($this->schedule->amount / $price, 6) + 0.000001;

            if($this->schedule->side == 'buy') {
                info('triggered buy');
                info($final_qty);
                $order = $api->marketBuy($this->schedule->symbol, $final_qty);
            } else {
                info('triggered sell');
                $order = $api->marketSell($this->schedule->symbol, $final_qty);
            }

            Order::create([
                'user_id' => $this->user->id,
                'order_id' => $order['orderId'],
                'symbol' => $order['symbol'],
                'side' => $order['side'],
                'status' => $order['status'],
                "full_response" => json_encode($order),
                'schedule_id' => $this->schedule->id
            ]);

            if($this->schedule->sequence == 'hourly') {
                $this->schedule->next_schedule_at = $this->time->addHour()->startOfHour()->addMinutes($this->schedule->minutes);
            } else {
                $this->schedule->next_schedule_at = $this->time->addDay()->startOfHour()->addMinutes($this->schedule->minutes);
            }

            $this->notes = 'Schedule has been sucessfully executed';
            $this->schedule->update();

        } catch (\Exception $e) {
            info('Trading error'. $e->getMessage());
        }

        return;
    }
}
