<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PseController extends Controller
{
    public function index(){

        $stocks = [
            'ACEN',
            'APL',
            'DITO',
            'WLCON',

        ];

        $observe = [
            'ACEX',
            'PHA',
            'CNVRG',
            'LTG',
            'BSC',
            'MONDE',
            'ABS',
            'GMA7'
        ];

        $client = new Client();
        $data = [];

        $res = $client->get('https://phisix-api3.appspot.com/stocks.json');
        $all = \GuzzleHttp\json_decode($res->getBody()->getContents());

        $all_stocks = collect($all->stock);

        foreach ($all_stocks as $stock) {

            try {
//                $target  = $all_stocks->where('symbol',$stock)->first();
                $data[$stock->symbol] = [
                    'name' => $stock->symbol,
                    'price' => $stock->price->amount,
                    'change' => $stock->percent_change,
                    'volume' => $stock->volume,
                    'volume_format' => $this->numberFormat($stock->volume),
                    'hold' => in_array($stock->symbol,$stocks),
                    'observe' => in_array($stock->symbol,$observe),
                    'value_turn_over' => $this->numberFormat($stock->volume * $stock->price->amount),
                    'add_class' => $this->getStatus($stock->percent_change),
                    'link' => 'https://www.investagrams.com/Chart/PSE:'.$stock->symbol
                ];
            } catch (\Exception $e) {
                dd($data[$stock->symbol]);
//
            }
        }

        $data = collect($data);
        $psei = $data->where('name','PSEi')->first();

        $final = [
            'holds' => $data->where('hold',1),
            'observe' => $data->where('observe',1),
            'gainers' => $data->sortByDesc('change')->take(30),
            'losers' => $data->sortBy('change')->take(30),
            'volume' =>  $data->sortByDesc('volume')->take(30),
            'psei' => $psei,
            'last_updated' => Carbon::parse($all->as_of)->diffForHumans()
        ];

        return view('pse',['stocks' => $final]);
    }

    private function getStatus($val){

        if($val > 0) {
            return 'text-success';
        }

        if($val < 0 ) {
            return 'text-danger';
        }

        return 'text-info';
    }

    private function numberFormat($n){

        $n = (0+str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) return false;
        // now filter it;
        if ($n > 1000000000000) return round(($n/1000000000000), 2).'T';
        elseif ($n > 1000000000) return round(($n/1000000000), 2).'B';
        elseif ($n > 1000000) return round(($n/1000000), 2).'M';
        elseif ($n > 1000) return round(($n/1000), 2).'K';
        return number_format($n);
    }

    public function child($stock){
        return view('pse_child',['stock' => $stock]);

    }
}
