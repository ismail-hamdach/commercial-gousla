<?php

namespace App\Http\Controllers\Admin\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laratrust\Models\LaratrustRole;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $users = User::whereHas('roles', function (Builder $query) {
                $query->whereNotIn('name', ['master']);
            })->get();
        } else 
            $users = User::all();

        return view('Dashboard.admin.users.show', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dashboard.admin.users.create', [
            'roles' => LaratrustRole::pluck('name')->all(),
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
            'name' => 'required|unique:users|max:33|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|string',
        ]);

        // hash password before add new user
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->attachRole($validated['role']);
        return redirect()->to('users')->with(['success' => 'Ajouté avec success']);
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
        $user = User::where('id', $id)->first();

        if (auth()->user()->hasRole('master'))
            $roles = LaratrustRole::pluck('name')->all();
        else
            $roles = LaratrustRole::where('name', '!=', 'master')->pluck('name')->all();

        $oldRole = $user->roles[0]->name;
        return view('Dashboard.admin.users.edit', compact('user', 'roles', 'oldRole'));
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
        $user = User::find($id);

        $validated = $request->validate([
            'name' => 'required|max:33|min:3',
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password);

        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $user->syncRoles([$validated['role']]);

        if ($request->role == 'admin') {
            $user->detachRole($user->role);
            $user->attachRole("admin");
        } elseif ($request->role == 'user') {
            $user->detachRole($user->role);
            $user->attachRole("user");
        }

        return redirect()->to('users')->with(['success' => 'La modification avec success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->to('users')->with(['success' => 'La supprission avec success']);
    }
}
