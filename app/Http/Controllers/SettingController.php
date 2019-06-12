<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use OhMyBrew\ShopifyApp\ShopifyApp;

class SettingController extends Controller
{
    const CONFIG_NAME = [
        
        'general_product_price'=>'general_product_price',
        'general_product_compare'=>'general_product_compare',
        'general_product_sku'=>'general_product_sku',

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

}
