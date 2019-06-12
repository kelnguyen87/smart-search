<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use OhMyBrew\ShopifyApp\ShopifyApp;

class SettingController extends Controller
{
    const CONFIG_PATH = [
        'general_title'=>'general_title',
        'general_description'=>'general_description',
        'general_button_title'=>'general_button_title',
        'added_alert_text'=>'added_alert_text',
        'continue_shop_text'=>'continue_shop_text',
        'go_cart_text'=>'go_cart_text'
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
        /*$shop = $this->shop->shop();
        $configModel = $this->config;

        $configData = [];
        foreach (self::CONFIG_PATH as $value){
            $configValue = $configModel->where(['path'=>$value,'shop_id'=>$shop->id])->first()->value ?? '';

            $configData[$value] = $configValue;
        }
        return view('config.index')->with(['data'=>$configData]);*/

        $shop = $this->shop->shop();
        $configModel = $this->config;

        $configData = [];
        foreach (self::CONFIG_PATH as $value){
            $configValue = $configModel->where(['path'=>$value,'shop_id'=>$shop->id])->first()->value ?? '';

            $configData[$value] = $configValue;
        }
        return view('results.index')->with(['data'=>$configData]);
    }

    public function save(Request $request)
    {
        $shop = $this->shop->shop();
        $configModel = $this->config;
        foreach (self::CONFIG_PATH as $value){
            $dataModel = $configModel->where(['path'=>$value,'shop_id'=>$shop->id])->first() ?? new Config;
            $dataFill = $request->input($value) ?? '';
            $dataModel->fill(['path'=>$value,'shop_id'=>$shop->id,'value'=>$dataFill])->save();
        }
        return back()->with('status','Your settings have been successfully saved');
    }

}
