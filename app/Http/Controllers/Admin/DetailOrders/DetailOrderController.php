<?php

namespace App\Http\Controllers\Admin\DetailOrders;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\DetailsOrder;
use App\Models\Tracking;
use Exception;
use Illuminate\Http\Request;
use Mpdf\Tag\Details;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class DetailOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = json_decode($request->data, true);
        $status = 0;
        $productNameException = '';
        $total = 0;
        $items = [];

        DB::beginTransaction();

        try {

            /*
                how data will be processed:
                    + start with new commandes, add them to the Detail Orders, then add each new commande to list of will be printed commandes.
                    + 
            */

            foreach ($data['newCommandes'] as $key => $row) {

                $row = json_decode(json_encode($row), true);

                $newDetailOrder = new DetailsOrder;

                $newDetailOrder->product_name = $row["product_name"];
                $newDetailOrder->order_id = $request["ref"];
                $newDetailOrder->QNT = intval($row["quantity"]);
                $newDetailOrder->price = floatval($row["price"]);
                $newDetailOrder->total = floatval($row["total"]);
                $newDetailOrder->percentage = floatval($row["percentage"]);
                $newDetailOrder->profit = floatval($row["profit"]);
                $newDetailOrder->user_id = auth()->user()->id;

                $newDetailOrder->save();

                $items[] = [
                    'id' => $newDetailOrder->ref,
                    'name' => $newDetailOrder->product_name,
                    'QNT' => $newDetailOrder->QNT,
                    'percentage' => $newDetailOrder->percentage,
                    'peice' => $newDetailOrder->price,
                    'total' => $newDetailOrder->total,
                ];

                $total += floatval($newDetailOrder->total);
                
                
            }

            foreach ($data["oldCommandes"] as $key => $row) {

                $row = json_decode(json_encode($row), true);

                $orderDetail = DetailsOrder::find(
                    intval($row['id'])
                );
                
                $oldQnt=$orderDetail->QNT;

                if ( $oldQnt !=$row['quantity'] ){
                    Tracking::create([
                        'orderDetailId'=> intval($row['id']),
                        'oldQuantity'=>$oldQnt,
                        'newQuantity'=>$row['quantity'],
                        'type'=>1,
                        'userId'=>auth()->user()->id
                        ]);
                
                }
                $orderDetail->QNT = $row['quantity'];
                $orderDetail->total = $row['total'];
                $orderDetail->profit = $row['profit'];

                $orderDetail->save();

                $productMatching = Product::where('name', $orderDetail->product_name)->first();
                
                if (!$productMatching) {
                    
                    $productNameException = $orderDetail->product_name;
                    DB::rollback();
                    break;
                    
                }

                $items[] = [
                    'id' => $productMatching->ref,
                    'name' => $orderDetail->product_name,
                    'QNT' => $orderDetail->QNT,
                    'percentage' => $orderDetail->percentage,
                    'peice' => $orderDetail->price,
                    'total' => $orderDetail->total,
                ];

                $total += floatval($row['total']);
            }
            
            // if any exception is gather the commit of the update query to the  database would not happen
            if ($productNameException == '') {
                DB::commit();
                $status = 1;
            }

            
        } catch (Exception $e) {
            DB::rollback();
        }

        if ($status) {

            $relatedOrder = Order::where('ref', $request->ref)->first();
            $relatedOrder->total = $total;

            $relatedOrder->save();

            $details = [];

            $entrepris = [];
            
            foreach (Company::get() as $entrepri) {
                $entrepris[] = [
                    'name_entrepri' => $entrepri->name,
                    'GSM_entrepri' => $entrepri->GSM,
                    'addresse_entrepri' => $entrepri->addresse,
                    'phone_entrepri' => $entrepri->phone,
                    'ICE' => $entrepri->ICE,
                    'email' => $entrepri->email,
                    'logo' => $entrepri->logo,
                    'RC' => $entrepri->RC,
                    'IF' => $entrepri->IF,
                    'TP' => $entrepri->TP,
                ];
            }

            $clients = [];
            foreach (Client::where('id', $relatedOrder->client_id)->get() as $client) {

                $clients[] = [
                    'id_client' => $client->id,
                    'name_client' => $client->name,
                    'addresse_client' => $client->addresse,
                    'phone_client' => $client->phone,
                    'ICE' => $client->ICE,
                    'email' => $client->email,
                ];
            }

            $details['entrepris'] = $entrepris;
            $details['items'] = $items;
            $details['clients'] = $clients;
            $details['orders'] = $relatedOrder;

            /* $pdf = PDF::loadView('Dashboard.admin.Order_PDF.order_Pdf', $details, [
                'title' => 'PDF Title',
                'author' => 'PDF Author',
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 40,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
                'showImageErrors' => true
            ]);

            $path = Storage::put('public/storage/InvoiceEmail/' . rand() . '-' . time() . '.' . 'pdf', $pdf->output());
            Storage::put($path, $pdf->output()); */
            
           /*  Mail::send('Dashboard.admin.email.invoice', $details, function ($message) use ($path, $pdf, $details) {
                $message->from('contact@gousla.com', env('APP_NAME'));
                $message->to($details['orders']->depot_email)->subject('subject')
                    ->attachData($pdf->output(), $path, [
                        'mime' => 'application/pdf',
                        'as' => 'aziz.pdf'
                    ]);
            }); */

            return new JsonResponse([
                'message' => 'Order Details Updated with Success!',
            ]);
            
        } else if ($status == 0) {
            return new JsonResponse([
                'message' => 'Product named ' . $productNameException . ' Not Found!',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Error When try To Modify the Detail of Commande!'    
        ], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $detailOrder = DetailsOrder::findOrFail($request->dOID);
            $totalOrderDetail = $detailOrder->total;

            $relatedOrder = Order::where('ref', $detailOrder->order_id)->first();
            $relatedOrder->total -= floatval($totalOrderDetail);

            $relatedOrder->save();
            Tracking::create([
                 'orderDetailId'=>$request->dOID,
                'oldQuantity'=>$detailOrder->QNT,
                'newQuantity'=>$detailOrder->QNT,
                'type'=>2,
                'userId'=>auth()->user()->id
                ]);
            $detailOrder->delete();

            return redirect()->back()->with('success', 'The Selected Order Detail is Deleted!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', "can't delete the selected order detail!");
        }
    }
}
