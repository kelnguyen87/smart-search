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
        'general_suggestion_status'=>'general_suggestion_status',
        'general_categories_status'=>'general_categories_status',
        'general_articles_status'=>'general_articles_status',
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


        return view('settings.index')->with(['data'=>$configData]);
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

    /**
     *  View tab help
     * @param
     * @return view
     */
    public function getHelp()
    {
        return view('helps.index');
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
        $collections = $this->getCollections($shop,$term );
        $products = $this->getProduct($shop,$term );
        $results_count = $this->getResultsCount($shop,$term );

        return response()->json(['success' => true, 'popularSuggestions' => $popularSuggestions, 'collections' => $collections]);
    }

    /**
     *  Get  filter Suggestions
     * @param $shop,$dataPhrase
     * @return array
     */
    public function getSuggestions($shop,$dataPhrase )
    {
        $dataSearchQueries = DB::table('report_dashboard')
            ->select('phrase')
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->where('phrase', 'like',   '%' . $dataPhrase . '%')
            ->groupBy('phrase')
            ->get();
        return $dataSearchQueries;
    }

    /**
     *  Get  filter Collections
     * @param $shop,$dataPhrase
     * @return array
     */
    public function getCollections($shop,$dataPhrase )
    {
        $allCollections = $shop->api()->rest('GET', "/admin/api/2019-04/custom_collections.json")->body->custom_collections;
        $returnData = [];
        foreach ($allCollections as $value) {
            if (stripos($value->title, $dataPhrase)!== false ) {
                array_push($returnData, ['id' => $value->id, 'handle' => $value->handle, 'title' => $value->title]);
            }
        }


        return $returnData;


    }

    /**
     *  Get  filter Product
     * @param $shop,$dataPhrase
     * @return array
     */
    public function getProduct($shop,$dataPhrase )
    {
        $allProducts = $shop->api()->rest('GET', "/admin/api/2019-04/products.json?fields=id,image,title,body_html,handle,variants")->body->products;
        $returnData = [];
        $returnProduct = [];
        $returnResults = [];
        $itemCount = 0;
        foreach ($allProducts as $value) {

            if (stripos($value->title, $dataPhrase)!== false  ) {
                $itemCount++;
                $min_price = $value->variants[0]->price;
                $price = $value->variants[0]->compare_at_price;
                array_push($returnProduct, ['id' => $value->id,'image' => $value->image->src,'title' => $value->title, 'url' => '/products/'.$value->handle, 'body_html' => $value->body_html, 'min_price' => $min_price,'price' => $price]);
                array_push($returnResults, ['results_count' => $itemCount]);

            }
        }


        return $returnProduct;


    }

    /**
     *  Get  filter Product
     * @param $shop,$dataPhrase
     * @return array
     */
    public function getResultsCount($shop,$dataPhrase )
    {
        $allProducts = $shop->api()->rest('GET', "/admin/api/2019-04/products.json?fields=id,image,title,body_html,handle,variants")->body->products;
        $returnResults = [];
        $itemCount = 0;
        foreach ($allProducts as $value) {
            if (stripos($value->title, $dataPhrase)!== false  ) {
                $itemCount++;
                array_push($returnResults, ['results_count' => $itemCount]);
            }
        }


        return $returnResults;


    }

}
