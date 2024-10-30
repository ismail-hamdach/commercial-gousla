<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\productsession;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function SetSessionProduct(Request $request)
    {
        $product = Product::where('id',$request->idProduct)->first();
        $productsession=productsession::where('product_ref',"=", $request->refProduct)->where('user_id', auth()->user()->id)->get();

        if($productsession->count() <= 0){
            if($product->QNT > 0){
                $product->QNT  -= 1 ;
                $product->save() ; 
                productsession::create([
                    'id' => $request->idProduct,
                    'product_ref' => $request->refProduct,
                    'name' => $request->nameProduct,
                    'priceAchat' => $product->priceAchat,
                    'peice' => $request->priceProduct,
                    'percentage' => $request->percentageProduct,
                    'total' => $request->priceProduct,
                    'profit' => ($request->priceProduct * $request->percentageProduct)/100,
                    'user_id' => auth()->user()->id,
                ]);

               return response()->json(['code'=> 1,'msg' => 'Bien ajouté']);

            }else{
                return response()->json(['code'=> 3,'msg' => 'Ce produit n\'est plus en stock !']);
            }

        }else{

            //return response()->json(['code'=> 2,'msg' => 'déjà!']);

        }


        // //  $request->session()->put("id",$request->idProduct);
        // $request->session()->put('user', ['id' => $request->idProduct, 'name' => $request->nameProduct, 'price' => $request->priceProduct]);
        // $value = $request->session()->get('user')['id'];
        // echo $value;
    }
}
