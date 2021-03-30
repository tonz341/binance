<?php

namespace App\Console\Commands;

use App\Jobs\ScheduleBtdGrouper;
use App\Jobs\TradeJob;
use App\Price;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

use App\Schedule;
use Binance;

class TradingBotBtd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trading:btd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run trading schedules on binance based on trigger percentage';

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
        $user = User::whereNotNull('binance_api_key')->whereNotNull('binance_secret')->first();
        $api = new Binance\API($user->binance_api_key,$user->binance_secret);

        $price = $api->price('BTCUSDC');

        for ($window_hour = 1 ; $window_hour < 25; $window_hour++) {
            $old_price = Price::where('created_at',now()->startOfHour()->subHour($window_hour))->first();

            if(!$old_price) {
                continue;
            }

            $percentage =  100 - (($old_price->price / $price) * 100); // get percentage difference vs price in window hour last time
            ScheduleBtdGrouper::dispatch($percentage, $window_hour);
        }


        Price::firstOrCreate([
            'symbol' => 'BTCUSDC',
            'price' => $price,
            'created_at' => now()->startOfHour(),
            'rsi_14_1d' => $this->getRsiValue('1h',14)
        ], [
            'symbol' =>'BTCUSDC',
            'created_at' => now()->startOfHour()
        ]);
    }



    public function getRsiValue($interval='1h',$period=14){

        try {
            $taapi = config('services.tp_api.key');
            $endpoint = 'rsi';

            $query = http_build_query(array(
                'secret' => $taapi,
                'exchange' => 'binance',
                'symbol' => 'BTC/USDC',
                'interval' => $interval,
                'optInTimePeriod' => $period // level
            ));

            $url = "https://api.taapi.io/{$endpoint}?{$query}";

            $client = new Client();
            $response = $client->get($url);
            $value = \GuzzleHttp\json_decode($response->getBody()->getContents())->value;
        } catch (\Exception $e) {
            info($e->getMessage());
            return 0;
        }

        return $value;
    }
}