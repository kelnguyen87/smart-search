<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Charge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to stores about last month invoice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $shop_id = $user->shop_id;
            $all_invoices = $this->savePreviousInvoices($shop_id);
            Log::info('$all_invoices: '.$all_invoices);
            $last_month = $this->getLastMonthInvoice($shop_id);
            if ($last_month['view'] != 0) {
                $last_month_invoice = $this->saveInvoice($last_month);
            } else {
                continue;
            }
            $month = $last_month['month'];
            $year = $last_month['year'];
            $last_month['subject'] = "Smart Upsell $month/$year App Monthly Invoice for store $user->name";
            if ($user->email == 'minhvc@smartosc.com') {
                \Mail::to($user->email)
                    ->send(new \App\Mail\MonthlyInvoice($last_month));
            }
        }
        $this->info('Sent monthly invoice');
    }

    public function getLastMonthInvoice($shop_id)
    {
        $startDay = \Carbon\Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $endDay = \Carbon\Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $view = DB::table('report_view')->select(DB::raw('month(created_at) as month, year(created_at) as year, count(*) as view'))
            ->where('shop_id', $shop_id)
            ->where('created_at', '>=', $startDay)
            ->where('created_at', '<', $endDay)
            ->groupBy(DB::raw('month(created_at),year(created_at)'))->first();
        $view = (array)$view;
        if (empty($view)) {
            $view['month'] = \Carbon\Carbon::now()->subMonth()->month;
            $view['year'] = \Carbon\Carbon::now()->subMonth()->year;
            $view['view'] = 0;
        }
        $view['shop_id'] = $shop_id;
        $view['start_date'] = $startDay;
        $view['end_date'] = $endDay;
//        var_dump($view);
        $view = $this->calculatePrice($view);
        return $view;
    }

    public function calculatePrice($view_by_month)
    {
        Log::info(print_r($view_by_month, true));
        if (isset($view_by_month['view'])) {
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
        } else {
            return $view_by_month;
        }
    }

    public function getAllInvoice($shop_id)
    {
        $start_of_this_month =  \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $view_by_months = DB::select("select created_at, month(created_at) as month, year(created_at) as year, count(*) as view from report_view where shop_id = $shop_id, created_at < $start_of_this_month group by year(created_at), month(created_at);");
//        Log::info('$view_by_months query: '.print_r($view_by_months,true));
        foreach ($view_by_months as &$view_by_month) {
            $view_by_month = (array)$view_by_month;
            $date = Carbon::parse($view_by_month['created_at']);
//            Log::info('$date: '.print_r($date,true));
            $view_by_month['shop_id'] = $shop_id;
            $view_by_month['start_date'] = $date->startOfMonth()->toDateString();
            $view_by_month['end_date'] = $date->endOfMonth()->toDateString();
            $view_by_month = $this->calculatePrice($view_by_month);
        }
        return $view_by_months;
    }

    public function savePreviousInvoices($shop_id)
    {
        try {
            $view_by_months = $this->getAllInvoice($shop_id);
            Log::info('$view_by_months: '.print_r($view_by_months,true));
            foreach ($view_by_months as $view_by_month) {
                $this->saveInvoice($view_by_month);
            }
        } catch (\Throwable $e) {
            Log::error($e);
            return 'failed';
        }
    }

    public function saveInvoice($last_month)
    {
        $charge = Charge::firstOrCreate([
            'charge_id' => strtotime($last_month['start_date']) + $last_month['shop_id'],
            'test' => 0,
            'status' => 0,
            'name' => $last_month['name'],
            'type' => 0,
            'price' => $last_month['price'],
            'capped_amount' => $last_month['view'],
            'activated_on' => $last_month['start_date'],
            'trial_ends_on' => $last_month['end_date'],
            'shop_id' => $last_month['shop_id']
        ]);

        return $charge;
    }
}
