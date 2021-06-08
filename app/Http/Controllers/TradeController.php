<?php

namespace App\Http\Controllers;

use App\Jobs\ScheduleBtdGrouper;
use App\Order;
use App\Price;
use App\Schedule;
use App\User;
use Binance;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Endroid\QrCode\Writer\PngWriter;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ofcold\QrCode\Facades\QrCode;


class TradeController extends Controller
{


    protected $client;

    public function __construct()
    {
        $this->client  = new Client();
    }

    public function set(){
        return view('schedule');
    }

    private function getHoldersCount($html){

        try {
            $start = strpos($html,'id="ContentPlaceHolder1_tr_tokenHolders"');
            $holders = substr("$html", $start);

            $end = strpos($holders,'addresses');

            $holders = substr("$holders", 0 ,$end);
            $holders = substr("$holders", strpos($holders,'<div class="mr-3">') + (strlen('<div class="mr-3">') + 1));

            return (int)$holders;
        }  catch (\Exception $e) {
            return 0;
        }
    }


    private function getTokenInfo($url){

        $res = $this->client->get($url);
        $html = $res->getBody()->getContents();

        try {

            $decimal = substr($html, strpos($html,'Decimals:'));
            $start = strpos($html,'property="og:description"');
            $scrape = substr($html, $start);
            $end = strpos($scrape,'/> <meta property="og:type"');
            $scrape = substr($scrape, 0 ,$end);

            $data = collect(explode(" ", $scrape))->unique()->values();
            $decimal = (int)collect(explode("\n", substr($decimal, 0, strpos($decimal,'<hr class="hr-space">'))))[2];

            return  [
                'name' =>  $data[$data->search('Token', true) - 1],
                'code' =>  $data[$data->search('Token', true) + 1],
                'decimal' => $decimal,
                'price_usd' => (double)preg_replace('/[^\d.]/', "",$data[$data->search('price', true) + 2]),
                'total_supply' => (int)str_replace(",", "", $data[$data->search('supply', true) + 1]),
                'holders' => (int)str_replace(",", "", $data[$data->search('holders', true) + 1]),
            ];


        }  catch (\Exception $e) {
            return 0;
        }
    }

    private function client($url){

        try {
            $res = $this->client->get($url);
            $html = \GuzzleHttp\json_decode($res->getBody()->getContents());

            return (double)($html->result);
        } catch (\Exception $e) {
            return 0;
        }

    }

    public function test(Request $request)
    {

        echo '<form action="/sample" type="GET"><input type="text" placeholder="Token address" name="address" /><button type="submit">Search</button></form>';
        echo '<form action="/sample" type="GET"><input type="hidden" placeholder="Token address" name="address" value="0xe6f3ec808b86ca1f891071ac759831bd9f833c4e" /><button type="submit">BANA</button></form>';
        echo '<form action="/sample" type="GET"><input type="hidden" placeholder="Token address" name="address" value="0x2170ed0880ac9a755fd29b2688956bd959f933f8" /><button type="submit">ETC</button></form>';
        echo '<form action="/sample" type="GET"><input type="hidden" placeholder="Token address" name="address" value="0x7130d2a12b9bcbfae4f2634d864a1ee1ce3ead9c" /><button type="submit">BTC</button></form>';
        echo '<form action="/sample" type="GET"><input type="hidden" placeholder="Token address" name="address" value="0x16939ef78684453bfdfb47825f8a5f714f12623a" /><button type="submit">XTZ</button></form>';


        $test = $this->core($request);


        echo "<hr />";
        echo "<p>Name:".$test['name']."</p>";
        echo "<p>Code:".$test['code']."</p>";
        echo "<p>Decimal:".$test['decimal']."</p>";
        echo "<p>Max Supply:".$test['max_supply']."</p>";
        echo "<p>Circulation Supply:".$test['circ_supply']."</p>";
        echo "<p>Holders:".$test['holders']."</p>";
        echo "<p>Price to USD: $".$test['price_to_usd']."</p>";

        echo "<p>Burned Tokens:".$test['burned_tokens']."</p>";
        echo "<p>Burned Tokens percentage:".$test['burned_tokens_percentage']."</p>";
        echo "<p>Max Supply Word:".$test['max_supply_str']."</p>";
        echo "<p>Circulation Supply Word:".$test['circ_supply_str']."</p>";

//        echo "<p>Max Supply Amount:  <strong>".$test['max_supply_amount']." </strong></p>";
//        echo "<p>Circulation Supply Amount: <strong>".$test['circ_supply_amount']."</strong></p>";

    }


    private function core($request){

        if(!isset($request->address)) {
            $token = '0xe6f3ec808b86ca1f891071ac759831bd9f833c4e';
        } else {
            $token = $request->address;
        }

        $api = 'IVSGGH6SA13NE9PXJ7DAIBN77JBBEY9H23';
//
        $url = 'https://bscscan.com/token/'.$token.'#balances';
        $info = $this->getTokenInfo($url);

        if($info === 0) {
            return response('Token address is invalid', 400);
        }

        $test = [
            'name' => $info['name'],
            'code' => $info['code'],
            'decimal' => $decimal = $info['decimal'],
            'max_supply' => number_format($max_supply = $info['total_supply']),
            'circ_supply' => number_format($circ_supply = ($this->client('https://api.bscscan.com/api?module=stats&action=tokenCsupply&contractaddress='.$token.'&apikey='.$api) / (10 ** $decimal))),
            'free' => $max_supply - $circ_supply,
            'holders' => $info['holders'],
            'price_to_usd' => $info['price_usd'],
//            'max_supply_amount' => $max_supply * $info['price_usd'],
//            'circ_supply_amount' => $circ_supply * $info['price_usd'],
            'burned_tokens' => number_format($burned = 1000000000 - $max_supply),
            'burned_tokens_percentage' => number_format((float)($burned / 1000000000) * 100, 5, '.', '').'%',
            'max_supply_str' => $this->number_format_short($max_supply),
            'circ_supply_str' => $this->number_format_short($circ_supply),

        ];

        return $test;
    }

    public function api(Request $request){
        return response($this->core($request), 200);
    }



    private function getPriceToUsd($url){
        return $url;
    }

    private function number_format_short( $n ) {
        if ($n > 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = 'K+';
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = 'M+';
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = 'B+';
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = 'T+';
        }

        return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
    }


}
