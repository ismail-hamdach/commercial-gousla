@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Gestion des produits pour les employees</h2>
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
                <h5 class="card-header">Inserer des produits</h5>
                <div class="card-body ">
                    <form class="needs-validation" action="{{ route('employees.saveproduct') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="validationCustom03" class="mt-3">Répférence</label>
                                        <input type="text" class="form-control" name="ref" readonly
                                            id="validationCustom03" placeholder="Référence" value="{{ old('refe') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom04" class="mt-3">Nom de produit <span
                                                style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="name" id="validationCustom04"
                                            placeholder="Nom de produit" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-md-6">
                                        <label for="validationCustom03" class="mt-3">Prix d'achat <span
                                                style="color:red;">*</span></label>
                                        <input type="text" name="priceAchat" class="form-control" id="validationCustom05"
                                            value="{{ old('priceAchat') }}" placeholder="Price d'Achat">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationCustom03" class="mt-3">Prix de vent <span
                                                style="color:red;">*</span></label>
                                        <input type="text" name="price" class="form-control" id="validationCustom03"
                                            value="{{ old('price') }}" placeholder="prix" required>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="validationCustom03" class="mt-3">Percentage % :</label>
                                        <input type="text" name="percentage" value="0"  class="form-control" id="validationCustom03"
                                            value="{{ old('percentage') }}" placeholder="Percentage">
                                    </div>
                                     <div class="col-md-4">
                                        <label for="validationCustom03" class="mt-3">Quantité <span
                                                style="color:red;">*</span></label>
                                        <input type="text" name="QNT" class="form-control" id="validationCustom03" 
                                            value="{{ old('QNT') }}" placeholder="Quantité">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="mt-3">Choisir l'employeer <span
                                                style="color:red;">*</span></label>
                                        <div class="d-flex ">
                                            <select
                                                onchange="findCategoriesOfEmployee(event.target.value); findStocksOfEmployee(event.target.value)"
                                                required name="users" id="users" class="form-control">
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="mt-3">Choisir la catégorie <span
                                                style="color:red;">*</span></label>
                                        <div class="d-flex ">
                                            <select name="categorie" id="categorie" class="form-control">
                                            </select>
                                            <button type="button" class="btn btn-success px-3" data-toggle="modal"
                                                data-target="#exampleModalCenterCategorie"><i
                                                    class=" fas fa-plus-circle"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="mt-3">Choisir Le dépot <span
                                                style="color:red;">*</span></label>
                                        <div class="d-flex ">
                                            <select name="depot" id="depot" class="form-control">
                                            </select>
                                            <button type="button" class="btn btn-success px-3" data-toggle="modal"
                                                data-target="#model_depot"><i class=" fas fa-plus-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-3">
                                <div class="imgUp">
                                    <div class="imagePreview"></div>
                                    <label class="btn btn-info w-100">
                                        Uploader<input type="file" name="image" class="uploadFile img"
                                            value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-">
                            <button class="btn btn-primary" type="submit">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Categorie add modale -->
    <!-- ============================================================== -->
    <div class="modal fade" id="exampleModalCenterCategorie" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ajouter une famille</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                        <label for="validationCustom03">Nom :</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nom"
                            required>
                        <label for="" class="mt-2">Employee :</label>
                        <select id="workersForCategorie" name="employeeIdForCategorie" class="form-control">

                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-black radius" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary radius" id="add_categorie">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Depot add modale -->
    <!-- ============================================================== -->
    <div class="modal fade" id="model_depot" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ajouter Dépot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                        <label for="validationCustom03">Nom :</label>
                        <input type="text" name="depot" id="name_depot" class="form-control" placeholder="Nom"
                            required>
                        <label for="validationCustom03" class="mt-3">Adresse :</label>
                        <input type="text" name="addresse" id="addresse" class="form-control"
                            placeholder="adresse">
                        <label for="validationCustom03" class="mt-3">Email :</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                        <label for="" class="mt-2">Employee :</label>
                        <select id="workersForStock" type="text" name="workersForStock" class="form-control">

                        </select>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-black radius" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary radius" id="add_depot">Ajouter</button>
                </div>
            </div>
        </div>
        <script>
            function findCategoriesOfEmployee(id) {
                $.ajax({
                    type: "GET",
                    url: `/employeescategory/${id}`,
                    dataType: 'json',
                    success: function(response) {
                        //$('#model_depot').modal('toggle');
                        //toastr.success("L'ajout avec succès")
                        //console.log(response.depots);
                        $('#categorie').html('');
                        $('#categorie').append("<option value='0'>Choisir categorie</option>");
                        $.each(response.categories, function(key, item) {
                            $('#categorie').append("<option value='" + item.id + "'>" + item
                                .name + "</option>");
                        });
                    }
                });
            }

            function findStocksOfEmployee(id) {
                $.ajax({
                    type: "GET",
                    url: `/employeesstock/${id}`,
                    dataType: 'json',
                    success: function(response) {
                        $('#depot').html('');
                        $('#depot').append("<option value='0'>Choisir categorie</option>");
                        $.each(response.depots, function(key, item) {
                            $('#depot').append("<option value='" + item.id + "'>" + item.name +
                                "</option>");
                        });
                    }
                });
            }
        </script>
    </div>
@endsection

@section('js_product_page')
    <script>
        function getAllWorkers() {
            $.ajax({
                type: 'GET',
                url: "{{ route('users.data') }}",
                dataType: 'json',
                success: function(response) {
                    $('#users').append('<option value="0">Choisir employee</option>')
                    $('#workersForStock').append('<option value="0">Choisir employee</option>')
                    $('#workersForCategorie').append('<option value="0">Choisir employee</option>')
                    $.each(response, function(key, item) {
                        $('#users').append("<option value='" + key + "'>" + item + "</option>");
                        $('#workersForStock').append("<option value='" + key + "'>" + item +
                            "</option>");
                        $('#workersForCategorie').append("<option value='" + key + "'>" + item +
                            "</option>");
                    });
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            //Insert data of Categorie
            $('#add_categorie').on("click", function() {
                var name = $("#name").val();
                var employeeId = $('#workersForCategorie').val();
                var token = $("#csrf").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('categories.store') }}",
                    data: {
                        name: name,
                        employeeId: employeeId,
                        _token: token
                    },
                    success: function(response) {
                        $('#exampleModalCenterCategorie').modal('toggle');
                        toastr.success("L'ajout de catégorie avec succès");
                        $('#categorie').empty();
                        selectDataCategorie();
                    }
                });
            });

            //Insert data of DEPOT
            $('#add_depot').on("click", function() {
                var name = $("#name_depot").val();
                var addresse = $("#addresse").val();
                var email = $("#email").val();
                var employeeId = $('#workersForStock').val();
                var token = $("#csrf").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('depots.store') }}",
                    data: {
                        name: name,
                        addresse: addresse,
                        email: email,
                        employeeId: employeeId,
                        _token: token
                    },
                    success: function(response) {
                        $('#model_depot').modal('toggle');
                        toastr.success("L'ajout de dépot avec succès");
                        $('#depot').empty();
                        selectDataDepot();
                    }
                });
            });

            //select data of depot
            function selectDataDepot() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('products.fetchDataDepot') }}",
                    dataType: 'json',
                    success: function(response) {
                        $.each(response.depots, function(key, item) {
                            $('#depot').append("<option value='" + item.id + "'>" + item.name +
                                "</option>");
                        });
                    }
                });
            }

            //select data of categorie
            function selectDataCategorie() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('products.fetchDataCategoris') }}",
                    dataType: 'json',
                    success: function(response) {
                        //$('#model_depot').modal('toggle');
                        //toastr.success("L'ajout avec succès")
                        //console.log(response.depots);
                        $.each(response.categories, function(key, item) {
                            $('#categorie').append("<option value='" + item.id + "'>" + item
                                .name + "</option>");
                        });
                    }
                });
            }

            getAllWorkers();
        });
    </script>
@endsection
