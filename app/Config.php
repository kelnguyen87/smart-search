<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config_data';
    protected $fillable = ['name','value','shop_id'];
}
