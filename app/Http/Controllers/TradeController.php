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
use Endroid\QrCode\Writer\PngWriter;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ofcold\QrCode\Facades\QrCode;


class TradeController extends Controller
{

    protected $user;
    protected $schedule;

    public function set(){
        return view('schedule');

    }
    public function test()
    {

//        ContentPlaceHolder1_hdnTotalSupply supply
//        ContentPlaceHolder1_hdnSymbol symbol
//        sparkholderscontainer holders

        
        
        $client  = new Client();

        $res = $client->get('https://bscscan.com/token/0xe6f3ec808b86ca1f891071ac759831bd9f833c4e');

        $html = $res->getBody()->getContents();

        dd($html);


//        $inputs = $document->getElementById("ContentPlaceHolder1_hdnTotalSupply");


//        dd($inputs);



        $product = [
            'name' => 'test_product',
            'wallet_address' => 'tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy',
            'price' => 5,
            'symbol' => 'xtz',
        ];

        if($product['symbol'] == 'xtz') {

            $user = User::whereNotNull('binance_api_key')->whereNotNull('binance_secret')->first();
            $api = $user->binance_api_key ? decrypt($user->binance_api_key) : null;
            $secret = $user->binance_secret ? decrypt($user->binance_secret) : null;

            $api = new Binance\API($api,$secret);
            $product['current_price_on_usd'] = $product['price'] * (double)$api->price('XTZUSDT');

        } else {
            $product['current_price_on_usd'] = $product['price'];
        }


        echo "Name:". $product['name'] . "<br>" . "Price:". $product['price'] . $product['symbol'].  "<br>" .  "USD price:". $product['current_price_on_usd'] ."<hr>";

        $string = 'tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy';
        $tes  = QrCode::encoding('UTF-8')->generate($string);
        echo '<br> <br> <br> <br> This is tezos only address (direct) <br>' . $tes . '<br>';
        echo $string . "<hr>";

        $string = 'tezos:tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy';
        $tes  = QrCode::encoding('UTF-8')->generate($string);
        echo '<br> <br> <br> <br> This is tezos only address <br>' . $tes . '<br>';
        echo $string . "<hr>";

        $string = 'tezos:tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy?amount='.$product['price'];
        $tes  = QrCode::encoding('UTF-8')->generate($string);
        echo '<br> <br> <br> <br> This is tezos with amount <br>' . $tes . '<br>';
        echo $string . "<hr>";


        $string = 'bitcoin:19HSBchayeEiaNdHJWqTycbUSLPuunhqUM?amount='.$product['price'];
        $tes  = QrCode::encoding('UTF-8')->generate($string);

        echo '<br> <br> <br> <br> This is bitcoin with amount <br>' . $tes . '<br>';
        echo $string . "<hr>";

        $string = '19HSBchayeEiaNdHJWqTycbUSLPuunhqUM';
        $tes  = QrCode::encoding('UTF-8')->generate($string);

        echo '<br> <br> <br> <br> This is bitcoin only address (direct) <br>' . $tes . '<br>';
        echo $string . "<hr>";


//        dd('done');

//        $tes  = QrCode::BTC('tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy', 0.334);



//        echo '<center>' . $tes . '</center>';

//        dd('done');



//        Cache::store('file')->put('BTCUSDC',57243.55); // 10 Minutes
//        Cache::store('file')->put('RSI_1D_14',35.88); // 10 Minutes
//
//        dd( $value = Cache::get('BTCUSDC'));
//
////        dd(0.00229100 - 0.00000229);
//        dd((0.00229100 - (0.00229100 * 0.001)) * 100000);
//
//        dd(0.00229100 -  (0.00229100 * 0.0001));
//
//        dd(100 - ( (0.00000229 / 0.00229100) * 100));
//
//        dd(0.00229100 - 0.00000229);
//
//        dd( (0.00229100 * 0.009));

//        dd((float)0.00228921 - 0.002291);

//        $product = [
//            'name' => 'test_product',
//            'wallet_address' => 'tz1YdUMFfwMbr6n91kyWASe9DyJA1LuhtrKy',
//            'price' => 5,
//            'symbol' => 'xtz',
//        ];
//



//        dd($product);
//
//
//        $ticker = $api->prices(); // Make sure you have an updated ticker object for this to work
//        $balances = $api->balances($ticker);
//
//        $wallet_configuration = config('wallet')['BTCUSDC']; // bring up wallet configuration
//
//        $available_balance = (float)$balances[$wallet_configuration['sell_currency']]['available'];
//
//
//        dd($available_balance);


    }


}
