<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laratrust\Laratrust;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Models\LaratrustRole;

class Roles extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Dashboard.master.roles.show', [
            'roles' => LaratrustRole::pluck('name', 'id')->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dashboard.master.roles.create', [
            'permissions' => LaratrustPermission::pluck('name', 'id')->all(),
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
        $validated = $request->validate([
            'rolename' => 'required|string',
            'selectedPermissions' => 'required|array',
        ]);

        $role = \App\Models\Role::create([
            'name' => $validated['rolename'],
        ]);

        foreach ($validated['selectedPermissions'] as $permission) {
            $permission = LaratrustPermission::where('name', $permission)->first();
            $role->attachPermission($permission);
        }

        return redirect()->to('roles')->with(['success' => 'Ajouté avec success']);
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
        try {
            return view('Dashboard.master.roles.edit', [
                'roleName' => LaratrustRole::findOrFail($id)->name,
                'permissionsOfRole' => LaratrustRole::findOrFail($id)->permissions->pluck('name')->toArray(),
                'allPermissions' => LaratrustPermission::pluck('name')->all(),
                'idOfRole' => $id,
            ]);
        } catch (\Exception $e) {
            dd($e);
            return abort(404);
        }
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
        //
        try {
            $validated = $request->validate([
                'selectedPermissions' => 'required|array',
            ]);

            LaratrustRole::findOrFail($id)->syncPermissions($validated['selectedPermissions']);
            return redirect()->to('roles')->with(['success' => 'Modifications avec success']);
        } catch (\Exception $e) {
            return abort(404);
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
        //
        dd($id);
    }
}
