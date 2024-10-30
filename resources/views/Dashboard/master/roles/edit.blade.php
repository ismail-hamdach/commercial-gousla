@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Gestion des
                    roles</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
            @endif
            <div class="card">
                <h5 class="card-header">Modifier un role</h5>
                <div class="card-body">
                    <form action="{{ route('roles.update', $idOfRole) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Nom de role <span style="color:red;">*</span></label>
                                <input type="text" name="rolename" class="form-control" placeholder="role name"
                                    value="{{ $roleName }}" required>
                            </div>
                        </div>
                        <div class="form-row " >
                            <div class="d-flex flex-column  col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2" style="height: 300px;">
                                <label>List des permissions <span style="color:red;">*</span></label>
                                <select required name="selectedPermissions[]" class="form-control flex-grow-1" multiple>
                                    @foreach ($allPermissions as $permission)
                                        <option @if (in_array($permission, $permissionsOfRole)) selected @endif>{{ $permission }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <button class="btn btn-primary" type="submit">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
