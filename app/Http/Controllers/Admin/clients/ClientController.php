<?php

namespace App\Http\Controllers\Admin\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientsRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $clients = Client::where('user_id', auth()->user()->id)->get();

        return view("Dashboard.admin.clients.show", compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Dashboard.admin.clients.create");
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
            'name' => 'required|min:5|max:30',
            'addresse' => 'required',
            'Longitude' => 'required|numeric',
            'Longitude' => 'required|numeric',
            'email' => 'email|nullable',
            'phone' => 'required|numeric',
            'ICE' => 'numeric|nullable'
        ], [
            'name.required' => 'Le nom est obligatoire',
            'addresse.required' => 'L\'adresse est obligatoire',
            'Téléphone.required' => 'Téléphone est obligatoire',
            'ICE.numeric' => 'ICE doit étre numérique'
        ]);

        $client = new Client();

        $client->name = $request->name;
        $client->addresse = $request->addresse;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->ICE = $request->ICE;
        $client->Latitude = $request->Latitude;
        $client->Longitude = $request->Longitude;
        $client->user_id = auth()->user()->id;

        $client->save(); 
        return redirect()->to('clients')->with("success", "l'ajout avec succès");
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
        $client = Client::where('id', $id)->first();
        return view('Dashboard.admin.clients.edit', compact('client'));
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
        $client = Client::find($id);

        $validatedData = $request->validate([
            'name' => 'required|min:5|max:30',
            'addresse' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'ICE' => 'numeric'
        ], [
            'name.required' => 'Le nom est obligatoire',
            'addresse.required' => 'L\'adresse est obligatoire',
            'email.required' => 'E-mail est obligatoire',
            'Téléphone.required' => 'Téléphone est obligatoire',
            'ICE.numeric' => 'ICE doit étre numérique'
        ]);

        $name = $request->name;
        $email = $request->email;
        $addresse = $request->addresse;
        $phone = $request->phone;
        $ICE = $request->ICE;
        $Latitude = $request->Latitude;
        $Longitude = $request->Longitude;

        $client->update([
            'name' => $name,
            'Longitude' => $Latitude,
            'Latitude' => $Latitude,
            'email' => $email,
            'addresse' => $addresse,
            'phone' => $phone,
            'ICE' => $ICE,
        ]);
        return redirect()->to('clients')->with(['success' => 'La modification avec success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->to('clients')->with("success", "la supprission avec succès");
    }
}
