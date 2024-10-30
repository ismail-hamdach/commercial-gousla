<?php

namespace App\Http\Controllers\Admin\statistics;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientClassement extends Controller
{
    public function index()
    {
      /*   $this->chiffreAffaireClient(null, null, null); */
        return view('Dashboard.admin.statistics.classement', [
            'clients' => Client::get(['id', 'name']),
        ]);
    }

    public function operationHandler(Request $request)
    {
        switch ($request->get('_target')) {
            case 'chiffreAffaireClient':
                return $this->chiffreAffaireClient($request->get('startDate'), $request->get('endDate'), $request->get('clientId'));
        }
    }

    public function chiffreAffaireClient($startDate, $endDate, $clientId)
    {
        $chiffreAffaireClient = Client::leftJoin('orders',  function ($join) use ($startDate, $endDate) {
            $join->on('clients.id', '=', 'orders.client_id');

            if ($startDate && $endDate) {
                $join->whereRaw('DATE(orders.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
            }
        })
            ->leftJoin('details_orders', 'details_orders.order_id', '=', 'orders.ref')
            ->where(function ($query) use ($clientId) {
                if ($clientId != 0) $query->whereRaw("clients.id = ". $clientId);
            })
            ->selectRaw('IFNULL(SUM(details_orders.total), 0) AS chiffreAffaire, clients.name')
            ->groupBy('clients.id')
            ->orderByDesc('chiffreAffaire')
            ->get()
            ->toArray();

        return response()->json($chiffreAffaireClient);
    }
}
