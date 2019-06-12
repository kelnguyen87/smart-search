<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OhMyBrew\ShopifyApp\ShopifyApp;
use Carbon\Carbon;
use App\Product;
use App\Offer;
use App\Config;

class WidgetController extends Controller
{
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var Offer
     */
    protected $offer;
    /**
     * @var Config
     */
    protected $config;

    /**
     * WidgetController constructor.
     * @param Product $product
     * @param Offer $offer
     * @param Config $config
     */
    public function __construct(Product $product, Offer $offer, Config $config)
    {

        $this->product = $product;
        $this->offer = $offer;
        $this->config = $config;
    }

    /**
     * get widget for product detail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWidget(Request $request)
    {
        //list variable
        $domain = $request->get('domain');
        $productId = $request->get('product_id');
        $shopModel = config('shopify-app.shop_model');
        $triggerType = $request->get('type');
        Log::info('$triggerType: '.$triggerType);
        $shop = $shopModel::withTrashed()->firstOrCreate(['shopify_domain' => $domain]);
        //handle product data
        $listProductId = $this->getProductsByShopifyId($productId);
        Log::info('$listProductId: '.print_r($listProductId,true));
        $offerAvailbleId = $this->getOfferListByProductList($listProductId,$triggerType,'product-detail');
        Log::info('$offerAvailbleId: '.print_r($offerAvailbleId,true));
        //check product have offer
        if(empty($offerAvailbleId)){
            return response()->json(['success' =>  false]);
        }
        $this->insertReportView($offerAvailbleId,$shop);
        $widgetTitle = $this->getWidgetTitle($offerAvailbleId,$shop);
        $widgetDescription = $this->getWidgetDescription($offerAvailbleId,$shop);
        $listRenderProduct = $this->getListProductToRender($offerAvailbleId);
        $buttonTitle = $this->config->where(['path'=>SettingController::CONFIG_PATH['general_button_title'],'shop_id'=>$shop->id])->first()->value ?? 'Add to cart';
        $addedAlert = $this->config->where(['path'=>SettingController::CONFIG_PATH['added_alert_text'],'shop_id'=>$shop->id])->first()->value ?? 'Product was successfully added!';
        $continueText = $this->config->where(['path'=>SettingController::CONFIG_PATH['continue_shop_text'],'shop_id'=>$shop->id])->first()->value ?? 'Continue to shop';
        $goCartText = $this->config->where(['path'=>SettingController::CONFIG_PATH['go_cart_text'],'shop_id'=>$shop->id])->first()->value ?? 'Go to bag';
        //render query pattern
        $queryPattern = '?ids=';
        foreach ($listRenderProduct['shopify_id'] as $value){
            $queryPattern .= $value.',';
        }
        $str = 'init';
        try {
            //get product data
            $request = $shop->api()->rest('GET', "/admin/products.json" . $queryPattern."&published_status=published");
            $data = $request->body->products;
            $str = 'before';
            $transformData = $this->transformProductData($data,$listRenderProduct['variant_id']);
            $str = 'after';
            //render view
            $view = view('widget.widgetUpsell')
                ->with(['data' => $data,
                    'shopify_id'=>$productId,
                    'listVariant' => $listRenderProduct['variant_id'],
                    'title'=>$widgetTitle,
                    'description'=>$widgetDescription,
                    'buttonTitle' => $buttonTitle,
                    'addedAlert' => $addedAlert,
                    'continueText' => $continueText,
                    'goCartText' => $goCartText,
                    'type'=>$triggerType])
                ->render();
            return response()->json(['success' => true, 'view' => $view]);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json(['success' =>  false, 'view' => $str.json_encode($data)]);
        }
    }

    public function getCartWidget(Request $request)
    {
        //list variable
        $domain = $request->get('domain');
        $triggerType = $request->get('type');
        $lineItems = $request->input('items');

        $shopModel = config('shopify-app.shop_model');
        $shop = $shopModel::withTrashed()->firstOrCreate(['shopify_domain' => $domain]);
        //handle product data
        $variantIds = [];
        foreach ($lineItems as $item){
            array_push($variantIds,$item['variant_id']);
        }
        $listProduct_Id = $this->getProductsByVariantId($variantIds);
        $offerAvailbleId = $this->getOfferListByProductList($listProduct_Id,$triggerType,'cart');

        //check product have offer
        if(empty($offerAvailbleId)){
            return response()->json(['success' =>  false]);
        }
        $mapper = $this->mapVariantWithTriggerProduct($variantIds,$offerAvailbleId,$shop->id);

        $listRenderProduct = $this->getListProductToRender($offerAvailbleId,$variantIds);

        $this->insertReportView($offerAvailbleId,$shop);
        $widgetTitle = $this->getWidgetTitle($offerAvailbleId,$shop);
        $widgetDescription = $this->getWidgetDescription($offerAvailbleId,$shop);
        $buttonTitle = $this->config->where(['path'=>SettingController::CONFIG_PATH['general_button_title'],'shop_id'=>$shop->id])->first()->value ?? 'Add to cart';
        $addedAlert = $this->config->where(['path'=>SettingController::CONFIG_PATH['added_alert_text'],'shop_id'=>$shop->id])->first()->value ?? 'Product was successfully added!';
        $continueText = $this->config->where(['path'=>SettingController::CONFIG_PATH['continue_shop_text'],'shop_id'=>$shop->id])->first()->value ?? 'Continue to shop';
        $goCartText = $this->config->where(['path'=>SettingController::CONFIG_PATH['go_cart_text'],'shop_id'=>$shop->id])->first()->value ?? 'Go to bag';
        //render query pattern
        $queryPattern = '?ids=';
        foreach ($listRenderProduct['shopify_id'] as $value){
            $queryPattern .= $value.',';
        }
        try {
            //get product data
            $request = $shop->api()->rest('GET', "/admin/products.json" . $queryPattern);
            $data = $request->body->products;
            //render view
            $view = view('widget.widget')->with([
                'data' => $data,
                'mapper_data' => $mapper['trigger_offer_variants_mappings'],
                'listVariant' => $listRenderProduct['variant_id'],
                'variant_mapping'=>$mapper['mapping_array'],
                'title'=>$widgetTitle,
                'buttonTitle' => $buttonTitle,
                'addedAlert' => $addedAlert,
                'continueText' => $continueText,
                'goCartText' => $goCartText,
                'description'=>$widgetDescription,
                'type'=>$triggerType
            ])->render();
            return response()->json(['success' => true, 'view' => $view]);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json(['success' => false,'error'=>$e]);
        }
    }

    /**
     * @param array $listOffer
     * @param ShopifyApp $shop
     * @return string
     */
    public function getWidgetTitle($listOffer, $shop)
    {
        if (sizeof($listOffer) > 1 ){
            $titleModel = $this->config->where(['path'=>SettingController::CONFIG_PATH['general_title'],'shop_id'=>$shop->id])->first()->value ?? '';
            return $titleModel;
        }
        $offerModel = $this->offer->find($listOffer[0]);
        return $offerModel->title;
    }

    /**
     * @param array $listOffer
     * @param ShopifyApp $shop
     * @return string
     */
    public function getWidgetDescription($listOffer, $shop)
    {
        if (sizeof($listOffer) > 1 ){
            $descriptionModel = $this->config->where(['path'=>SettingController::CONFIG_PATH['general_description'],'shop_id'=>$shop->id])->first()->value ?? '';
            return $descriptionModel;
        }
        $offerModel = $this->offer->find($listOffer[0]);
        return $offerModel->description;
    }

    /**
     * get list product id (one shopify id have many varriant) from shopify id
     * @param string $shopifyId
     * @return array
     */
    public function getProductsByShopifyId($shopifyId)
    {
        $productModel = $this->product;
        $productInternalId = $productModel->where('variant_id',$shopifyId)->get();
        $listProduct = [];
        foreach ($productInternalId as $value){
            $listProduct[] = $value->id;
        }
        return $listProduct;
    }

    /**
     * get list product id (one shopify id have many varriant) from list variant ids
     * @param array $variantIds
     * @return array
     */
    public function getProductsByVariantId($variantIds)
    {
        $productModel = $this->product;
        $productInternalId = $productModel->whereIn('variant_id',$variantIds)->get();
        $listProduct = [];
        foreach ($productInternalId as $value){
            $listProduct[] = $value->id;
        }
        return $listProduct;
    }

    /**
     * Get Offer list from list product id
     * @param array $listProductId
     * @return array
     */
    public function getOfferListByProductList($listProductId,$triggerType,$triggerPlace)
    {
        $currentTime = Carbon::now()->format('Y-m-d');
        $offerModel = $this->offer;
        $listTrigger = DB::table('trigger_collection')->whereIn('product_id',$listProductId)->get();
        $listOfferId = [];
        foreach ($listTrigger as $value){
            $listOfferId[] = $value->offer_id;
        }
        $offerAvailble = $offerModel
            ->whereIn('id',$listOfferId)
            ->where('type',$triggerType)
            ->where('trigger_place',$triggerPlace)
            ->where('start_day','<=',$currentTime)
            ->where('end_day','>=',$currentTime)
            ->where('active',1)
            ->get();
        $offerAvailbleId = [];
        foreach ($offerAvailble as $value){
            $offerAvailbleId[] = $value->id;
        }
        return $offerAvailbleId;
    }

    /**
     * get list product to render widget
     * @param array $offerId
     * @return array
     */
    public function getListProductToRender($offerId, $variantIdsInCart=[])
    {
        $productModel = $this->product;
        $listOfferProduct = DB::table('offer_collection')->whereIn('offer_id',$offerId)->get();
        $listOfferProductId = [];
        foreach ($listOfferProduct as $value){
            $listOfferProductId[] = $value->product_id;
        }
        $listRawVisibleProduct = $productModel->whereIn('id',$listOfferProductId)->get();
        $listShopifyProduct = [];
        $listVariantProduct = [];
        foreach ($listRawVisibleProduct as $value){
            $listShopifyProduct[] = $value->shopify_id;
            if (!in_array($value->variant_id,$variantIdsInCart)) {
                $listVariantProduct[] = $value->variant_id;
            }
        }
        return ['shopify_id'=>$listShopifyProduct,'variant_id'=>$listVariantProduct];
    }

    /**
     * insert into report view table each time widget is loaded
     * @param array $offerId
     * @param ShopifyApp $shop
     */
    public function insertReportView($offerId, $shop)
    {
        $insertData = [];
        $currentDate = Carbon::now();
        foreach ($offerId as $value){
            array_push($insertData,['offer_id'=>$value,'shop_id'=>$shop->id,'created_at'=>$currentDate]);
        }
        DB::table('report_view')->insert($insertData);
    }

    /**
     * transform data from shopify product api
     * @param array $data
     * @param array $listVariants
     * @return array
     */
    public function transformProductData($data,$listVariants)
    {
        $products = [];
        foreach ($data as $value){
            Log::info('$value: '.print_r($value,true));
            $product = [
                'handle' => $value->handle,
                'title' => $value->title,
                'product_id' => $value->id
            ];
            if (!empty($value->image)){
                $product['img_src'] = $value->image->src;
            } else {
                $product['img_src'] = '';
            }
            if (sizeof($value->variants)>1){
                $product['multi_variants'] = 0;
                $product['variant_id'] = $value->variants[0]->id;
                $product['price'] = $value->variants[0]->price;
                $product['inventory'] = 1;
                if ($value->variants[0]->inventory_quantity <= 0) $product['inventory'] = 0;
            }else{
                $product['multi_variants'] = 1;
                $product['inventory'] = 1;
                foreach ($value->variants as $item){
                    if ($item->inventory_quantity >=0 && in_array($item->id,$listVariants)) {
                        $product['variants'][] = [
                            'variant_id' => $item->id,
                            'price' => $item->price,
                            'inventory' => $item->inventory_quantity
                        ];
                    }
                    if (!isset($product['variants'])) $product['inventory'] = 0;
                }
            }
            array_push($products,$product);
        }
        foreach ($products as $key => $value){
            if($value['inventory'] == 0) unset($products[$key]);
        }
        return $products;
    }

    public function mapVariantWithTriggerProduct($variantIds,$offerIds,$shopId)     {
        $offerIds_format = '(';
        $offerIds_format .= join(',',$offerIds);
        $offerIds_format .= ')';

        $variantIds_format = '(';
        $variantIds_format .= join(',',$variantIds);
        $variantIds_format .= ')';

        $trigger_offer_variants_mappings = DB::select(
            "select mapping.trigger_variant_id, mapping.offer_variant_id, mapping.offer_id from
                    (select distinct trigger_variant.variant_id as trigger_variant_id, offer_variant.variant_id as offer_variant_id, trigger_variant.offer_id
                        from 
                        (select product.variant_id, trigger_collection.offer_id from trigger_collection join product on product.id = trigger_collection.product_id 
                            where product.variant_id in $variantIds_format) as trigger_variant
                        join
                        (select product.variant_id, offer_collection.offer_id from offer_collection join product on product.id = offer_collection.product_id) as offer_variant 
                        on trigger_variant.offer_id = offer_variant.offer_id
                        where trigger_variant.offer_id in $offerIds_format) as mapping
                    join offer on mapping.offer_id = offer.id
                    where offer.shop_id = $shopId;
                    ");
        $mapping_array = [];
        $variants_to_render = [];
        foreach ($trigger_offer_variants_mappings as $trigger_offer_variants_mapping){
            $mapping_array[$trigger_offer_variants_mapping->offer_variant_id] = $trigger_offer_variants_mapping->trigger_variant_id;
            if (!in_array($trigger_offer_variants_mapping->offer_variant_id,$variantIds)) {
                $variants_to_render[] = $trigger_offer_variants_mapping->offer_variant_id;
            }
        }

        return ['trigger_offer_variants_mappings'=>$trigger_offer_variants_mappings,'mapping_array'=>$mapping_array,'variants_to_render'=>$variants_to_render];
    }
}
