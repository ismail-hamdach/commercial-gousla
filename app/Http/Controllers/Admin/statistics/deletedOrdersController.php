<?php

namespace App\Http\Controllers\Admin\statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class deletedOrdersController extends Controller
{
     public function index()
    {
        // $orders=Order::onlyTrashed()->whereRaw('month(created_at) = month(current_date)')
        // ->join('details_orders', 'details_orders.order_id', '=', 'orders.ref')
            //   ->get()->toArray();
            
            $employee=User::whereHas('roles',function($query){
                $query->whereName('employee');
            })->select('id','name')->get()->toArray();
            // dd($employee);
        return view('Dashboard.admin.statistics.deletedOrders', [
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
         $orders=Order::onlyTrashed()->where(function($query) use($validated){
             if ($validated['startDate']) $query->whereDate('orders.created_at','>=',$validated['startDate']);
             if ($validated['endDate']) $query->whereDate('orders.created_at','<=',$validated['endDate']);
             else $query->whereRaw('month(orders.created_at) = month(current_date)');
             if ($validated['employeeId']) $query->where('orders.user_id',$validated['employeeId']);
         })->join('clients','clients.id','=','client_id')->selectRaw('orders.ref,orders.total,orders.created_at,orders.deleted_at,clients.name')
         
        // ->join('details_orders', 'details_orders.order_id', '=', 'orders.ref')
              ->get()->toArray();
       
        return response()->json($orders);
    }
}
