<?php

namespace App\Console\Commands;

use App\Price;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Binance;


class PingCurrentPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping current prices of crypto currency based on prices configuration';

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

        $currencies = config('prices');

        foreach ($currencies as $currency) {
            $price = $api->price($currency);

            Price::firstOrCreate([
                'symbol' => $currency,
                'price' => $price,
                'created_at' => now()->startOfHour(),
                'rsi_14_1d' => $this->getRsiValue('1h',14)
            ], [
                'symbol' => $currency,
                'price' => $price,
                'created_at' => now()->startOfHour()
            ]);
        }
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
