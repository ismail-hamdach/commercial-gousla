@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Créer order</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <form id="createOrder" class="row" action="{{ route('invoice.store') }}" method="POST">
                @csrf
                @livewire('search-product')
                <div class="col-sm-3 col-md-6 col-lg-6  ">
                    <div class="p-4 card" id='SelectClient'>
                        <h3>Les clients <span style="color:red;">*</span></h3>
                        <div class="d-flex ">
                            @if (auth()->user()->hasRole('admin'))
                                <a href="{{ route('clients.create') }}" class="btn btn-success"><i
                                        class=" fas fa-plus-circle"></i></a>
                            @endif
                            {{-- <input type="text" id="clientSearch" wire:model="searchTirm"  placeholder="Rechercher un client" autocomplete="off"> --}}
                            <select name="clients" id="" class="selectpicker form-control" data-live-search="true">
                                @forelse ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @empty
                                    <div class='col-md-12'>Aucune résultat</div>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="p-4 card ">
                        <label for="">Choisir un stock <span style="color:red;">*</span></label>
                        <div class="d-flex">
                            @if (auth()->user()->hasRole('admin'))
                                <a href="#" data-toggle="modal" id="add_depotOrder" data-target="#model_depot"
                                    class="btn btn-success"><i class=" fas fa-plus-circle"></i></a>
                            @endif
                            <select name="depotOrder" id="depotOrder" class="form-control">
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="p-4 card ">
                        <label for="">Choisissez La méthode de paiment <span style="color:red;">*</span></label>
                        <div class="d-flex ">
                            <select name="MethodPayment" id="depotOrder" class="form-control">
                                <option value="virement bancaire">virement bancaire</option>
                                <option value="Espèces" selected>Espèces</option>
                                <option value="cheque">chèque</option>
                                <option value="retour">retour</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="card p-4">
                        <div class="table-responsive">
                            <div class="row">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Designation</th>
                                            <th scope="col">Prix (DH)</th>
                                            <th scope="col">Quantite</th>
                                            <th scope="col">Percentage</th>
                                            <th scope="col">Profit (DH)</th>
                                            <th scope="col">Total (DH)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ProductSession">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr style="margin-top: 60px;margin-bottom: 60px">
                        <div class="row w-100">
                            <div class="col">
                                <span>Total HT</span> <br>
                                <div class="text-center text-success h2 Total "></div>
                                <input type="hidden" class="TotalInput" value="" name="total">
                            </div>
                        </div>
                        <div id="hiddenDiv" class="mt-3 text-center" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Chargement...</span>
                            <div class="mt-2">Traitement de la commande...</div>
                        </div>
                        </div>
                        <button id="submitBtn" type="submit" name="Send_invoice" value="Send_invoice"
                            class="btn btn-primary mt-3">Envoyer</button>
                            
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Categorie add modale -->
    <!-- ============================================================== -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
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
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nom" required>
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
                        <input type="text" name="depot" id="name_depot_order" class="form-control"
                            placeholder="Nom" required>
                        <label for="validationCustom03" class="mt-3">Adresse :</label>
                        <input type="text" name="addresse" id="addresse_order" class="form-control"
                            placeholder="adresse">
                        <label for="validationCustom03" class="mt-3">Email :</label>
                        <input type="email" name="email" id="email_order" class="form-control" placeholder="Email">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-black radius" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary radius" id="ad_depotOrder">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js_order_page')
    @if (Session::has('success'))
        <script>
            toastr.success("{!! Session::get('success') !!}")
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.error("{!! Session::get('error') !!}")
        </script>
    @endif
    <script>
        //Insert data of DEPOT
        $('#ad_depotOrder').on("click", function() {
            var name = $("#name_depot_order").val();
            var addresse = $("#addresse_order").val();
            var email = $("#email_order").val();
            var token = $("#csrf").val();

            $.ajax({
                type: "POST",
                url: "{{ route('depots.store') }}",
                data: {
                    name: name,
                    addresse: addresse,
                    email: email,
                    _token: token
                },
                success: function(response) {
                    $('#model_depot').modal('toggle');
                    toastr.success("L'ajout de dépot avec succès");
                    $('#depotOrder').empty();
                    selectDataDepot();
                }
            });
        });

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
                        $('#categorie').append("<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    });
                }
            });
        }
        //selectDataCategorie();

        //Insert data of Categorie
        $('#add_categorie').on("click", function() {
            var name = $("#name").val();
            var token = $("#csrf").val();

            $.ajax({
                type: "POST",
                url: "{{ route('categories.store') }}",
                data: {
                    name: name,
                    _token: token
                },
                success: function(response) {
                    $('#exampleModalCenter').modal('toggle');
                    toastr.success("L'ajout de catégorie avec succès");
                    $('#categorie').empty();
                    selectDataCategorie();
                }
            });
        });

        $('#createOrder').on('submit', function() {
            $('#submitBtn').hide();
            $('#hiddenDiv').show();
        });
    </script>
@endsection
