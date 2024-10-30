<?php

namespace App\Http\Controllers\Admin\statistics;

use App\Http\Controllers\Controller;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\CheckClient;

class BoardController extends Controller
{
    public function index()
    {
        return view('Dashboard.admin.statistics.board');
    }

    public function operationHandler(Request $request)
    {
        switch ($request->get('_target')) {
            case 'chiffreAffaire':
                return $this->getChiffreAffaire($request->get('startDate'), $request->get('endDate'));
            case 'commession':
                return $this->getCommession($request->get('startDate'), $request->get('endDate'));
            case 'bestSellingProducts':
                return $this->getBestSellingProducts($request->get('startDate'), $request->get('endDate'));
            case 'profit':
                return $this->getProfit($request->get('startDate'), $request->get('endDate'));
            case 'check_client':
                return $this->checkClient($request->get('startDate'), $request->get('endDate'));
        }
    }

    public function checkClient($startDate, $endDate)
    {
        $checkClient   =  CheckClient::join('clients','check_clients.client_id','=','clients.id')->join('users','check_clients.user_id','=','users.id')
        ->where(function($query) use ($startDate , $endDate)  {
             if ($startDate && $endDate) {
                $query->whereRaw('DATE(check_clients.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }})->selectRaw('users.name as user , clients.name as client ,check_clients.created_at as date ')->get()->toArray();
         return response()->json($checkClient);
    }
    public function getProfit($startDate, $endDate)
    {
        $productStock   =  Product::selectRaw('sum(price *  qnt )  as totalPrice ,  sum(priceAchat *  qnt  ) as totalPriceAchat  ')->first(); 
        $pn = DetailsOrder::where(function($query) use ($startDate , $endDate)  {
             if ($startDate && $endDate) {
                $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }})->selectRaw('sum( (qnt  *  (     price  -  priceAchat  ))   -  profit  ) as profitNet')->first();
        $data=[['title'=>"Profit Net" , 'value'=>number_format($pn->profitNet ,2) ?? 0 ],['title'=>"total Avec Prix d'achat" , 'value'=>number_format($productStock->totalPriceAchat,2) ?? 0 ],['title'=>"total Avec Prix de vent" , 'value'=>number_format($productStock->totalPrice ,2) ?? 0 ]] ;  
        return response()->json($data);
    }
    public function getChiffreAffaire($startDate, $endDate)
    {
        $chiffreAffaire = User::leftJoin('orders', function ($join) use ($startDate, $endDate) {
            $join->on('users.id', '=', 'orders.user_id')->whereNull('orders.deleted_at');;
    
            if ($startDate && $endDate) {
                $join->whereRaw('DATE(orders.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }
        })
            // ->where('users.name', '!=', 'Master')
            ->groupBy('users.id')
            ->orderBy('users.id', 'ASC')
            ->selectRaw('IFNULL(SUM(orders.total), 0) AS totalChiffre, users.name')
            ->get()
            ->toArray();

        return response()->json($chiffreAffaire);
    }

    public function getCommession($startDate = null, $endDate = null)
    {
        $commession = User::leftJoin('details_orders', function ($join) use ($startDate, $endDate) {
            $join->on('users.id', '=', 'details_orders.user_id')->whereNull('details_orders.deleted_at');
    
            if ($startDate && $endDate) {
                $join->whereRaw('DATE(details_orders.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }
        })
            // ->where('users.name', '!=', 'Master')
            ->groupBy('users.id')
            ->orderBy('users.id', 'ASC')
            ->selectRaw('IFNULL(SUM(details_orders.profit), 0) AS commession, users.name')
            ->get()
            ->toArray();

        return response()->json($commession);
    }

    public function getBestSellingProducts($startDate = null, $endDate = null)
    {
        $bestSellingProducts = DetailsOrder::join('users', function ($join) use ($startDate, $endDate) {
            $join->on('users.id', '=', 'details_orders.user_id')->whereNull('details_orders.deleted_at');

            if ($startDate && $endDate) {
                $join->whereRaw('DATE(details_orders.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }
        })
            ->selectRaw('COUNT(details_orders.id) AS countOccurence, details_orders.product_name, users.name')
            ->groupBy('product_name')
            ->orderByDesc('countOccurence')
            ->limit(5)
            ->get()
            ->toArray();

        return response()->json($bestSellingProducts);
    }
}
