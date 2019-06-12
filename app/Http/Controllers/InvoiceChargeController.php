<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Charge;

class InvoiceChargeController extends Controller
{
    public function getAllInvoice($shop_id)
    {
        $view_by_months = DB::select("select month(created_at) as month, year(created_at) as year, count(*) as view from report_view where shop_id = $shop_id group by year(created_at), month(created_at);");
        foreach ($view_by_months as &$view_by_month) {
            $view_by_month = (array)$view_by_month;
        }
        return $view_by_months;
    }

    public function listInvoice($shop_id)
    {
        $shop = \ShopifyApp::shop();
        $shop_id = $shop->id;
        $view_by_months = Charge::where('shop_id', $shop_id)->get()->toArray();
        return $view_by_months;
    }

    public function calculatePrice($view_by_month)
    {
        $view_count = $view_by_month['view'];
        $plan = DB::table('plans')->select(['name', 'price'])->where('capped_amount', '>=', $view_count)->orderByRaw("(capped_amount - $view_count) asc")
            ->limit(1)->first();
        if (empty($plan)) {
            $plan = DB::table('plans')->select(['name', 'price'])->where('id', 5)->first();
        }
        foreach ((array)$plan as $key => $value) {
            $view_by_month[$key] = $value;
        }
        return $view_by_month;
    }

    public function getThisMonthInvoice($shop_id)
    {
        $startDay = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endDay = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        $view = DB::table('report_view')->select(DB::raw('month(created_at) as month, year(created_at) as year, count(*) as view'))
            ->where('shop_id', $shop_id)
            ->where('created_at', '>=', $startDay)
            ->where('created_at', '<', $endDay)
            ->groupBy(DB::raw('month(created_at),year(created_at)'))->first();
        $view = (array)$view;
        if (empty($view)) {
            $view['month'] = \Carbon\Carbon::now()->month;
            $view['year'] = \Carbon\Carbon::now()->year;
            $view['view'] = 0;
        }
        $view['shop_id'] = $shop_id;
        $view['start_date'] = $startDay;
        $view['end_date'] = $endDay;
//        var_dump($view);
        $view = $this->calculatePrice($view);
        return $view;
    }

    public function showInvoiceList()
    {
        $shop = \ShopifyApp::shop();
        $shop_id = $shop->id;
//        $view_by_months = $this->getAllInvoice($shop_id);
        $view_this_month = $this->getThisMonthInvoice($shop_id);
        $view_by_months = $this->listInvoice($shop_id);
//        foreach ($view_by_months as &$view_by_month) {
//            $view_by_month = $this->calculatePrice($view_by_month);
//        }
        return view('report/invoiceTable')->with('view_by_months', $view_by_months)->with('view_this_month', $view_this_month);
    }
}
