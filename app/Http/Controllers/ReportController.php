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

    public function getProductChartData(Request $request)
    {
        $shop = \ShopifyApp::shop();


        $viewDashboard = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();


        $productAmountData = [];

        $total = 0;
        foreach ($viewDashboard as $value) {
            $total += $value->total;

        }

        foreach ($viewDashboard as $value) {
            $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $valuePercentages = round(($value->total/$total)*100, 2);
            array_push($productAmountData, ['value' => $valuePercentages, 'color' => $color, 'highlight' => $color, 'label' =>$value->phrase]);
        };

        return response()->json(['success' => true,
            'product_amount' => $productAmountData
        ]);
    }
    public function getDashboard()
    {
        $shop = \ShopifyApp::shop();
        $configModel = $this->config;

        $dataSearchQueries = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', 'yes')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();


        $dataSearchNoResult = DB::table('report_dashboard')
            ->select('phrase','result',DB::raw('count(phrase) as total'))
            ->where('shop_id', $shop->id)
            ->where('result', ' ')
            ->groupBy('phrase')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();


        return view('welcome')->with(['dataSearchQueries'=>$dataSearchQueries,'dataSearchNoResult'=>$dataSearchNoResult]);;


    }

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

    public function getProductName()
    {
        $shop = \ShopifyApp::shop();
        $metafield = ["name" => "inventory", "key" => "warehouse", "value" => "", "value_type"=> "json_string"];
        $currency = $shop->api()->rest('POST', '/admin/api/2019-04/metafields.json',["metafield"=>$metafield]);

        $allInternalProduct = $this->product->all();
        $internalProducts = [];
        foreach ($allInternalProduct as $value) {
            $internalProducts[$value->id] = $value->variant_id;
        }
        $returnData = [];
        foreach ($allProduct as $value) {
            foreach ($value->variants as $item) {
                if (in_array($item->id, $internalProducts)) {
                    $returnData[array_search($item->id, $internalProducts)] = "$value->title - $item->title";
                }
            }
        }
        return $returnData;
    }


    public function getGeneralData(Request $request)
    {
        $shop = \ShopifyApp::shop();
        $currency = $shop->api()->rest('POST', '/admin/api/2019-04/metafields.json')->body->shop->currency;
		
        $startDay = Carbon::parse($request->get('start'))->toDateTimeString();
        Log::info('$startDay: '.$startDay);
        $endDay = Carbon::parse($request->get('end'))->addDay()->toDateTimeString();
        Log::info('$endDay: '.$endDay);
        $view = DB::table('report_view')
            ->where('shop_id', $shop->id)
            ->where('created_at', '>=', $startDay)
            ->where('created_at', '<', $endDay)
            ->count();

        $addedToCart = DB::table('report_sale')
            ->where('shop_id', $shop->id)
            ->where('created_at', '>=', $startDay)
            ->where('created_at', '<', $endDay)
            ->count();

        $revenues = DB::table('report_sale')
            ->select(['tracking_code', 'amount'])
            ->distinct('tracking_code')
            ->where('shop_id',$shop->id)
            ->where('created_at', '>=', $startDay)
            ->where('created_at', '<', $endDay)
            ->where('is_purchase', 1)
            ->get();


        $totalRevenue = 0;
        $numberPurchase = 0;
        $conversion = 0;
        foreach ($revenues as $value) {
            $totalRevenue += $value->amount;
            $numberPurchase++;
        }
        if ($addedToCart != 0) $conversion = ($numberPurchase / $addedToCart) * 100;
        $highestOffer = $this->getViewByOffer($startDay, $endDay, 'desc');
        $lowestOffer = $this->getViewByOffer($startDay, $endDay, 'asc');
        $listAddedCart = $this->getListAddedCart($startDay, $endDay);
        $highestConversion = $this->getViewByConversion($startDay, $endDay, 'asc', $listAddedCart, $currency);
        $lowestConversion = $this->getViewByConversion($startDay, $endDay, 'desc', $listAddedCart, $currency);
        return response()->json(['view' => $view,
            'lowestView' => $lowestOffer,
            'highestView' => $highestOffer,
            'highestConversion' => $highestConversion,
            'lowestConversion' => $lowestConversion,
            'added_cart' => $addedToCart,
            'revenue' => round($totalRevenue, 2) . " $currency",
            'conversion' => round($conversion, 2) . '%'
        ]);

    }

    public function getViewByOffer($startDate, $endDate, $sort)
    {
        $shop = \ShopifyApp::shop();
        $view = DB::table('report_view')
            ->select('offer.id', 'offer.name', DB::raw('count(report_view.offer_id) as total'))
            ->join('offer', 'report_view.offer_id', '=', 'offer.id')
            ->where('report_view.shop_id', $shop->id)
            ->where('report_view.created_at', '>=', $startDate)
            ->where('report_view.created_at', '<', $endDate)
            ->groupBy('offer.id', 'offer.name')
            ->orderBy('total', $sort)
            ->limit(3)
            ->get();
        $orderViews = [];
        foreach ($view as $value) {
            array_push($orderViews, ['offer_name' => $value->name, 'offer_view' => $value->total, 'id' => $value->id]);
        }
        return $orderViews;
    }

    public function getViewByConversion($startDate, $endDate, $sort, $orderAdded, $currency)
    {
        $shop = \ShopifyApp::shop();
        $purchase = DB::table('report_sale')
            ->select('offer_id', 'is_purchase', 'created_at', 'amount')
            ->where('is_purchase', 1)
            ->where('shop_id', $shop->id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate)
            ->select('offer_id', DB::raw('count(offer_id) as total'), DB::raw('sum(amount) as amount'))
            ->groupBy('offer_id')
            ->get();
        $orderPurchase = [];
        foreach ($purchase as $value) {
            $orderPurchase[$value->offer_id]['total'] = $value->total;
            $orderPurchase[$value->offer_id]['amount'] = $value->amount;
        }
        foreach ($orderAdded as $key => $value) {
            $timePurchase = $orderPurchase[$key]['total'] ?? 0;
            $moneyPurchase = $orderPurchase[$key]['amount'] ?? 0;
            $orderAdded[$key]['conversion'] = round(($timePurchase / $value['offer_added']) * 100, 2);
            $orderAdded[$key]['amount'] = round($moneyPurchase, 2) . " $currency";
        }
        if ($sort == 'asc') {
            usort($orderAdded, function ($a, $b) {
                return $b['conversion'] <=> $a['conversion'];
            });
        } else {
            usort($orderAdded, function ($a, $b) {
                return $b['conversion'] <=> $a['conversion'];
            });
        }
        return $orderAdded;
    }

    public function getListAddedCart($startDate, $endDate)
    {
        $shop = \ShopifyApp::shop();
        $orderAdded = [];
        $addedCart = DB::table('report_sale')
            ->select('offer.id', 'offer.name', DB::raw('count(report_sale.offer_id) as total'))
            ->join('offer', 'report_sale.offer_id', '=', 'offer.id')
            ->where('report_sale.shop_id', $shop->id)
            ->whereDate('report_sale.created_at', '>=', $startDate)
            ->whereDate('report_sale.created_at', '<', $endDate)
            ->groupBy('offer.id', 'offer.name')
            ->get();
        foreach ($addedCart as $value) {
            $orderAdded[$value->id] = ['offer_name' => $value->name, 'offer_added' => $value->total, 'id' => $value->id];
        }
        return $orderAdded;
    }
}
