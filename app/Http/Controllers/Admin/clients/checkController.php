<?php

namespace App\Http\Controllers\Admin\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\CheckClient;
use App\Models\User;
class checkController extends Controller
{
    public function index (){
        $clients = Client::where(function($query)   {
            if(!auth()->user()->hasRole('master|admin'))   $query->where('user_id',auth()->user()->id );
        })->pluck('name','id')->toArray();
        $employyes = User::where(function($query)   {
            if(!auth()->user()->hasRole('master|admin'))   $query->where('id',auth()->user()->id );
        })->pluck('name','id')->toArray();
 
        return view("Dashboard/admin/clients/check-clients" , compact('employyes','clients'));
    }
   
    public function checkClient(Request $request)
    {
        $client= $request->client;
        $employee= $request->employee;
        $startDate= $request->startDate;
        $endDate=$request->endDate;
        $checkClient   =  CheckClient::join('clients','check_clients.client_id','=','clients.id')->join('users','check_clients.user_id','=','users.id')
        ->where(function($query) use ($startDate , $endDate , $client , $employee)  {
             if ($startDate && $endDate) $query->whereRaw('DATE(check_clients.created_at) BETWEEN ? AND ?', [$startDate, $endDate]);
             else  $query->whereRaw('DATE(check_clients.created_at) =  current_date' );
             if($client)
                $query->where('check_clients.client_id', $client);
             if($employee)
                $query->where('check_clients.user_id',$employee);
            })->selectRaw('users.name as user , clients.name as client ,check_clients.created_at as date ')->get()->toArray();
         return response()->json($checkClient);
    }
}
