<?php

namespace App\Http\Controllers;

use App\Mail\Admin\email\SendEmail;
use App\Models\Client;
use App\Models\Company;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\productsession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Arr;

class invoiceController extends Controller
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
        $totalGlobal = 0;
        if ($request->clients == null) {
            return redirect()->back()->with('error', "Choisissez Le client SVP !");
        }

        if ($request->Send_invoice == "Send_invoice") {
            $Quantity = 5;
            for ($i = 0; $i < $Quantity; $i++) {
                $ref_code = 'Ab_' . Str::random($length = 11);
            }

            $order = Order::create([
                // 'ref' => $ref_code,
                'client_id' => $request->clients,
                'payment_method' => $request->MethodPayment,
                'depot_email' => $request->depotOrder,
                'total' => $request->total,
                'user_id' => auth()->user()->id,
            ]);

            $productsession = productsession::where('user_id', auth()->user()->id)->select('id','priceAchat')->get()->toArray(); 
            for ($i = 0; $i < count($productsession); $i++) {
                $DetailsOrder = DetailsOrder::create([
                    'product_name' => $request->name_product_added[$i],
                    'order_id' => $order->ref,
                    'QNT' => $request->QNT_product_added[$i],
                    'percentage' => $request->percentage_product_added[$i],
                    'price' => $request->price_product_added[$i],
                    'priceAchat' => $productsession[$i]['priceAchat'],
                    'total' => $request->QNT_product_added[$i] * $request->price_product_added[$i],
                    'profit' => ($request->QNT_product_added[$i] * $request->price_product_added[$i] * $request->percentage_product_added[$i]) / 100,
                    'user_id' => auth()->user()->id,
                ]);
            }

            $items = [];

            foreach (productsession::where('user_id', auth()->user()->id)->get() as $productsessionAll) {
                $items[] = [
                    'id' => $productsessionAll->product_ref,
                    'name' => $productsessionAll->name,
                    'QNT' => $productsessionAll->QNT,
                    'percentage' => $productsessionAll->percentage,
                    'peice' => $productsessionAll->peice,
                    'total' => $productsessionAll->total,
                ];
            }

            $clients = [];
            
            foreach (Client::where('id', $request->clients)->get() as $client) {

                $clients[] = [
                    'id_client' => $client->id,
                    'name_client' => $client->name,
                    'addresse_client' => $client->addresse,
                    'phone_client' => $client->phone,
                    'ICE' => $client->ICE,
                    'email' => $client->email,
                ];
            }
            //dd(Order::where('ref','=',$ref_code)->first());
            //    $orders=[];
            //     foreach(Order::where('ref','=',$ref_code)->first() as $order){
            //         $orders[]=[
            //            //'ref' =>$order->ref,
            //            'depot_email' =>$order->depot_email,
            //        ];
            //    }

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

            $details['entrepris'] = $entrepris;
            $details['items'] = $items;
            $details['clients'] = $clients;
            $details['orders'] = Order::where('ref', '=', $order->ref)->first();
             
           /*  $pdf = PDF::loadView('Dashboard.admin.Order_PDF.order_Pdf', $details, [
                'title' => 'PDF Title',
                'author' => 'PDF Author',
                'showImageErrors' => true,
            ]);  
            
                // $pdf->getMpdf()->setFooter('Dicetak dari Aplikasi '.config('settings.app_name').'||Halaman {PAGENO} dari {nbpg}');

            $path = Storage::put('public/storage/InvoiceEmail/' . rand() . '-' . time() . '.' . 'pdf', $pdf->output());
            Storage::put($path, $pdf->output());
           
            Mail::send('Dashboard.admin.email.invoice',$details,function($message) use($path,$pdf,$details){ 
                    $message->from('test@gousla.com',env('APP_NAME'));
                    $message->to($details['orders']->depot_email)->subject('subject')
                    ->attachData($pdf->output(),$path,[
                        'mime' => 'application/pdf',
                        'as' =>'aziz.pdf'
                    ]);
                });
 */
            productsession::where('user_id', auth()->user()->id)->truncate();
            return redirect()->back()->with('success', "La commande envoyé avec succée");
        }

        return redirect()->back();
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
        //
    }
}
