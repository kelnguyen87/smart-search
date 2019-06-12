<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OhMyBrew\BasicShopifyAPI;

class WebhookController extends Controller
{
    public function orderFulfilled(Request $request)
    {
        Log::info('handle order fulfilled controller');
        try {
//            Log::info('request:' . print_r($request, true));
            Log::info('request header:' . print_r($request->headers->all(), true));
            $method = $request->method();
            if ($method == 'POST') {
                $data = file_get_contents('php://input');
                Log::info('data:'.print_r($data,true));
                $data = json_decode($data);
                $this->verifyRequest($data,$request->headers->get('x-shopify-hmac-sha256'));
                Log::info('data:'.print_r($data,true));
                $listTrackingCode = $this->checkTrackingCode($data);
                DB::table('report_sale')->whereIn('tracking_code', $listTrackingCode)->update(['is_purchase' => 1]);
            }
            Log::info('handle order fulfilled controller succeeded');
        } catch (\Throwable $e) {
            Log::error($e);
        }
    }

    public function checkTrackingCode($data)
    {
        $fulfilled_id = $data->properties->FulfilledId;
        $order_id = $data->properties->OrderId;
        $api = new BasicShopifyAPI(); // true sets it to private
        $api->setShop($data->shopify_domain);
        $access_token = DB::table('shops')->where('shopify_domain', $data->shopify_domain)->value('shopify_token');
        $api->setAccessToken($access_token);
        $response = $api->rest('GET', "/admin/orders/$order_id/fulfillments/$fulfilled_id.json"); // or GraphQL
        $line_items = $response->body->fulfillment->line_items;
        $listTrackingCode = [];
        foreach ($line_items as $value) {
            foreach ($value->properties as $item) {
                if ($item->name == "tracking-code") {
                    array_push($listTrackingCode, $item->value);
                }
            }
        }
        return $listTrackingCode;
    }

    public function verifyRequest($data, $hmac)
    {
        $queryString = http_build_query($data);
        Log::info('querystring:'.print_r($queryString,true));
        $calculated = hash_hmac('sha256', $queryString, env('SHOPIFY_APP_API_SECRET'));
        Log::info('$calculated:'.print_r($calculated,true));
        return $hmac === $calculated;
    }
}
