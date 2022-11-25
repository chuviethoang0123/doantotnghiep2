<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashBoardController extends Controller
{
    public function dashboard(Request $request) {
        $start_date = CarBon::parse($request->start_date)->format('Y-m-d')." 00:00:00";
        $end_date = CarBon::parse($request->end_date)->format('Y-m-d')." 23:59:59";
        $distance = Carbon::parse($request->end_date)->diffInDays($request->start_date);
        $distance_before = Carbon::parse($request->end_date)->diffInDays($request->start_date);
        if (CarBon::parse($request->start_date)->format('Y-m-d') == CarBon::parse($request->end_date)->format('Y-m-d')) {
            $distance_before = 1;
        }
        $before_date = Carbon::parse($request->start_date)->subDays($distance_before);
        
        $order_money_before = DB::table('order')->whereBetween('created_at', [$before_date,$start_date])->where('action', 4)->sum('order_total_money');
        $order_money_after = DB::table('order')->whereBetween('created_at', [$start_date,$end_date])->where('action', 4)->sum('order_total_money');

        $order_count_before = DB::table('order')->whereBetween('created_at', [$before_date,$start_date])->whereIn('action', [1,2,3,4])->count();
        $order_count_after = DB::table('order')->whereBetween('created_at', [$start_date,$end_date])->whereIn('action', [1,2,3,4])->count();

        $user_before = DB::table('users')->whereBetween('created_at', [$before_date,$start_date])->count();
        $user_after = DB::table('users')->whereBetween('created_at', [$start_date,$end_date])->count();

        $rate_before = DB::table('rate')->whereBetween('created_at', [$before_date,$start_date])->count();
        $rate_after = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->count();

        $rateValue1 = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->where('rate_scores', 1)->count();
        $rateValue2 = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->where('rate_scores', 2)->count();
        $rateValue3 = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->where('rate_scores', 3)->count();
        $rateValue4 = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->where('rate_scores', 4)->count();
        $rateValue5 = DB::table('rate')->whereBetween('created_at', [$start_date,$end_date])->where('rate_scores', 5)->count();
        $rateValueParams = [$rateValue1,$rateValue2,$rateValue3,$rateValue4,$rateValue5];
        

        $paramsOverview = [
            'order_money' => $order_money_after,
            'precent_order_money' => $this->compare($order_money_before,$order_money_after),
            'order_count' => $order_count_after,
            'precent_order_count' => $this->compare($order_count_before,$order_count_after),
            'user_count' => $user_after,
            'precent_user_count' => $this->compare($user_before,$user_after),
            'rate_count' => $rate_after,
            'precent_rate_count' => $this->compare($rate_before,$rate_after),
        ];

        $arrDate = [];
        $dateOrder = [];
        $countOrder = [];
        for($i = 0; $i <= $distance; $i++) {
            $date = Carbon::parse($request->start_date)->addDays($i)->format('d-m-Y');
            $dateformat = Carbon::parse($request->start_date)->addDays($i)->format('Y-m-d');
            $order = Order::whereBetween('created_at', [$dateformat." 00:00:00", $dateformat." 23:59:59"])->where('action', 4)->sum('order_total_money');
            $orderCount = Order::whereBetween('created_at', [$dateformat." 00:00:00", $dateformat." 23:59:59"])->whereIn('action', [1,2,3,4])->count();

            array_push($arrDate, $date);
            array_push($dateOrder, $order);
            array_push($countOrder, $orderCount);
        }

        return $this->responseSuccess(['total' => $paramsOverview, 'date' => $arrDate, 'sum_order' => $dateOrder, 'order_count' => $countOrder, 'rate_value' => $rateValueParams]);
    }

    public function compare($before, $after) {
        if ($before === 0 || $after === 0 || $before === $after) {
            return '+0';
        } else if ($before > $after) {
            return '-' . (($before - $after) / $before) * 100;
        } else if ($before < $after){
            return '+' . ($after / $before) * 100;
        }
    }
}
