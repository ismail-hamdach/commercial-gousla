@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Gestion des produits </h2>
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
            <h5 class="card-header">Modifier Le produit {{ $product->name }}</h5>
            <div class="card-body ">
                <form class="needs-validation" action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom03" class="mt-3">Référence</label>
                                    <input type="text" class="form-control" name="ref"  id="validationCustom03" placeholder="Référence" value="{{ $product->ref }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom04" class="mt-3">Nom de produit :</label>
                                    <input type="text" class="form-control" name="name" id="validationCustom04" placeholder="Nom de produit" value="{{ $product->name }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationCustom03" class="mt-3">Prix :</label>
                                    <input type="text" name="price" class="form-control" id="validationCustom03" placeholder="prix" value="{{ $product->price }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom03" class="mt-3">Quantité :</label>
                                    <input type="text" name="QNT" class="form-control" id="validationCustom03" value="{{ $product->QNT }}" placeholder="Quantité" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="mt-3">Choisir la catégorie</label>
                                    <div class="d-flex ">
                                        <select name="categorie" id="categorie" class="form-control">

                                        </select>
                                        <button type="button" class="btn btn-success px-3" data-toggle="modal"
                                        data-target="#exampleModalCenter"><i
                                            class=" fas fa-plus-circle"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="mt-3">Choisir Le dépot</label>
                                    <div class="d-flex ">
                                        <select name="depot" id="depot" class="form-control">

                                        </select>
                                        <button type="button"  class="btn btn-success px-3" data-toggle="modal"
                                        data-target="#model_depot"><i
                                            class=" fas fa-plus-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-3">
                            <div class="imgUp">
                                <div class="imagePreview" style="background: url('{{ asset('product_image/'.$product->image) }}'); background-size:cover;"></div>
                                <label class="btn btn-info w-100">
                                    Uploader<input type="file" name="image" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary" type="submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- ============================================================== -->
    <!-- Depot add modale -->
    <!-- ============================================================== -->
    <div class="modal fade" id="model_depot" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                        <label for="validationCustom03">Nom :</label>
                        <input type="text" name="depot" id="name" class="form-control" placeholder="Nom" required>
                        <label for="validationCustom03" class="mt-3">Adresse :</label>
                        <input type="text" name="addresse" id="addresse" class="form-control" placeholder="adresse" >
                        <label for="validationCustom03" class="mt-3">Email :</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" >
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-black radius" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary radius" id="add_depot">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

@section("js_product_page")
    <script>
        $(document).ready(function () {

            //Insert data of Categorie
            $('#add_categorie').on("click", function () {
                var name= $("#name").val();
                var tva= $("#TVA").val();
                var token=$("#csrf").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('categories.store') }}",
                    data: {name:name,TVA:tva,_token:token},
                    success: function (response) {
                        $('#exampleModalCenter').modal('toggle');
                        toastr.success("L'ajout de catégorie avec succès");
                        $('#categorie').empty();
                        selectDataCategorie();
                    }
                });
            });
            
            //Insert data of DEPOT
            $('#add_depot').on("click", function () {
                var name= $("#name").val();
                var addresse= $("#addresse").val();
                var email= $("#email").val();
                var token=$("#csrf").val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('depots.store') }}",
                    data: {name:name,addresse:addresse,email:email,_token:token},
                    success: function (response) {
                        $('#model_depot').modal('toggle');
                        toastr.success("L'ajout de dépot avec succès");
                        $('#depot').empty();
                        selectDataDepot();
                    }
                });
            });

            //select data of depot
            function selectDataDepot()
            {
                $.ajax({
                type: "GET",
                url: "{{ route('products.fetchDataDepot') }}",
                dataType:'json',
                success: function (response) {
                    // $('#model_depot').modal('toggle');
                    // toastr.success("L'ajout avec succès")
                    //console.log(response.depots);
                    $.each(response.depots, function (key,item) { 
                         $('#depot').append("<option value='"+item.id+"'>"+item.name+"</option>");
                    });
                }
                });
            }
            selectDataDepot();

            //select data of categorie
            function selectDataCategorie()
            {
                $.ajax({
                type: "GET",
                url: "{{ route('products.fetchDataCategoris') }}",
                dataType:'json',
                success: function (response) {
                    //$('#model_depot').modal('toggle');
                    //toastr.success("L'ajout avec succès")
                    //console.log(response.depots);
                    $.each(response.categories, function (key,item) { 
                         $('#categorie').append("<option value='"+item.id+"'>"+item.name+"</option>");
                    });
                    }
                });
            }
            selectDataCategorie();
        });
    </script>
@endsection