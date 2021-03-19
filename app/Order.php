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
            $full = json_decode($this->full_response);
            return $full['origQty'];
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
