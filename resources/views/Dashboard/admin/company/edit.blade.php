@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Paramètres de l'entreprise </h2>
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
            <h5 class="card-header">Modifier les paramètres de votre entreprise</h5>
            <div class="card-body ">
                <form class="needs-validation" action="{{ route('companys.update',$company->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom03">Nom de société :</label>
                                    <input type="text" class="form-control" name="name"  id="validationCustom03" placeholder="Nom de société" value="{{ $company->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom04">Adresse :</label>
                                    <input type="text" class="form-control" name="addresse" id="validationCustom04" placeholder="Adresse" value="{{ $company->addresse }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom03">Téléphone :</label>
                                    <input type="text" name="phone" class="form-control" id="validationCustom03" placeholder="telephone" value="{{ $company->phone }}" >
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom03">GSM :</label>
                                    <input type="text" name="GSM" class="form-control" id="validationCustom03" placeholder="GSM" value="{{ $company->GSM }}" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom04">ICE :</label>
                                    <input type="text" class="form-control" name="ICE" id="validationCustom04"  placeholder="ICE" value="{{ $company->ICE }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom03">Email :</label>
                                    <input type="email" name="email" class="form-control" id="validationCustom03"  placeholder="Email" value="{{ $company->email }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom04" class="mt-3">Numéro de registre de commerce :</label>
                                    <input type="number" class="form-control" name="RC" id="validationCustom04" value="{{ $company->RC }}"  placeholder="RC" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom03" class="mt-3">Identifiant taxe professionnelle :</label>
                                    <input type="number" name="TP" class="form-control" id="validationCustom03" value="{{ $company->TP }}"  placeholder="TP" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom04" class="mt-3">Identification fiscal :</label>
                                    <input type="number" class="form-control" name="IF" id="validationCustom04" value="{{ $company->IF }}" placeholder="IF" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-3">
                            <div class="imgUp">
                                <div class="imagePreview" style="background: url('{{ asset('Company_logo/'.$company->logo) }}'); background-size:cover;"></div>
                                <label class="btn btn-info w-100">
                                    Uploader<input type="file" name="logo" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <button class="btn btn-primary" type="submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection