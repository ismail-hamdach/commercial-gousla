@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Gestion des stock pour les employees</h2>
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
                <h5 class="card-header">Editer un depot</h5>
                <div class="card-body">
                    <form action="{{ route('employeesstocks.update', $stock->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Nom de depot <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $stock->name }}"
                                    placeholder="nom de depot">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Address de depot <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="address" value="{{ $stock->addresse }}"
                                    placeholder="Address de depot">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                                <label>E-Mail <span style="color:red;">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ $stock->email }}"
                                    placeholder="email de depot">
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
