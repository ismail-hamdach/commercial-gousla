@extends('Dashboard.layouts.master')

@section('content')
    
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Chiffre d'Affaire</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_get_chiffre_affaire">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_get_chiffre_affaire">
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchChiffreAffire()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getChiffreAffaire()" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div id="chiffreAffaireCommercial" class="row">
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Profits</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_get_profit">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_get_profit">
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchProfit()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getChiffreAffaire()" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div id="chiffreProfit" class="row">
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Commession</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_get_commession">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_get_commession">
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchCommession()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getCommessions()" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div id="commessionCommercial" class="row">
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Meilleur Produit Vendu</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_get_best_selling_products">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_get_best_selling_products">
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchBestSellingProducts()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getBestSellingProducts()" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div class="table-responsive bg-white">
        <table class="table">
            <thead class="bg-light">
                <tr class="border-0">
                    <th>Nom Produit</th>
                    <th>Quantity</th>
                    <th>Par</th>
                </tr>
            </thead>
            <tbody id="bestSellingProducts">
            </tbody>
        </table>
    </div>
 
    {{-- <div class="row">
    <div class="col-xl-9 col-lg-12 col-md-6 col-sm-12 col-12">
        <div class="card">
            
            <h5 class="card-header">5 Dernières commandes </h5>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-light">
                            <tr class="border-0">
                                <th>Référence</th>
                                <th>Nom de client</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>payment_method</th>                             
                                <th>Date</th>                              
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Orders as $order)
                            <tr>
                                <td class="text-center">{{ $order->ref }}</td>
                                <td class="text-center">{{ $order->client->name }}</td>
                                <td class="text-center">{{ $order->client->email }}</td>
                                <td class="text-center">{{ $order->client->phone }}</td>
                                <td class="text-center">{{ $order->payment_method }}</td>
                                <td class="text-center">{{ $order->created_at }}</td>
                                <td style="width:10%;">
                                    <div class="d-flex justify-content-between">
                                        <a href=""
                                            data-toggle="modal" 
                                            data-orderId="{{ $order->ref }}" 
                                            data-target="#modal_details"
                                            class="btn btn-primary rounded btn_details">Détails</a>

                                        <a href="#"
                                        data-toggle="modal"
                                        data-orderId="{{ $order->id }}"
                                        data-target="#deletemodal"
                                        class="btn btn-danger rounded">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>    
</div> --}}


    <!-- modal delete -->
    <!-- =============================== -->
    <div class="modal fade" id="modal_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Détails de commande</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Nom produit </th>
                                    <th>QNT </th>
                                    <th>Prix </th>
                                    <th>Total </th>
                                </tr>
                            </thead>
                            <tbody id="Data_Order">
                            </tbody>
                            <tr>
                                <td>TOTAL GLOBAL</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="4" id="Total"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded" data-dismiss="modal">Annuler</button>
                    <form action="" method="GET">
                        @csrf
                        <input type="hidden" id="imprimer_order_ID" name="order_ID" value="">
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="btn btn-danger rounded">Imprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ====================================== -->
    <!-- end modal delete -->

    <!-- modal delete -->
    <!-- =============================== -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer!!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded" data-dismiss="modal">Annuler</button>
                    <form action="" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" id="order_ID" name="order_ID" value="">
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="btn btn-danger rounded">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        <tr>
            <td></td>
        </tr>
    </div>
    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
    <!-- ====================================== -->
    <!-- end modal delete -->
    </div>
@endsection

@section('js_index_page')
    <script>
        function getChiffreAffaire(startDate = null, endDate = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/board',
                method: 'POST',
                data: {
                    _token: token,
                    _target: 'chiffreAffaire',
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#chiffreAffaireCommercial').html('');
                    $(result).each((item, data) => {
                        $('#chiffreAffaireCommercial').append(`
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card" style="box-shadow: 10px 5px 5px rgb(145, 145, 35);">
                                    <div class="card-body">
                                        <h5 class="text-muted">${data.name}</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1">${data.totalChiffre} DH</h1>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue2"></div>
                                </div>
                            </div>
                        `);
                    })
                }
            });
        }

        function getCommessions(startDate = null, endDate = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/board',
                method: 'POST',
                data: {
                    _token: token,
                    _target: 'commession',
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#commessionCommercial').html('');
                    $(result).each((item, data) => {
                        $('#commessionCommercial').append(`
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card" style="box-shadow: 10px 5px 5px rgb(145, 145, 35);">
                                    <div class="card-body">
                                        <h5 class="text-muted">${data.name}</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1">${data.commession} DH</h1>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue2"></div>
                                </div>
                            </div>
                        `);
                    })
                }
            });
        }

        function getBestSellingProducts(startDate = null, endDate = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/board',
                method: 'POST',
                data: {
                    _token: token,
                    _target: 'bestSellingProducts',
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#bestSellingProducts').html('');
                    $(result).each((item, data) => {
                        $('#bestSellingProducts').append(`
                            <tr>
                                <td>${ data.product_name }</td>
                                <td>${ data.countOccurence }</td>
                                <td>${ data.name }</td>
                            </tr>
                        `);
                    })
                }
            });
        }
        function getProfit(startDate = null, endDate = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/board',
                method: 'POST',
                data: {
                    _token: token,
                    _target: 'profit',
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#chiffreProfit').html('');
                    $(result).each((item, data) => {
                        $('#chiffreProfit').append(`
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card" style="box-shadow: 10px 5px 5px rgb(145, 145, 35);">
                                    <div class="card-body">
                                        <h5 class="text-muted">${data.title}</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1">${data.value} DH</h1>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue2"></div>
                                </div>
                            </div>
                        `);
                    })
                }
            });
        }

        function searchChiffreAffire() {
            let startDate = document.getElementById('date_debut_get_chiffre_affaire').value;
            let endDate = document.getElementById('date_fin_get_chiffre_affaire').value;
            getChiffreAffaire(startDate, endDate);
        }
        function searchProfit() {
            let startDate = document.getElementById('date_debut_get_profit').value;
            let endDate = document.getElementById('date_fin_get_profit').value;
            getProfit(startDate, endDate);
        }

        function searchCommession() {
            let startDate = document.getElementById('date_debut_get_commession').value;
            let endDate = document.getElementById('date_fin_get_commession').value;
            getCommessions(startDate, endDate);
        }

        function searchBestSellingProducts() {
            let startDate = document.getElementById('date_debut_get_best_selling_products').value;
            let endDate = document.getElementById('date_fin_get_best_selling_products').value;
            getBestSellingProducts(startDate, endDate);
        }

        getChiffreAffaire();
        getCommessions();
        getBestSellingProducts();
        getProfit();
    </script>
@endsection
