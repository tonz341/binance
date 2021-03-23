<?php

return [
    'BTCUSDC' => [
        'board_lot' => 6, //6 decimal round
        'buy_currency' => 'USDC', // wallet check for buying
        'sell_currency' => 'BTC', // wallet check for selling
        'add_on_filler' => 0.000001, // filler for failed boardlot on buying
        'quantity_formula' => 'divider'
    ],

    'XRPBTC' => [
        'board_lot' => 6, //6 decimal round
        'buy_currency' => 'BTC', // wallet check for buying
        'sell_currency' => 'XRP', // wallet check for selling
        'add_on_filler' => 0, // filler for failed boardlot
        'quantity_formula' => 'multiplier'
    ],
];
