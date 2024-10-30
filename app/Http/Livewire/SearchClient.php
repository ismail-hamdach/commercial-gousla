<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Livewire\Component;

class SearchClient extends Component
{
    public $searchTirm='';
    public $clients;

    public function render()
    {
        if(empty($this->searchTirm))
        {
            $this->clients=Client::where('name',$this->searchTirm)->get();
        }else{

            $this->clients=Client::where('name','like','%'.$this->searchTirm."%")->get();
            
        }
        return view('livewire.search-client');
    }

}
