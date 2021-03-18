<?php

namespace App\Http\Controllers;

use App\Schedule;
use Binance;
use Illuminate\Http\Request;

class TradeController extends Controller
{

    public function set(){

        return view('schedule');

    }
    public function buy(){

        $time = now()->addHours(4);

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

            dd();

            info($schedule->time);

            $schedule->next_schedule_at = now()->addDay();
            $schedule->update();
        }


//        dd($schedules);





//
//        $api = 'uXodXm72bGkeeEPFbBvxoQ0hroVWX9UG3Kr11ijF1vV9Qn4BVvMSdpKnoNAzWGev';
//        $secret = 'a3RFd1c8u52ftLBbQPXkoNgFknxnW6jt23nttrmliqFuPwCq95DOvf49cqHxjSnP';
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
