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
        'general_product_reviews'=>'general_product_reviews',
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

        $json_configData =json_encode($configData);
        $shopAPI = \ShopifyApp::shop();
        $metafield = ["namespace" => "inventory", "key" => "warehouse", "value" => $json_configData, "value_type"=> "json_string"];
        $shopAPI->api()->rest('POST', '/admin/api/2019-04/metafields.json',["metafield"=>$metafield]);

        return view('results.index')->with(['data'=>$configData]);
    }

    /**
     * save form search setting
     * @param Request $request
     * @return string
     */
    public function save(Request $request)
    {
        $shop = $this->shop->shop();
        $configModel = $this->config;

        foreach (self::CONFIG_NAME as $value){

            $dataModel = $configModel->where(['name'=>$value,'shop_id'=>$shop->id])->first() ?? new Config;
            $dataFill = $request->input($value) ?? '';

            $dataModel->fill(['name'=>$value,'shop_id'=>$shop->id,'value'=>$dataFill])->save();
        }
        return back()->with('status','Your settings have been successfully saved');
    }

    public function getHelp()
    {

        return view('help');;


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getSearchFormData(Request $request)
    {
        //list variable
        $domain = $request->get('domain');
        $term = $request->get('term');
        $shopModel = config('shopify-app.shop_model');
        $shop = $shopModel::withTrashed()->firstOrCreate(['shopify_domain' => $domain]);

        $popularSuggestions = $this->getSuggestions($shop,$term );


        return response()->json(['success' => true, 'popularSuggestions' => $popularSuggestions]);
    }

    public function getSuggestions($shop,$dataPhrase )
    {
        /*$dataPhrase = 'Black';
        $allProduct = $shop->api()->rest('GET', "/admin/api/2019-04/products.json?published_status=published?term=black")->body->products;
        $returnData = [];
        foreach ($allProduct as $value) {
            if (strpos($value->title, $dataPhrase)!== false || strpos($value->body_html, $dataPhrase)!== false) {
                $returnData[] = $value->title;
            }
        }
        sort($returnData);
        return array_unique($returnData);*/

        $dataSearchQueries = DB::table('report_dashboard')
            ->select('phrase',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->where('phrase', 'like',   '%' . $dataPhrase . '%')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')
            ->get();

        return $dataSearchQueries;
    }



}
