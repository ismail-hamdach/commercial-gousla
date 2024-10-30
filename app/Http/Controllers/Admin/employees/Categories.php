<?php

namespace App\Http\Controllers\Admin\employees;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class Categories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['employee']);
        })->get();

        $user_id = $request->user_id;

        $categoriesOfEmployees = Categorie::with(['user'])->where(function (Builder $query) use ($user_id) {
            $query->whereHas('user', function (Builder $query) use ($user_id) {
                $query->whereHas('roles', function (Builder $query) use ($user_id) {
                    $query->whereIn('name', ['employee']);
                })->where(function (Builder $query) use ($user_id) {
                    if ($user_id != null) $query->where('id', $user_id);
                });
            });
        })->get();

        return view('Dashboard.employees.categories.categories', [
            'users' => $users,
            'categories' => $categoriesOfEmployees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['employee']);
        })->get();

        return view('Dashboard.employees.categories.create', [
            'users' => $users,
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
            'name' => 'required|min:3|unique:categories,name',
            'user' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            Categorie::create([
                'name' => $validatedData['name'],
                'user_id' => $validatedData['user'],
            ]);

            DB::commit();

            return redirect(route('employeescategories.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur dans la base de données');
        }
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
        $categorie = Categorie::find($id);
        $selectEmployees = Categorie::with(['user'])->where('name', $categorie->name)->get()->pluck('user.id')->toArray();

        $possibleEmployees = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['employee']);
        })->whereNotIn('id', $selectEmployees)->get();

        return view('Dashboard.employees.categories.edit', [
            'categorie' => $categorie,
            'possibleEmployees' => $possibleEmployees,
        ]);
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
        $validatedData = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'user' => 'nullable|numeric',
        ]);

        try {
            $categorie =  Categorie::find($id);

            $categorie->update([
                'name' => $validatedData['name'],
            ]);

            if (intval($validatedData['user']))
                $categorie->update(['user_id' => $validatedData['user']]);

            return redirect(route('employeescategories.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur dans la base de données');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Categorie::find($id)->delete();
            
            return redirect(route('employeescategories.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur dans la base de données');
        }
    }

    public function categoriesOfEmployees($id) {
        $categories = Categorie::where('user_id', $id)->get(['id', 'name']);

        return response()->json([
            'categories' => $categories,
        ]);
    }
}
