<?php

namespace App\Http\Livewire;

use App\Models\Categorie;
use App\Models\Product;
use Livewire\Component;

class SearchProduct extends Component
{
    public $searchTirm = '';
    public $products;
    public $categ;
    public function render()
    {
        //$categories = Categorie::all();
        $categories = Categorie::where('user_id', auth()->user()->id)->get(); 
        if (empty($this->searchTirm) &&  $this->categ ) {
            // $this->products=Product::all()->take(30);
            $this->products = Product::where('categorie_id', $this->categ)->where('user_id', auth()->user()->id)->take(16)->get();
        } elseif (  $this->categ) {

            $this->products = Product::where([
                ['name', 'like', '%' . $this->searchTirm . "%"],
                ['categorie_id', '=', $this->categ],
                ['user_id', '=', auth()->user()->id]
            ])->take(16)->get();

        } else {
            $this->products = Product::where(
                function($query) {
                    if(!empty($this->searchTirm))
                        $query->where([
                            ['name', 'like', '%' . $this->searchTirm . "%"], 
                            ['user_id', '=', auth()->user()->id]
                        ]);
                    
                }
            )->where('user_id', auth()->user()->id)->latest()
            // ->take(16)
            ->get();
        }
        return view('livewire.search-product', compact('categories'));
    }
}
