<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use OhMyBrew\ShopifyApp\ShopifyApp;
use App\Dashboard;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
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
    public function __construct(Dashboard $config,ShopifyApp $shop)
    {

        $this->config = $config;
        $this->shop = $shop;
    }

    public function index()
    {
        return view('report.index');
    }

    /**
     * get phrase text total
     * @param Request $request
     * @return json
     */
    public function getProductChartData(Request $request)
    {
        $shop = \ShopifyApp::shop();

        $viewDashboard = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')
            ->get();


        $productAmountData = [];
        $total = 0;
        $autoChart= 0;
        $valueTotal = 0;
        $totalDashboard = count($viewDashboard);
        foreach ($viewDashboard as $value) {
            $total += $value->total;

        }

        foreach ($viewDashboard as $value) {
            $autoChart ++;
            if($autoChart<=9){
                $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $valuePercentages = round(($value->total/$total)*100, 2);
                array_push($productAmountData, ['value' => $valuePercentages, 'color' => $color, 'highlight' => $color, 'label' =>$value->phrase]);
            }else{
                $valueTotal += $value->total;
                if($autoChart===$totalDashboard) {

                    $valueOtherPercentages = round(($valueTotal/$total)*100, 2);
                    $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                    array_push($productAmountData, ['value' => $valueOtherPercentages, 'color' => $color, 'highlight' => $color, 'label' => 'Other Phrase']);
                }
            }


        };

        return response()->json(['success' => true,
            'product_amount' => $productAmountData
        ]);
    }

    /**
     * API to edit liquid
     * @param
     * @return json
     */
    public function  themeAssets(){
        $shop = \ShopifyApp::shop();


        /*$templatesIndexValue = $shop->api()->rest('GET', '/admin/api/2019-04/themes/74304454730/assets.json?asset[key]=layout/theme.liquid')->body->asset->value;

        $AssetApi = [ "key" => "layout/theme.liquid", "value" => "$templatesIndexValue <p>hoten: luannt</p>"];

        $allAssets = $shop->api()->rest('PUT', '/admin/api/2019-04/themes/74304454730/assets.json',["asset"=>$AssetApi]);*/


    }

    /**
     * get phrase text total
     * @param Request $request
     * @return view
     */
    public function getDashboard()
    {
        $shop = \ShopifyApp::shop();
        $configModel = $this->config;
        $this->themeAssets();

        $dataSearchQueries = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')

            ->get();

        $dataSearchNoResult = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', ' ')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')

            ->get();

        return view('index')->with(['dataSearchQueries'=>$dataSearchQueries,'dataSearchNoResult'=>$dataSearchNoResult]);;


    }

    /**
     * add request for table report_dashboard
     * @param Request $request
     * @return json
     */
    public function addGetSetting(Request $request)
    {

        $domain = $request->get('domain');
        $dataPhrase = $request->get('phrase');
        $dataResults = $request->get('results');
        $shopModel = config('shopify-app.shop_model');
        $shop = $shopModel::withTrashed()->firstOrCreate(['shopify_domain' => $domain]);

        $insertData = [];
        $currentDate = Carbon::now()->toDateString();


        if($dataResults==null){
            array_push($insertData,['phrase'=>$dataPhrase,'shop_id'=>$shop->id,'created_at'=>$currentDate]);
        }else{
            array_push($insertData,['phrase'=>$dataPhrase,'result'=>$dataResults,'shop_id'=>$shop->id,'created_at'=>$currentDate]);
        }


        DB::table('report_dashboard')->insert($insertData);

        return ['insertData'=>$insertData];
    }

}
