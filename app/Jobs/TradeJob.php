<?php

namespace App\Jobs;

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
            return;
        }

        $api = $this->user->binance_api_key ? decrypt($this->user->binance_api_key) : null;
        $secret = $this->user->binance_secret ? decrypt($this->user->binance_secret) : null;

        if(!$api || !$secret) {
           return;
        }

        try {
            $api = new Binance\API($api,$secret);
            info($api->price($this->schedule->symbol));
        } catch (\Exception $e) {
            info('Trading error'. $e->getMessage());
        }


        $this->schedule->next_schedule_at = $this->time->addDay();
        $this->schedule->update();
//
//
//        $api = new Binance\API($api,$secret);
//
//        dd($api);
//
//
//
////        $price = $api->price("XRPUSDC");
//
////        dd(40 / $price);
////        echo "Price of BNB: {$price} BTC.".PHP_EOL;
//
////        dd('hehe');
//
////        $ticker = $api->prices();
//
////        dd($ticker);
//
////        dd($price = $api->price("BTCUSDC"));
//
////        $depth = $api->depth("ETHBTC");
//
////        dd($depth);
//
//
////        $price = $api->price("BTCUSDC");
//
////        dd($price);
//
//        $quantity = 40;
//        $order = $api->marketSell("XRPUSDC", $quantity);
//
//        dd($order);
//
//
////        $order = $api->marketBuy("BTCUSDC", $quantity);
//
//
////        $quantity = 1;
////        $price = 50;
////
////        $order = $api->buy("BTCUSDC", $quantity, $price);
//
//        dd($order);
//
////        $ticker = $api->prices();
//
//
////        $balances = $api->balances($ticker);
//
////        dd($balances);
//
//
////        $price = $api->price("BTCUSDC");
////        $price = $api->price("USDCUSDT");
//
//        $price = $api->price("ETHBTC");
//
//        dd($price);

    }
}
