<?php

namespace App\Http\Controllers\Admin\statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Tracking;

class trackingController extends Controller
{
      public function index()
    {
         
            $employee=User::whereHas('roles',function($query){
                $query->whereName('employee');
            })->select('id','name')->get()->toArray();
            // dd($employee);
        return view('Dashboard.admin.statistics.trackings', [
            'employee' =>$employee,
        ]);
    }
    public function search(Request $request)
    {
        $validated=$request->validate([
            '_token'=> 'required|string',
                    'employeeId'=>"nullable|numeric",
                    'startDate'=>"nullable|date",
                    'endDate'=> "nullable|date",
                    ]);
         $orders=Tracking::where(function($query) use($validated){
             if ($validated['startDate']) $query->whereDate('details_orders.created_at','>=',$validated['startDate']);
             if ($validated['endDate']) $query->whereDate('details_orders.created_at','<=',$validated['endDate']);
             else $query->whereRaw('month(details_orders.created_at) = month(current_date)');
             if ($validated['employeeId']) $query->where('details_orders.user_id',$validated['employeeId']);
         })->join('details_orders','details_orders.id','=','trackings.orderDetailId')
         ->groupByRaw('trackings.orderDetailId') 
         ->selectRaw('details_orders.order_id as ref,details_orders.id,details_orders.product_name as product,trackings.oldQuantity,trackings.created_at,trackings.newQuantity')->get()->toArray();
       
        return response()->json($orders);
    }
    public function details($id)
    {
        
         $orders=Tracking::where('orderDetailId',$id)
         ->join('details_orders','details_orders.id','=','trackings.orderDetailId')
         ->join('users','users.id','=','trackings.userId')
         ->selectRaw('details_orders.order_id as ref,users.name as user,
         details_orders.product_name as product,trackings.oldQuantity,
         trackings.created_at,trackings.newQuantity,
         case trackings.type  when 1 then "Modifier" when 2 then "Supprimé" end as type')
         
        ->get()->toArray();
       
        return response()->json($orders);
    }
}
