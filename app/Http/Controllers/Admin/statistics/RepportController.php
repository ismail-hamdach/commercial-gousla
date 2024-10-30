<?php

namespace App\Http\Controllers\Admin\statistics;

use App\Http\Controllers\Controller;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepportController extends Controller
{
    public function index()
    {
        // $orders=Order::whereRaw('date(created_at) = current_date()')->paginate(3);
        $employyes = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'employee']);
        })->pluck('name', 'id');
        // dd($prdPlusVendu,  $totalOrders,$commercial);

        // dd( $orders);
        return view('Dashboard.admin.statistics.repport', ['employyes' => $employyes]);
    }
    public function search(Request $request)
    {

        $validated = $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'employee' => 'nullable|numeric'

        ]);

        $totalOrders = Order::join('details_orders', 'orders.ref', '=', 'details_orders.order_id')
            ->where(function ($query) use ($validated) {
                if ($validated['date_debut'])
                    $query->whereDate('details_orders.created_at', '>=', $validated['date_debut']);
                if ($validated['date_fin'])
                    $query->whereDate('details_orders.created_at', '<=', $validated['date_fin']);
                if ($validated['employee'])
                    $query->where('orders.user_id', $validated['employee']);
            })->selectRaw('count(DISTINCT orders.id) as totalOrders , sum(details_orders.total)  as totalPrice,sum(details_orders.profit) as profit')->first()->toArray();
        //SELECT product_name, user_id,count(*) as totall FROM details_orders group By user_id order by totall desc;
        $commercial = DB::table('details_orders')
            ->join('orders', 'orders.ref', '=', 'details_orders.order_id')
            ->join('users', 'users.id', '=', 'details_orders.user_id')
            ->where(function ($query) use ($validated) {
                if ($validated['date_debut'])
                    $query->whereDate('details_orders.created_at', '>=', $validated['date_debut']);
                if ($validated['date_fin'])
                    $query->whereDate('details_orders.created_at', '<=', $validated['date_fin']);
                if ($validated['employee'])
                    $query->where('orders.user_id', $validated['employee']);
            })
            ->selectRaw('users.name, count(*) as total')
            ->groupBy('details_orders.user_id', 'users.name')->orderByDesc('total')->first();

        $prdPlusVendu = DB::table('details_orders')->join('orders', 'orders.ref', '=', 'details_orders.order_id')
            ->where(function ($query) use ($validated) {
                if ($validated['date_debut'])
                    $query->whereDate('details_orders.created_at', '>=', $validated['date_debut']);
                if ($validated['date_fin'])
                    $query->whereDate('details_orders.created_at', '<=', $validated['date_fin']);
                if ($validated['employee'])
                    $query->where('orders.user_id', $validated['employee']);
            })->selectRaw('product_name, count(*) as total')->groupBy('product_name')->orderByDesc('total')->first();
        return response()->json(['prdPlusVendu' => $prdPlusVendu, 'commercial' => $commercial, 'totalOrders' => $totalOrders, 'data' => $validated]);
        // return view('Dashboard.admin.statistics.repport',['Orders'=>$orders]);
    }

    public function incoicePDF(Request $request)
    {
        /*date_debut,
        date_fin,
        totalCommand,
        productPlusVendu,
        totalPrice,
        commercial,
        commission,
        employee*/
        $validated = $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'totalCommand' => 'nullable|numeric',
            'productPlusVendu' => 'nullable|string',
            'totalPrice' => 'nullable|string',
            'commercial' => 'nullable|string',
            'commission' => 'nullable|numeric',
            'employee' => 'nullable|numeric'
        ]);
        $orders = Order::where(function ($query) use ($validated) {
            if ($validated['date_debut'])
                $query->whereDate('orders.created_at', '>=', $validated['date_debut']);
            if ($validated['date_fin'])
                $query->whereDate('orders.created_at', '<=', $validated['date_fin']);
            if ($validated['employee'])
                $query->where('orders.user_id', $validated['employee']);
        })->join('details_orders','orders.ref','=','details_orders.order_id')
        ->groupBy(['details_orders.order_id'])
        ->selectRaw('orders.*, sum(details_orders.profit) as  totalProfit ')
        ->get();

        // return response()->json( ['data'=>$validated]);
        $validated['totalPrice'] ? $validated['totalPrice'] .= ' DH' : null;
        $validated['commission'] ? $validated['commission'] .= ' DH' : null;
        $validated['totalCommand'] == 0  ? $validated['totalCommand']= null  : null;
        //for products

        //if posted data is not empty
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', TRUE);
        $options->set('chroot', '/');
        $options->setIsRemoteEnabled(true);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);
        $dompdf->set_option('isRemoteEnabled', TRUE);

        $output = '<!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />

               <!-- Invoice styling -->
                <style>
                    body {
                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                        text-align: center;
                        color: #777;
                        position:relative;
                    }
                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }
                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border: 1px solid #ddd;
                        font-weight: bold;
                        font-size: x-small;
                         white-space: nowrap;
                    }
                    td{
                        text-align:center
                    }
                </style>
            </head>
            <body>
                <div class="invoice-box">
                    <table style="min-width: 40ch;">
                        <tr class="information">
                            <td colspan="8" >
                                <table style="min-width:97%" >
                                    <tr>
                                        <td>
                                            <div style="border: 1px solid #eee; text-align:left; padding:5px; height:120px;">
                                                <h2 style="border-bottom:1px solid #eee;">Rapport</h2>
                                                <strong>Date</strong> : ' . date('d-m-Y') . '<br />
                                            </div>
                                        </td>                                    
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="heading" style="width=100%">
                                <td colspan="3" > Produit Plus Vendu  </td>
                                <td  >   T.cmd   </td>
                                <td >   T.vents  </td>
                                <td> T Commission  </td>
                                <td  colspan="2"> Commercial  </td>
                        </tr>
                        <tr  style="width=100%">
                        <td  colspan="3" style=";border: 1px solid #eee;" > '. $validated['productPlusVendu'] . ' </td>
                        <td  style=";border: 1px solid #eee;" > '. $validated['totalCommand'] . ' </td>
                            <td  style=";border: 1px solid #eee;" > '. $validated['totalPrice'] . ' </td>
                            <td  style=";border: 1px solid #eee;"> '. $validated['commission'] . ' </td>
                            <td  colspan="2"  style=";border: 1px solid #eee;"> '. $validated['commercial'] . ' </td>                            
                        </tr>
                        <tr class="top">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td class="title">
                                        </td>
                                    </tr>
                                    <tr>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>Date</td>
                            <td>Ref</td>
                            <td colspan="2"   >Client</td>
                            <td>PM</td>
                            <td>Commercial</td>
                            <td>Total</td>
                            <td>%</td>
                        </tr>';


                foreach ($orders as $order) {
                    $output .= '<tr>
                                    <td  style="font-size: x-small; white-space: nowrap; border: 1px solid #eee;">' .$order->created_at. '</td>
                                    <td style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->ref . '</td>
                                    <td colspan="2"  style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->client->name . '</td>
                                     <td style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->payment_method . ' </td>
                                    <td  style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->user->name . '</td>
                                    <td style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->total   . ' DH</td>  
                                    <td style="font-size: x-small; white-space: nowrap;border: 1px solid #eee;">' . $order->totalProfit  . ' DH</td>  
                                </tr>';
                }
                $output .= '
                       

                    </table>
                </div>
              

            </body>
        </html>';
        $dompdf->loadHtml($output);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        // echo '<pre>', print_r($output, true), '</pre>';
        // $dompdf->stream();
        return response()->stream(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Rapport.pdf"',
            ]
        );

        // Storage::put('public/csv/name.pdf',$content);
    }
}
