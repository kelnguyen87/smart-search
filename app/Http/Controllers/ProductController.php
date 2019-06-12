<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\ShopifyApp;

class ProductController extends Controller
{
    public function getSelectFormData(Request $request)
    {
        $shop = \ShopifyApp::shop();
        $filterList = $this->getFilterList($shop);
        $productList = $this->getProductList($shop,$request,'');
        $selectedProduct = $this->getListSelectedProduct($request);
        $totalPage = $this->getTotalPage($shop,$request);
        $currency_format = $this->getCurrency($shop);
        return response()->json(['success' => true, 'filterList' => $filterList, 'productList' => $productList,
            'selectedProduct' => $selectedProduct,'totalPage'=>$totalPage,'currencyFormat'=>$currency_format]);
    }

    /**
     * @param ShopifyApp $shop
     * @return array
     */
    public function getFilterList($shop)
    {
        $filterList = [
            'vendor' => $this->getVendorList($shop),
            'product_type' => $this->getProductType($shop),
            'custom_collection' => $this->getCustomCollection($shop)
        ];
        return $filterList;
    }

    /**
     * get vendor list for filter
     * @param ShopifyApp $shop
     * @return array
     */
    public function getVendorList($shop)
    {
        $request = $shop->api()->rest('GET', '/admin/products.json?fields=vendor');
        $rawVendor = $request->body->products;
        $vendorList = [];
        foreach ($rawVendor as $value) {
            $vendorList[] = $value->vendor;
        }
        sort($vendorList);
        return array_unique($vendorList);
    }

    /**
     * get product type list for filter
     * @param $shop
     * @return array
     */
    public function getProductType($shop)
    {
        $request = $shop->api()->rest('GET', '/admin/products.json?fields=product_type');
        $rawProductType = $request->body->products;
        $productTypeList = [];
        foreach ($rawProductType as $value) {
            $productTypeList[] = $value->product_type;
        }
        $productTypeList = array_filter($productTypeList);
        sort($productTypeList);
        return array_unique($productTypeList);
    }

    /**
     * get total page for filter
     * @param ShopifyApp $shop
     * @param Request $request
     * @return float
     */
    public function getTotalPage($shop, $request)
    {
        $searchPartent = $this->generateSearchPartent($request);
        $searchPartent = str_replace(substr($searchPartent,strpos($searchPartent,'limit')),'',$searchPartent);
        $totalProduct = $shop->api()->rest('GET', '/admin/products/count.json'.$searchPartent);
        $pageLimit = $request->input('product_limit') ?? 10;
        $totalPage =  $totalProduct->body->count/$pageLimit;
        return ceil($totalPage);
    }

    /**
     * get list custom collection of shop for filter
     * @param ShopifyApp $shop
     * @return array
     */
    public function getCustomCollection($shop)
    {
        $request = $shop->api()->rest('GET', '/admin/custom_collections.json');
        $rawCollection = $request->body->custom_collections;
        $collectionForFillter = [];
        foreach ($rawCollection as $key => $value) {
            $collectionForFillter[$key]['id'] = $value->id;
            $collectionForFillter[$key]['title'] = $value->title;
        }
        usort($collectionForFillter, function ($a, $b) {
            return $a['title'] <=> $b['title'];
        });
        return $collectionForFillter;
    }

    /**
     * get product list
     * @param ShopifyApp $shop
     * @param Request $request
     * @param string $searchPartent
     * @return array
     */
    public function getProductList($shop, $request, $searchPartent)
    {
        $selectedIds = $request->get('selected_product');
        $listVariantId = [];
        if (isset($selectedIds)){
            $listId = explode(',',$selectedIds);
            foreach ($listId as $key => $value){
                $listId[$key] = explode('-',$value);
            }
            foreach ($listId as $value){
                $listVariantId[] = $value[1];
            }
        }
        //Fetch product form shop
        if ($searchPartent == ''){
            $searchPartent = '?limit=10&page=1&published_status=published';
        }
        $response = $shop->api()->rest('GET', '/admin/products.json'.$searchPartent);
        $rawProducts = $response->body->products;
        $productDetails = [];
        //Assign data into array
        foreach ($rawProducts as $key => $value) {
            $productDetails[$key]['product_id'] = $value->id;
            $productDetails[$key]['title'] = $value->title;
            if (isset($value->image)) {
                $productDetails[$key]['image'] = $value->image->src;
            } else {
                $productDetails[$key]['image'] = '';
            }
            $productDetails[$key]['count_variant'] = 0;
            foreach ($value->variants as $item) {
                $selected = 'false';
                if(in_array($item->id,$listVariantId)){
                    $selected = 'true';
                }
                if ($item->inventory_quantity<=0){
                    $productDetails[$key]['variants'][] = ['variant_id' => $item->id, 'price' => $item->price, 'option' => $item->option1,'selected'=>$selected,'out_of_stock'=>'true'];
                } else {
                    $productDetails[$key]['variants'][] = ['variant_id' => $item->id, 'price' => $item->price, 'option' => $item->option1,'selected'=>$selected,'out_of_stock'=>'false'];
                }
            }
//            if (!isset($productDetails[$key]['variants'])) unset($productDetails[$key]);
        }
        return $productDetails;
    }

    /**
     * generate search pattern for api call
     * @param Request $request
     * @return string
     */
    public function generateSearchPartent($request)
    {
        //generate search partent
        $searchPattern = '?';
        if ($request->input('product_vendor')!='') {
            $searchPattern .= 'vendor=' . $request->input('product_vendor').'&';
        }
        if ($request->input('product_type')!='') {
            $searchPattern .= 'product_type=' . $request->input('product_type').'&';
        }
        if ($request->input('product_collection')!='') {
            $searchPattern .= 'collection_id=' . $request->input('product_collection').'&';
        }
        if ($request->input('product_title')!='') {
            $searchPattern .= 'title=' . $request->input('product_title').'&';
        }
        $searchPattern .= 'limit=' . $request->input('product_limit').'&';
        if ($request->input('page')!='') {
            $searchPattern .= 'page=' . $request->input('page');
        }else{
            $searchPattern .= 'page=1';
        }
        return $searchPattern;
    }

    /**
     * Get list selected product by string of products id and variant
     * @param Request $request
     * @return array
     */
    public function getListSelectedProduct($request)
    {
        $listId = $request->get('selected_product');
        if (!isset($listId)) return [];
        $listId = explode(',',$listId);
        foreach ($listId as $key => $value){
            $listId[$key] = explode('-',$value);
        }
        $listIdString = '';
        $listVariantId = [];
        foreach ($listId as $value){
            $listIdString .= $value[0].',';
            $listVariantId[] = $value[1];
        }
        $shop = \ShopifyApp::shop();
        $response = $shop->api()->rest('GET', '/admin/products.json?ids='.$listIdString."&published_status=published");
        $rawProducts = $response->body->products;
        $productDetails = [];
        //Assign data into array
        foreach ($rawProducts as $key => $value) {
            $name = $value->title;
            $image = $value->image->src;
            foreach ($value->variants as $order => $item) {
                if(in_array($item->id,$listVariantId)){
                    $productDetails[$key.$order]['product_id'] = $item->product_id;
                    $productDetails[$key.$order]['name'] = $name;
                    $productDetails[$key.$order]['image'] = $image;
                    $productDetails[$key.$order]['variant_id'] = $item->id;
                    $productDetails[$key.$order]['title'] = $item->title;
                    $productDetails[$key.$order]['price'] = $item->price;
                }
            }
        }
        return $productDetails;
    }

    /**
     * Filter product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductByFilter(Request $request)
    {
        $shop = \ShopifyApp::shop();
        $productList = $this->getProductList($shop,$request,$this->generateSearchPartent($request));
        $totalPage = $this->getTotalPage($shop,$request);
        return response()->json(['success'=>true,'productList'=>$productList,'totalPage'=>$totalPage]);
    }

    public function getCurrency($shop){
        $shop_info = $shop->api()->rest('GET', '/admin/shop.json')->body->shop;
        $currency_format = $shop_info -> money_with_currency_format;
        $currency_format = str_replace('{{amount}}','',$currency_format);
        return $currency_format;
    }
}
