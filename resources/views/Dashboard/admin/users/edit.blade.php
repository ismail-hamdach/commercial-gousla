@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Gestion des
                    utilisateurs</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Modifier utilisateur abdellah</h5>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Nom d'utilisateur <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                    placeholder="username" required>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Email <span style="color:red;">*</span></label>
                                <input type="email" class="form-control" name="email" placeholder="email"
                                    value="{{ $user->email }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label for="validationCustom03">Mot de passe <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="password" placeholder="Mot de passe">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Role <span style="color:red;">*</span></label>
                                <select name="role" class="form-control">
                                    @foreach ($roles as $role)
                                        <option @if ($oldRole == $role) selected @endif>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <button class="btn btn-primary" type="submit">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
