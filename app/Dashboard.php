<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $table = 'report_dashboard';

    protected $fillable = ['phrase','count','result','shop_id'];
}
