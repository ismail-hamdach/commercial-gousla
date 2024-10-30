<?php

namespace App\Http\Controllers\Admin\orders;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Categorie;
use App\Models\Depot;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\productsession;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total = 0; 
         $profit =  'sum(details_orders.profit )  as profit'; 
         if (auth()->user()->hasRole('admin|master|gerant BL'))   $profit =  'sum(details_orders.qnt * (details_orders.price - details_orders.priceAchat) - details_orders.profit )  as profit';
        //$Orders = Order::orderBy('created_at','DESC')->get();
      
        $Orders = Order::join('details_orders' ,  'details_orders.order_id' ,  '=' , 'orders.ref')->where(function($query){
            if (!auth()->user()->hasRole('admin|master|gerant BL'))
            $query->where('details_orders.user_id', auth()->user()->id);
        })->orderBy('id', 'DESC')->groupByRaw('details_orders.order_id') ->selectRaw('orders.* , '.$profit)->get();

        //$detailsOrder = DetailsOrder::where('order_id','=',$Orders->ref)->get();
        return view("Dashboard.admin.orders.show", compact('Orders'));
    }

    public function details(Request $request)
    {
        $DetailsOrder = DetailsOrder::where('order_id', $request->order_ref)->orderBy('id', 'DESC')->get();
        $total = $DetailsOrder->sum('total');
        $totalProfit = $DetailsOrder->sum('profit');
        // return dd($request->order_ref);
        return response()->json([
            'DetailsOrder' => $DetailsOrder,
            'total' => $total . " DH",
            'totalProfit' => $totalProfit . " DH",
        ]);
    }


    public function validation($id)
    {
        //dd($request->id);
        $order = Order::where('id', '=', $id)->first();
        //dd($order);
        // dd($order->validation);
        if ($order->validation == 0) {
            $order->update([
                'validation' => 1,
            ]);
        } elseif ($order->validation == 1) {
            $order->update([
                'validation' => 0,
            ]);
        }
        return redirect()->back()->with(['success' => 'Validation avec success']);
    }

    public function router(Request $request)
    {
        $new_Qnt  = $request->input_QNT_router;
        $ID_tedailOrder = $request->input_id_detailOrder_router;
        if ( $new_Qnt > 0){
            $UpdatedDetailsOrder = DetailsOrder::findOrFail( $ID_tedailOrder);
            $oldQnt=$UpdatedDetailsOrder->QNT;

            Tracking::create([
                'orderDetailId'=>$ID_tedailOrder,
                'oldQuantity'=>$oldQnt,
                'newQuantity'=>$new_Qnt,
                'type'=>1,
                'userId'=>auth()->user()->id
                ]);
            $UpdatedDetailsOrder->update([
                'QNT' => $new_Qnt
            ]);
            
            return response()->json(['code' => 1, 'msg' => 'La mise à jour avec succée']);
        }
    }

    public function IncoicePDF($id)
    {
        $Order = Order::where('ref', "=", $id)->first();
        $companie = Company::first();
        $DetailsOrder = DetailsOrder::where('order_id', '=', $Order->ref)->get();
        $data['total'] = $DetailsOrder->sum('total');
        $data['totalProfit'] = $DetailsOrder->sum('profit');
        //for order
        $data['Referance_order'] = $Order->ref;
        $data['date_order'] = $Order->created_at;
        $data['payment_method'] = $Order->payment_method;

        //for client
        $data['client'] = $Order->client->name;
        $data['client_ICE'] = $Order->client->ICE;
        $data['client_addresse'] = $Order->client->addresse;
        $data['client_phone'] = $Order->client->phone;

        //for companie
        $data['company_name'] = $companie->name;
        $data['GSM'] = $companie->GSM;
        $data['phone'] = $companie->phone;
        $data['addresse'] = $companie->addresse;
        $data['email'] = $companie->email;
        $data['logo'] = $companie->logo;
        $data['ICE'] = $companie->ICE;

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

                    .heading td {
                        border: 1px solid #eee;
                    }

                    body h1 {
                        font-weight: 300;
                        margin-bottom: 0px;
                        padding-bottom: 0px;
                        color: #000;
                    }

                    body h3 {
                        font-weight: 300;
                        margin-top: 10px;
                        margin-bottom: 20px;
                        font-style: italic;
                        color: #555;
                    }

                    body a {
                        color: #06f;
                    }

                    .invoice-box {
                        max-width: 800px;
                        margin: auto;
                        padding: 15px;
                        font-size: 16px;
                        line-height: 24px;
                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                        color: #555;
                    }

                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                        border-collapse: collapse;
                    }

                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }

                    .invoice-box table tr td:nth-child(2) {
                        text-align: right;
                    }

                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }

                    .invoice-box table tr.top table td.title {
                        font-size: 45px;
                        line-height: 45px;
                        color: #333;
                    }

                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }

                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }

                    .invoice-box table tr.details td {
                        padding-bottom: 20px;
                    }

                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }

                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }

                    .invoice-box table tr.total td:nth-child(2) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }

                    @media only screen and (max-width: 600px) {
                        .invoice-box table tr.top table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }

                        .invoice-box table tr.information table td {
                            width: 100%;
                            display: block;
                            text-align: center;
                        }
                    }
                </style>
            </head>

            <body>
                <div class="invoice-box">
                    <table>
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

                        <tr class="information">
                            <td colspan="4">
                                <table >
                                    <tr>
                                        <td>
                                            <div style="border: 1px solid #eee; text-align:left; padding:5px; height:120px;">
                                                <h2 style="border-bottom:1px solid #eee;">Commande livraison</h2>
                                                <strong>Réfé</strong> : ' . $data['Referance_order'] . '<br />
                                                <strong>Date</strong> : ' . date('d-m-Y', strtotime($data['date_order'])) . '<br />
                                            </div>
                                        </td>
                                        <td>
                                            <div style="border: 1px solid #eee; text-align:left; padding:5px;height:120px;">
                                                <strong>Client</strong> : <strong>' . $data['client'] . '</strong><br />
                                                <strong>GSM</strong> : ' . $data['client_phone'] . '<br />
                                                <strong>Adresse</strong> : ' . $data['client_addresse'] . '<br />
                                                <strong>ICE</strong> : ' . $data['client_ICE'] . '
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>Produit</td>
                            <td>Qté</td>
                            <td>Price</td>
                            <td>Percentage %</td>
                            <td>Profit</td>
                            <td>Total</td>
                        </tr>';

        foreach ($DetailsOrder as $product) {
            $output .= '<tr>
                                <td>' . $product["product_name"] . '</td>
                                <td>' . $product["QNT"] . '</td>
                                <td>' . $product["price"] . ' DH</td>
                                <td>' . $product["percentage"] . ' %</td>
                                <td>' . $product["profit"] . ' DH</td>
                                <td>' . $product["total"] . ' DH</td>
                            </tr>';
        }
        $output .= '
                        <tr class="heading">
                            <td>Total</td>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td>' . number_format((float)$data['totalProfit'], 2, '.', '') . ' DH</td>
                            <td>' . number_format((float)$data['total'], 2, '.', '') . ' DH</td>
                        </tr>

                    </table>
                </div>
                <div style="position:absolute; bottom:5%;text-center:center; width:100%;">
                <hr>
                    ' . $data['addresse'] . '<br>
                    ' . \Carbon\Carbon::now() . '
                </div>

            </body>
        </html>';
        $dompdf->loadHtml($output);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser

        $dompdf->stream();

        //Storage::put('public/csv/name.pdf',$content);
    }
    public function IncoicePDFMini($id)
    {
        $Order = Order::where('ref', "=", $id)->first();
        
        // total credit
        // $totalCredit=Order::where('client_id',$Order->client_id)->selectRaw('sum(total-payer) as credit')->first()->toArray();
        $credit_an=$Order->credit;
        $companie = Company::first();
        $DetailsOrder = DetailsOrder::where('order_id', '=', $Order->ref)->get();
        $data['total'] = $DetailsOrder->sum('total');
        $data['totalProfit'] = $DetailsOrder->sum('profit');
        
        //for order
        $data['Referance_order'] = $Order->ref;
        $data['date_order'] = $Order->created_at;
        $data['payment_method'] = $Order->payment_method;
        $data['rest'] = $Order->total - $Order->payer;
        $data['payer'] = $Order->payer;

        //for client
        $data['client'] = $Order->client->name;
        $data['client_ICE'] = $Order->client->ICE;
        $data['client_addresse'] = $Order->client->addresse;
        $data['client_phone'] = $Order->client->phone;
        // $data['credit'] = $totalCredit['credit'];
        $data['credit_an'] = $credit_an;
        // $data['showCredit']=$credit;

        //for companie
        $data['company_name'] =null ;
        $data['GSM']=null  ;
        $data['phone']=null ;
        $data['addresse'] =null ;
        $data['email']=null ;
        $data['logo']=null;
        $data['ICE'] =null;
        if (isset($companie)){
            $data['company_name'] = $companie->name;
            $data['GSM'] = $companie->GSM;
            $data['phone'] = $companie->phone;
            $data['addresse'] = $companie->addresse;
            $data['email'] = $companie->email;
            $data['logo'] = $companie->logo;
            $data['ICE'] = $companie->ICE;
            
        }

        //for products

      return view('Dashboard.admin.Order_PDF.mini_Pdf', with([
                'data'=>$data,
                'DetailsOrder'=>$DetailsOrder,
                'companie'=>$companie,
                'orders'=>$Order,
                ]
            ));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::where('user_id', auth()->user()->id)->orderBy('name', 'asc')->get();
        return view("Dashboard.admin.orders.create", compact('clients'));
    }

    public function fetchDataProductSession()
    {
        $productSession = productsession::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'productSession' => $productSession,
        ]);
    }

    public function DeleteDataProductSession(Request $request)
    {
        $productS = productsession::where('product_ref', "=", $request->id)->where('user_id', auth()->user()->id)->first();
        if ($productS != null) {
             $product = Product::where('ref', $request->id)->first() ;
             $product->update(['QNT'=>$product->QNT   + $productS->QNT ]);
            $productS->delete();
            return response()->json(['code' => 1, 'msg' => 'Bien supprimé']);
        }

        return response()->json(['code' => 2, 'msg' => 'Wrong ID!!']);
    }

    //Function to get Total price of product
    public function fetchTotalProductSession()
    {
        $product = productsession::where('user_id', auth()->user()->id)->sum('total');
        return $product;
    }

    public function UpdateDataProductSession(Request $request)
    {
      
        $productSession = productsession::where('product_ref', "=", $request->product_ref)->where('user_id', auth()->user()->id)->first();
        $oldQnt=  $productSession->QNT ; 

        //incrisse Qnt product
        $product = Product::where('ref', $request->product_ref)->first();

        if (  $product->QNT   - (   $request->valueQNT -  $oldQnt  )  < 0 )  {

            return response()->json(['code' => 2, 'msg' => 'Vous avez saisie une quatité supérieur']);
        } else {
            $productSession->update([
                'QNT' => $request->valueQNT,
                'total' => $productSession->peice * $request->valueQNT,
                'profit' => ($productSession->peice * $request->valueQNT * $productSession->percentage) / 100,
            ]);
            $product->update([
                'QNT' => $product->QNT + ($oldQnt  -  $request->valueQNT) ,
            ]);
        }
        return response()->json(['code' => 1, 'msg' => 'Update avec succée']);
    }

    public function UpdatePriceProductSession(Request $request)
    {
        $productSession = productsession::where('product_ref', "=", $request->product_ref)->where('user_id', auth()->user()->id)->first();
        $oldQnt=  $productSession->QNT ; 


        //incrisse Qnt product
        $product = Product::where('ref', $request->product_ref)->first();
            $productSession->update([
                'peice' => $request->checkbox_Price,
                'QNT' => $request->valueQNT,
                'total' => $request->checkbox_Price * $request->valueQNT,
                'profit' => ($request->checkbox_Price * $request->valueQNT * $productSession->percentage) / 100,
            ]);

        return response()->json(['code' => 1, 'msg' => 'Update avec succée']);
    }

    public function fetchDataDepot()
    {
        $depots = Depot::all();
        return response()->json([
            'depots' => $depots,
        ]);
    }

    //Function to select client
    public function selectClients(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function orderDetails(string $orderRef)
    {
        $DetailsOrder = DetailsOrder::where('order_id', $orderRef)->orderBy('id', 'DESC')->get();

        $total = $DetailsOrder->sum('total');
        $totalProfit = $DetailsOrder->sum('profit');

        return [
            'DetailsOrder' => $DetailsOrder,
            'total' => $total . " DH",
            'totalProfit' => $totalProfit . " DH",
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        if(!auth()->user()->hasRole('gerant BL|admin|master')) return redirect()->back();
        $orderData = $this->orderDetails($order->ref);

        return view('Dashboard.admin.orders.editorder', [
            'orderData' => $orderData,
            'order' => $order,
            'categories' => Categorie::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasRole('gerant BL|admin|master')) return redirect()->back();
        $order = Order::where('id', $id)->first();
        $DetailsOrder = DetailsOrder::where('order_id', '=', $order->ref)->get();
        $order->delete();
        for ($i = 0; $i < count($DetailsOrder); $i++) {
            $DetailsOrder[$i]->delete();
        }

        if (str_contains(url()->previous(), 'employees/orders'))
            return redirect()->to('employees/orders')->with(['success' => 'La supprission avec success']);
        else
            return redirect()->to('orders')->with(['success' => 'La supprission avec success']);
    }
}
