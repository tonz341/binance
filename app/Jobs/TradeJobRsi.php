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


class TradeJobRsi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $schedule;
    protected $price;
    protected $rsi;

    public function __construct($schedule_id, $price, $rsi)
    {
        info('Initializing job bot - RSI');
        $this->schedule = Schedule::find($schedule_id);
        $this->user = $this->schedule->user;
        $this->price = $price;
        $this->rsi = $rsi;
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

        $api = $this->user->binance_api_key ? decrypt($this->user->binance_api_key) : null;
        $secret = $this->user->binance_secret ? decrypt($this->user->binance_secret) : null;

        if(!$api || !$secret) {
            info('no api or secret===' . $this->user->id);
            $this->notes = 'No binance key has been set';
            $this->schedule->status = 0;
            $this->schedule->update();
           return;
        }
        

        try {
            $api = new Binance\API($api,$secret);
            $ticker = $api->prices(); // Make sure you have an updated ticker object for this to work
            $balances = $api->balances($ticker);

            $wallet_configuration = config('wallet')[$this->schedule->symbol]; // bring up wallet configuration

            if($this->schedule->side == 'buy') {
                $available_balance = (float)$balances[$wallet_configuration['buy_currency']]['available'];
                if($available_balance < $this->schedule->amount) {
                    info('Not enough balance');
                    $this->schedule->notes = 'Balance is not enough';
                    $this->schedule->status = 0;
                    $this->schedule->update();
                    return;
                }
            } else {
                $available_balance = (float)$balances[$wallet_configuration['sell_currency']]['available'];
                if($available_balance < ($this->schedule->uncommitted_shares / 100000)) {
                    info('Not enough balance');
                    $this->schedule->notes = 'Balance is not enough';
                    $this->schedule->status = 0;
                    $this->schedule->update();
                    return;
                }
            }

            //if balance is enough, then continue
            $price = $api->price($this->schedule->symbol);
            $final_qty = $this->getFinalQty($wallet_configuration, $price);

            if($this->schedule->side == 'buy') {

                if($this->rsi > $this->schedule->rsi) {
//                    dont do anything just add cooldown
                    $this->schedule->next_schedule_at = now()->addMinutes(5);
                    $this->schedule->update();
                    return;
                }

                $order = $api->marketBuy($this->schedule->symbol, $final_qty);

                $this->schedule->average_price = $price;


                try {
                    $this->schedule->uncommitted_shares =  (($final_qty - ($final_qty * 0.001)) * 100000 ); // minus commision fee 0.001%
                } catch (\Exception $e) {
                    info('RSI error buy - ' .$e->getMessage());
                }

                if($this->schedule->auto_cyle) {
                    $this->schedule->side = 'sell';
                } else {
                    $this->schedule->status = 0;
                }

            } else {

                $percentage =  100 - (($this->schedule->average_price / $price) * 100); // get percentage difference vs price in window hour last time
                if($percentage < $this->schedule->target_sell) {
//                    dont do anything
                    $this->schedule->next_schedule_at = now()->addMinutes(5);
                    $this->schedule->update();
                    return;
                }

                $order = $api->marketSell($this->schedule->symbol, ($this->schedule->uncomitted_shares / 100000));

                if($this->schedule->auto_cyle) { // RESET schedule
                    $this->schedule->side = 'buy';
                    $this->schedule->rsi_cycle = $this->schedule->rsi_cycle + 1;
                    $this->schedule->average_price = 0;
                    $this->schedule->uncommitted_shares = 0;
                } else {
                    $this->schedule->status = 0;
                }
            }

            Order::create([
                'user_id' => $this->user->id,
                'order_id' => $order['orderId'],
                'symbol' => $order['symbol'],
                'side' => $order['side'],
                'status' => $order['status'],
                "full_response" => json_encode($order),
                'schedule_id' => $this->schedule->id,
                'type' => $this->schedule->type,
                'btc_price' => $price
            ]);

            $this->schedule->next_schedule_at = now()->addMinutes(5);
            $this->schedule->notes = 'Schedule RSI has been successfully executed';

            $this->schedule->update();

        } catch (\Exception $e) {
            $this->schedule->status = 0;
            $this->schedule->notes = $e->getMessage();
            $this->schedule->update();
        }

        return;
    }

    private function getFinalQty($wallet_configuration, $price){

        $add_filler = $this->schedule->side == 'buy' ?  @$wallet_configuration['add_on_filler'] : 0;

        if($wallet_configuration['quantity_formula'] == 'divider') {
            return round($this->schedule->amount / $price,  @$wallet_configuration['board_lot']) + $add_filler;
        }

        if($wallet_configuration['quantity_formula'] == 'multiplier') {
            return $this->schedule->amount;
        }

        return 0;
//

    }
}
