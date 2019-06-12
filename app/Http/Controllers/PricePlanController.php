<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use Illuminate\Support\Facades\Log;

class PricePlanController extends Controller
{
    public function configPlans()
    {
        $plans = [
            ['name' => 'Plan 1', 'price' => 5, 'capped_amount' => 200, 'type' => 0, 'test' => 0, 'on_install' => 0],
            ['name' => 'Plan 2', 'price' => 10, 'capped_amount' => 1000, 'type' => 0, 'test' => 0, 'on_install' => 0],
            ['name' => 'Plan 3', 'price' => 20, 'capped_amount' => 2000, 'type' => 0, 'test' => 0, 'on_install' => 0],
            ['name' => 'Plan 4', 'price' => 40, 'capped_amount' => 5000, 'type' => 0, 'test' => 0, 'on_install' => 0],
            ['name' => 'Plan 5', 'price' => 50, 'capped_amount' => -1, 'type' => 0, 'test' => 0, 'on_install' => 0]
        ];
        return $plans;
    }

    public function setPlans()
    {
        try {
            $plans = $this->configPlans();
            foreach ($plans as $plan) {
                $old_plan = Plan::where('name', $plan['name'])->first();
                if (!$old_plan) {
                    $new_plan = Plan::firstOrCreate($plan);
                } else {
                    $old_plan->name = $plan['name'];
                    $old_plan->price = $plan['price'];
                    $old_plan->save();
                }
            }
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json(['success' =>  false]);
        }
    }

    public function listPlans()
    {
        $plans = Plan::all();
        return view('plan.plan_list')->with('plans',$plans);
    }
}
