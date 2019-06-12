<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use Illuminate\Support\Facades\Log;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shop = \ShopifyApp::shop();
        Log::info('$shop'.print_r($shop,true));
        $shop_info = $shop->api()->rest('GET',"/admin/shop.json")->body->shop;
        $user = User::firstOrCreate([
            'name' => $shop_info->name,
            'email' => $shop_info->email,
            'password' => '',
            'shop_id' => $shop->id
        ]);
    }
}
