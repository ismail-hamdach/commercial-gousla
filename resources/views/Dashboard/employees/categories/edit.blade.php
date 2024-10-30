@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Gestion des categories pour les employees</h2>
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
                <h5 class="card-header">Editer une categorie</h5>
                <div class="card-body">
                    <form action="{{ route('employeescategories.update', $categorie->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Nom de categorie <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $categorie->name }}"
                                    placeholder="nom de categorie">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Employees <span style="color:red;">*</span></label>
                                <select class="form-control" name="user">
                                    <option value="0">Choisir employee</option>
                                    @foreach ($possibleEmployees as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                <button class="btn btn-primary" type="submit">Editer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
