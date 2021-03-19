<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];


    protected $appends = ['price'];


    public function getPriceAttribute()
    {
        try {
            return json_decode($this->full_response)->cummulativeQuoteQty;
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
