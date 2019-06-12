<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = "charges";

    protected $fillable = ['charge_id','test','status','name','type','price','capped_amount','activated_on','trial_ends_on','shop_id'];
}
