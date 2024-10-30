<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailsOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeesAdminOperations extends Controller
{
    public function showStatistics(Request $request)
    {
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;
        $user_id = $request->user_id;

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->get();

        if ($date_debut != null && $date_fin != null) {
            if ($date_fin == null) {
                $date_fin = Carbon::now()->format('Y-m-d');
            }

            $percentage = '';
            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('m'); // 8

            $totalOrder = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->count();

            $totalOrderInvalid = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->where('validation', '=', 0)->count();

            $profit = DetailsOrder::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('profit');

            $totalOrdervalid = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->where('validation', '=', 1)->count();

            $Turnover = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('total');

            $TurnoverlastMonth = DetailsOrder::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->sum('total');

            if ($TurnoverlastMonth > 0) {
                $percentage = ($Turnover - $TurnoverlastMonth) / $TurnoverlastMonth;
            } else {
                $percentage = 0;
            }

            $Products = Product::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->count();
            $clients = Client::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->whereBetween('created_at', [$date_debut, $date_fin])->count();
            $Orders = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->latest()->take(5)->get();



            return view('Dashboard.employees.statistics', compact('totalOrder', 'Turnover', 'Products', 'clients', 'Orders', 'percentage', 'totalOrderInvalid', 'totalOrdervalid', 'profit', 'users'));
        } else {
            $percentage = '';
            $date = \Carbon\Carbon::now();
            $lastMonth =  $date->subMonth()->format('m'); // 8

            $totalOrder = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->count();

            $totalOrderInvalid = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->where('validation', '=', 0)->count();

            $profit = DetailsOrder::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->sum('profit');

            $totalOrdervalid = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->where('validation', '=', 1)->count();

            $Turnover = DetailsOrder::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->sum('total');

            $TurnoverlastMonth = DetailsOrder::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->sum('total');

            if ($TurnoverlastMonth > 0)
                $percentage = ($Turnover - $TurnoverlastMonth) / $TurnoverlastMonth;
            else
                $percentage = 0;

            $Products = Product::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->count();

            $clients = Client::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->count();

            $Orders = Order::where(function (Builder $query) use ($user_id) {
                if ($user_id != 0) $query->where('user_id', $user_id);
            })->where('user_id', '<>', auth()->user()->id)->latest()->take(5)->get();

            return view('Dashboard.employees.statistics', compact('totalOrder', 'Turnover', 'Products', 'clients', 'Orders', 'percentage', 'totalOrderInvalid', 'totalOrdervalid', 'profit', 'users'));
        }
    }

    public function allOrders(Request $request)
    {
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;
        $user_id = !is_null($request->user_id) ? $request->user_id : 0;

        $orders = Order::where(function (Builder $query) use ($date_debut, $date_fin) {
            if ($date_debut != null && $date_fin != null) $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$date_debut, $date_fin]);
        })->where(function (Builder $query) use ($user_id) {
            if ($user_id != 0) $query->where('user_id', $user_id);
        })->where('user_id', '<>', auth()->user()->id)->orderBy('id', 'DESC')->get();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->get();

        return view("Dashboard.employees.allorders", compact('orders', 'users', 'user_id'));
    }

    public function listOfClients(Request $request)
    {
        $date_debut = $request->date_debut;
        $date_fin = $request->date_fin;
        $user_id = !is_null($request->user_id) ? $request->user_id : 0;

        $clients = Client::where(function (Builder $query) use ($date_debut, $date_fin) {
            if ($date_debut != null && $date_fin != null) $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$date_debut, $date_fin]);
        })->where(function (Builder $query) use ($user_id) {
            if ($user_id != 0) $query->where('user_id', $user_id);
        })->where('user_id', '<>', auth()->user()->id)->get();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->get();

        return view("Dashboard.employees.clients", compact('clients', 'users', 'user_id'));
    }

    public function productsManagement(Request $request)
    {
        $user_id = !is_null($request->user_id) ? $request->user_id : 0;

        $adminIds = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->pluck('id')->toArray();

        $products = Product::where(function (Builder $query) use ($user_id) {
            if ($user_id != null) $query->where('user_id', $user_id);
        })->whereIn('user_id', $adminIds)->get();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->get();

        return view("Dashboard.employees.productsmanagement", compact('products', 'users',));
    }

    public function newProduct()
    {
        return view("Dashboard.employees.createproduct");
    }

    public function saveProduct(Request $request)
    {
        $image_name='gousla.jpeg';
        $validatedData = $request->validate([
            'name' => 'required',
            'ref' => 'unique:products',
            'price' => 'required|numeric',
            'priceAchat' => 'required|numeric',
            'QNT' => 'numeric',
            'percentage' => 'between:0,100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'users' => 'nullable|numeric',
            'categorie' => 'required|numeric',
            'depot' => 'required|numeric',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'QNT.numeric' => 'Quantité doit étre numérique',
            'image.image' => 'image est obligatoire',
            'image.mimes' => 'Saisir juste (jpeg,png,jpg,svg)',
        ]);

        // $image_name = "";

        if ($request->has('image')) {
            $file = $request->image;
            $image_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('product_image'), $image_name);
           
        }

        $name = $request->name;
        $QNT = $request->QNT;
        $percentage = $request->percentage;
        $price = $request->price;
        $priceAchat = $request->priceAchat;
        $categorie_id = $request->categorie;
        $depot_id = $request->depot;

        // create a list of selected user, if no one selected so choose the admin;
        $user_id = isset($validatedData['users']) ? $validatedData['users'] : auth()->user()->id;

        Product::create([
            'name' => $name,
            'QNT' => $QNT,
            'percentage' => $percentage,
            'priceAchat' => $priceAchat,
            'price' => $price,
            'categorie_id' => $categorie_id,
            'depot_id' => $depot_id,
            'image' => $image_name,
            'user_id' => $user_id,
        ]);

        return redirect()->to('/employees/products')->with("success", "l'ajout de produit avec succès");
    }

    public function editProduct($id)
    {
        $product = Product::where('id', $id)->first();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'master']);
        })->get();

        return view("Dashboard.employees.editproduct", compact('product', 'users'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'priceAchat' => 'required|numeric',
            'percentage' => 'between:0,100',
            'QNT' => 'numeric',
            'user' => 'nullable|numeric',
            'depot' => 'nullable|numeric',
            'categorie' => 'nullable|numeric',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'QNT.numeric' => 'Quantité doit étre numérique',
        ]);

        $image_name = "";

        if ($request->has('image')) {
            $file = $request->image;
            $image_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('product_image'), $image_name);
            $product->image = $image_name;
        }

        if ($request->has('ref')) {
            $product->ref = $request->ref;
        }

        $name = $request->name;
        $QNT = $request->QNT;
        $priceAchat = $request->priceAchat;
        $price = $request->price;
        $percentage = $request->percentage;
        $categorie_id = intval($request->categorie);
        $depot_id = intval($request->depot);

        $product->update([
            'name' => $name,
            'QNT' => $QNT,
            'ref' => $product->ref,
            'priceAchat' => $priceAchat,
            'price' => $price,
            'percentage' => $percentage,
            'image' => $product->image,
        ]);

        if ($categorie_id != 0)
            $product->update(['categorie_id' => $categorie_id]);

        if ($depot_id != 0)
            $product->update(['depot_id' => $depot_id]);

        if (intval($validatedData['user']) != 0)
            $product->update(['user_id' => $validatedData['user']]);

        return redirect()->to('/employees/products')->with("success", "la modification avec succès");
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->to('/employees/products')->with(['success' => 'La supprission avec success']);
    }
}
