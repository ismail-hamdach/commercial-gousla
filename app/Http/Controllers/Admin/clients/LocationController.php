<?php

namespace App\Http\Controllers\Admin\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckClient;
use App\Models\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class LocationController extends Controller
{
    public function show(){
        return view("Dashboard.admin.clients.location");
    }
    public function scaned(Request $request){
        try{ 
            $userId =auth()->user()->id ;
            $userCheck = CheckClient::whereRaw('user_id = ?  and  client_id   =   ?  and date(created_at)  =  current_date ' ,  [$userId,$request->clientId])->count() ; 
            if(!$userCheck){
                CheckClient::create([
                'user_id'=>$userId , 
                'client_id'=> $request->clientId
                ]);
            return response()->json(['msg' => "scaned with success"]);
            }
         return response()->json(['msg' => 'alerdy scaned']);
        }catch(\Exception $e ){
            return response()->json(['msg' => $e]);
        }
    }
    public function qrcode(Request $request){
        try{ 
            
                $qrCode = QrCode::size(200)
                    ->format('png')
                    ->generate($request->id, true);
        
                return response($qrCode)->header('Content-Type', 'application/pdf');
        }catch(\Exception $e ){
            return response("error " . $e, 400);
        }
    }
}
