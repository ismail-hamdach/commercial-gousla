<?php

namespace App\Http\Controllers\Admin\employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Stocks extends Controller
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

        $stockOfEmployees = Depot::with(['user'])->where(function (Builder $query) use ($user_id) {
            $query->whereHas('user', function (Builder $query) use ($user_id) {
                $query->whereHas('roles', function (Builder $query) use ($user_id) {
                    $query->whereIn('name', ['employee']);
                })->where(function (Builder $query) use ($user_id) {
                    if ($user_id != null) $query->where('id', $user_id);
                });
            });
        })->get();

        return view('Dashboard.employees.stocks.stock', [
            'users' => $users,
            'stocks' => $stockOfEmployees,
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

        return view('Dashboard.employees.stocks.create', [
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
            'name' => 'required|min:3|unique:depots,name',
            'address' => 'required|string|min:3',
            'email' => 'required|email',
            'user' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            Depot::create([
                'name' => $validatedData['name'],
                'addresse' => $validatedData['address'],
                'email' => $validatedData['email'],
                'user_id' => $validatedData['user'],
            ]);

            DB::commit();

            return redirect(route('employeesstocks.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('employeesstocks.index'))->with('error', "Erreur dans l'insertion a la base de données");
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
        $stock = Depot::find($id);
        $selectEmployees = Depot::with(['user'])->where('name', $stock->name)->get()->pluck('user.id')->toArray();

        $possibleEmployees = User::whereHas('roles', function (Builder $query) {
            $query->whereIn('name', ['employee']);
        })->whereNotIn('id', $selectEmployees)->get();

        return view('Dashboard.employees.stocks.edit', [
            'stock' => $stock,
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
            'address' => 'required|string|min:3',
            'email' => 'required|email',
            'user' => 'nullable|numeric',
        ]);

        try {
            $depot = Depot::find($id);

            $depot->update([
                'name' => $validatedData['name'],
                'addresse' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            if (intval($validatedData['user']))
                $depot->update(['user_id' => $validatedData['user']]);

            return redirect(route('employeesstocks.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            return redirect(route('employeesstocks.index'))->with('error', 'Erreur dans la base de données');
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
            Depot::find($id)->delete();

            return redirect(route('employeesstocks.index'))->with('success', 'Ok!');
        } catch (\Exception $e) {
            return redirect(route('employeesstocks.index'))->with('error', 'Erreur dans la base de données');
        }
    }

    public function stockOfEmployee($id)
    {
        $depots = Depot::where('user_id', $id)->get(['id', 'name']);

        return response()->json([
            'depots' => $depots,
        ]);
    }
}
