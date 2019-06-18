<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use Carbon\Carbon;
use OhMyBrew\ShopifyApp\ShopifyApp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    const CONFIG_NAME = [
        'general_suggestion_price'=>'general_suggestion_price',
        'general_suggestion_derc'=>'general_suggestion_derc',

        'general_product_price'=>'general_product_price',
        'general_product_derc'=>'general_product_derc',
        'general_product_per'=>'general_product_per',
        'general_product_title'=>'general_product_title',
        'general_product_button'=>'general_product_button',
        'general_product_sorting'=>'general_product_sorting',

    ];
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var ShopifyApp
     */
    protected $shop;

    /**
     * SettingController constructor.
     * @param Config $config
     * @param ShopifyApp $shop
     */
    public function __construct(Config $config,ShopifyApp $shop)
    {

        $this->config = $config;
        $this->shop = $shop;
    }

    public function index()
    {

        $shop = $this->shop->shop();
        $configModel = $this->config;

        $configData = [];
        foreach (self::CONFIG_NAME as $value){
            $configValue = $configModel->where(['name'=>$value,'shop_id'=>$shop->id])->first()->value ?? '';

            $configData[$value] = $configValue;
        }
        return view('results.index')->with(['data'=>$configData]);
    }

    public function save(Request $request)
    {
        $shop = $this->shop->shop();
        $configModel = $this->config;


        foreach (self::CONFIG_NAME as $value){

            $dataModel = $configModel->where(['name'=>$value,'shop_id'=>$shop->id])->first() ?? new Config;
            $dataFill = $request->input($value) ?? '';
            //dd($request->input('general_product_price') );
            $dataModel->fill(['name'=>$value,'shop_id'=>$shop->id,'value'=>$dataFill])->save();
        }
        return back()->with('status','Your settings have been successfully saved');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSetting(Request $request)
    {
        $domain = $request->get('domain');
        $shopModel = config('shopify-app.shop_model');
        $configModel = $this->config;
        $shop = $shopModel::withTrashed()->firstOrCreate(['shopify_domain' => $domain]);

        /*$data = array(
            "namespace": "inventory",
                "key": "warehouse",
                "value": 25,
                "value_type": "integer"
            );


        $request = $shop->api()->rest('POST', '/admin/api/2019-04/metafields.json',$data);
        //$rawVendor = $request->body->products;
        dd($request);*/

        $configData = [];
        foreach (self::CONFIG_NAME as $value){
            $configValue = $configModel->where(['name'=>$value,'shop_id'=>$shop->id])->first()->value ?? '';
            $configData[$value] = $configValue;
        }

        return response()->json(['success' => true, 'data' => $configData]);
    }



}
