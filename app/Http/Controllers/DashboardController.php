<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* if(Auth::user()->hasRole('user'))
        {
            return view('Dashboard.users.index');
        }elseif(Auth::user()->hasRole(['admin', 'master']))
        { */
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;
        $user_id = auth()->user()->id;

        if ($date_debut != null && $date_fin != null) {
            if ($date_fin == null) {
                $date_fin = Carbon::now()->format('Y-m-d');
            }

            $percentage = '';
            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('m'); // 8

            $totalOrder = Order::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->count();
            $totalOrderInvalid = Order::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->where('validation', '=', 0)->count();
            $profit = DetailsOrder::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('profit');
            $totalOrdervalid = Order::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->where('validation', '=', 1)->count();
            $Turnover = Order::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('total');
            $TurnoverlastMonth = DetailsOrder::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('total');
            if ($TurnoverlastMonth > 0) {
                $percentage = ($Turnover - $TurnoverlastMonth) / $TurnoverlastMonth;
            } else {
                $percentage = 0;
            }

            $Products = Product::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->count();
            $clients = Client::where('user_id', $user_id)->whereBetween('created_at', [$date_debut, $date_fin])->count();
            $Orders = Order::where('user_id', $user_id)->latest()->take(5)->get();
            return view('Dashboard.admin.index', compact('totalOrder', 'Turnover', 'Products', 'clients', 'Orders', 'percentage', 'totalOrderInvalid', 'totalOrdervalid', 'profit'));
        } else {
            $percentage = '';
            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('m'); // 8

            $totalOrder = Order::where('user_id', $user_id)->count();
            $totalOrderInvalid = Order::where('user_id', $user_id)->where('validation', '=', 0)->count();
            $profit = DetailsOrder::where('user_id', $user_id)->sum('profit');
            $totalOrdervalid = Order::where('user_id', $user_id)->where('validation', '=', 1)->count();
            $Turnover = DetailsOrder::where('user_id', $user_id)->sum('total');
            $TurnoverlastMonth = DetailsOrder::where('user_id', $user_id)->sum('total');
            
            if ($TurnoverlastMonth > 0) 
                $percentage = ($Turnover - $TurnoverlastMonth) / $TurnoverlastMonth;
            else 
                $percentage = 0;

            $Products = Product::where('user_id', $user_id)->count();
            $clients = Client::where('user_id', $user_id)->count();
            $Orders = Order::where('user_id', $user_id)->latest()->take(5)->get();
            return view('Dashboard.admin.index', compact('totalOrder', 'Turnover', 'Products', 'clients', 'Orders', 'percentage', 'totalOrderInvalid', 'totalOrdervalid', 'profit'));
        }
        /* } */
    }
}
