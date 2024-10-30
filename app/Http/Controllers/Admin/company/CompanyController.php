<?php

namespace App\Http\Controllers\Admin\company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companys=Company::all();
        return view('Dashboard.admin.company.show',compact('companys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dashboard.admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData=$request->validate([

            'name' => 'required',
            'addresse' => 'required',
            'email' => 'required|email',
            'GSM' => 'numeric',
            'ICE' => 'required|numeric',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048'
        ],[
            'name.required' => 'Le nom est obligatoire',
            'addresse.required' => 'L\'adresse est obligatoire',
            'email.required' => 'E-mail est obligatoire',
            'Téléphone.required' => 'Téléphone est obligatoire',
            'ICE.numeric' => 'ICE doit étre numérique',
            'logo.image' => 'Logo est obligatoire',
            'logo.mimes' => 'Saisir juste (jpeg,png,jpg,svg)',
        ]);
        
        $image_name="";
        if($request->has('logo'))
        {
            $file=$request->logo;
            $image_name=time().'_'.$file->getClientOriginalName();
            $file->move(public_path('Company_logo'),$image_name);
        }
        $name = $request->name;
        $addresse = $request->addresse;
        $GSM = $request->GSM;
        $phone = $request->phone;
        $email = $request->email;
        $ICE = $request->ICE;
        $RC = $request->RC;
        $IF = $request->IF;
        $TP = $request->TP;

        $company=Company::create([
            'name' => $name,
            'addresse' => $addresse,
            'GSM' => $GSM,
            'phone' => $phone,
            'email' => $email,
            'ICE' => $ICE,
            'logo' =>$image_name,
            'TP' => $TP,
            'RC' => $RC,
            'IF' => $IF,
        ]);
       
        return redirect()->to('companys')->with("success","l'ajout avec succès");
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
        $company=Company::where('id',$id)->first();
        return view("Dashboard.admin.company.edit",compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validatedData=$request->validate([

            'name' => 'required',
            'addresse' => 'required',
            'email' => 'required|email',
            'GSM' => 'numeric',
            'ICE' => 'required|numeric',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ],[
            'name.required' => 'Le nom est obligatoire',
            'addresse.required' => 'L\'adresse est obligatoire',
            'email.required' => 'E-mail est obligatoire',
            'Téléphone.required' => 'Téléphone est obligatoire',
            'ICE.numeric' => 'ICE doit étre numérique',
            'logo.mimes' => 'Saisir juste (jpeg,png,jpg,svg)',
        ]);

        $image_name="";
        if($request->has('logo'))
        {
            $file=$request->logo;
            $image_name=time().'_'.$file->getClientOriginalName();
            $file->move(public_path('Company_logo'),$image_name);
            $company->logo=$image_name;
        }

        $name = $request->name;
        $addresse = $request->addresse;
        $GSM = $request->GSM;
        $phone = $request->phone;
        $email = $request->email;
        $ICE = $request->ICE;
        $RC = $request->RC;
        $IF = $request->IF;
        $TP = $request->TP;

        $company->update([
            'name' => $name,
            'addresse' => $addresse,
            'GSM' => $GSM,
            'phone' => $phone,
            'email' => $email,
            'ICE' => $ICE,
            'logo' =>$company->logo,
            'TP' => $TP,
            'RC' => $RC,
            'IF' => $IF,
        ]);
       
        return redirect()->to('companys')->with("success","la modification avec succès");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        $image_path = public_path('Company_logo')."/".$company->logo; 

        if (file_exists($image_path)) {
            @unlink($image_path);
        }
        return redirect()->to('companys')->with(['success' => 'La supprission avec success']);
    }
}
