<?php

namespace App\Http\Controllers\Admin\product;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Depot;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('user_id', auth()->user()->id)->get();

        return view('Dashboard.admin.products.show', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $showUsers = str_contains(url()->previous(), 'employees/products');
        return view("Dashboard.admin.products.create", ['showUsers' => $showUsers]);
    }

    public function fetchDataDepot()
    {
        $depots = Depot::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'depots' => $depots,
        ]);
    }

    public function fetchDataCategoris(Request $request)
    {
        $categories = Categorie::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'ref' => 'unique:products',
            'priceAchat' => 'required|numeric',
            'price' => 'required|numeric',
            'QNT' => 'numeric',
            'percentage' => 'between:0,100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'users' => 'nullable|numeric',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'QNT.numeric' => 'Quantité doit étre numérique',
            'image.image' => 'image est obligatoire',
            'image.mimes' => 'Saisir juste (jpeg,png,jpg,svg)',
        ]);

        $image_name = "gousla.jpeg";

        if ($request->has('image')) {
            $file = $request->image;
            $image_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('product_image'), $image_name);
        }

        $name = $request->name;
        $QNT = $request->QNT;
        $percentage = $request->percentage;
        $priceAchat = $request->priceAchat;
        $price = $request->price;
        $categorie_id = $request->categorie;
        $depot_id = $request->depot;

        // create a list of selected user, if no one selected so choose the admin;
        $user_id = isset($validatedData['users']) ? $validatedData['users'] : auth()->user()->id;


        Product::create([
            'name' => $name,
            'QNT' => $QNT,
            'percentage' => $percentage,
            'price' => $price,
            'priceAchat' => $priceAchat,
            'categorie_id' => $categorie_id,
            'depot_id' => $depot_id,
            'image' => $image_name,
            'user_id' => $user_id,
        ]);


        return redirect()->to('products')->with("success", "l'ajout de produit avec succès");
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
        $product = Product::where('id', $id)->first();
        if ($product->user_id != auth()->user()->id)
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['admin', 'master']);
            })->get();
        else
            $users = [];

        return view("Dashboard.admin.products.edit", compact('product', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'priceAchat' => 'required|numeric',
            'percentage' => 'between:0,100',
            'QNT' => 'numeric',
            'user' => 'nullable|numeric',

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
        $price = $request->price;
        $priceAchat = $request->priceAchat;
        $percentage = $request->percentage;
        $categorie_id = $request->categorie;
        $depot_id = $request->depot;

        $product->update([
            'name' => $name,
            'QNT' => $QNT,
            'ref' => $product->ref,
            'price' => $price,
            'priceAchat' => $priceAchat,
            'percentage' => $percentage,
            'categorie_id' => $categorie_id,
            'depot_id' => $depot_id,
            'image' => $product->image,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->to('products')->with("success", "la modification avec succès");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->to('products')->with(['success' => 'La supprission avec success']);
    }
    public function getProductsByCategory(Request $request)
    {
        try {

            $products = Product::where('categorie_id', $request->cId)->get();

            return new JsonResponse([
                'products' => $products
            ]);
        } catch (Exception $e) {
            return new JsonResponse(null, 400);
        }
    }

    public function getProductsByName(Request $request)
    {
        try {

            $products = Product::where([
                ['categorie_id', '=', $request->cId],
                ['name', 'LIKE', '%' . $request->pName . '%']
            ])->get();

            return new JsonResponse([
                'products' => $products
            ]);
        } catch (Exception $e) {
            return new JsonResponse(null, 400);
        }
    }
}
