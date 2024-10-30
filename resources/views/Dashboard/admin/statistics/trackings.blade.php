@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Deleted Orders</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Employees</label>
                <select class="form-control" id="employee">
                    <option value="0">Tous</option>
                    @foreach ($employee as $employe)
                        <option value="{{ $employe['id']}}">{{ $employe['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchTrackings()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getChiffreAffaireClient()"
                    class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div class="table-responsive bg-white">
        <div style="max-height: 40vh; overflow-y: auto;">
      
            <table id="example" class="table "  >
                <thead class="bg-light">
                    <tr class="border-0">
                        <th>Ref</th>
                        <th>Date de Creation</th>
                        <th>Produit</th>
                        <th>Ancienne quantité</th>
                        <th>Nouvelle quantité</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="deletedOrdes">
                </tbody>
            </table>
        </div>
    </div>
            <div class="modal fade" id="modal_details"  
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content" style="width:150% ;height:120% ">
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
                                    <th>Date de Creation</th>
                                    <th>Produit</th>
                                    <th>Ancienne quantité</th>
                                    <th>Nouvelle quantité</th>
                                       <th>Commercial</th>
                                       <th>Type</th>
                                     </tr>
                                </thead>
                                <tbody id="Data_Order">
                                </tbody>
                                <tr>
                                    <td colspan="4">TOTAL GLOBAL</td>
                                    <td id="profit"></td>
                                    <td id="Total"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!--<div class="modal-footer">-->
                    <!--    <button type="button" class="btn btn-dark rounded" data-dismiss="modal">Annuler</button>-->
                    <!--    <form action="" method="GET">-->
                    <!--        @csrf-->
                    <!--        <input type="hidden" id="imprimer_order_ID" name="order_ID" value="">-->
                    <!--        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"-->
                    <!--            class="btn btn-danger rounded">Télécharger PDF</button>-->
                    <!--    </form>-->
                    <!--</div>-->
                </div>
            </div>
        </div>

    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
@endsection

@section('js_index_page')
    <script>
        function getChiffreAffaireClient(startDate = null, endDate = null, employeeId = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/searchTrackings',
                method: 'POST',
                data: {
                    _token: token,
                    employeeId: employeeId,
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#deletedOrdes').html('');
                    console.log(result)
                    $(result).each((item, data) => {
                        //rders.ref,orders.total,orders.created_at,orders.deleted_at,clients.name
                        $('#deletedOrdes').append(`
                            <tr>
                                <td>${ data.ref }</td>
                                <td>${ new Date(data.created_at).toISOString().split('T')[0] + ' at ' + new Date(data.created_at).toLocaleTimeString() } </td>
                                <td>${ data.product } </td>
                                <td>${ data.oldQuantity } </td>
                                <td>${ data.newQuantity } </td>
                                <td>  <a href="" data-toggle="modal"
                                      data-orderId="${ data.id }" data-target="#modal_details"
                                      class="btn btn-primary rounded btn_details">Détails</a>
                                </td>
                            </tr>
                        `);
                    })
                }
            });
        }

        function searchTrackings() {
            let startDate = document.getElementById('date_debut').value;
            let endDate = document.getElementById('date_fin').value;
            let employeeId = document.getElementById('employee').value;
            getChiffreAffaireClient(startDate, endDate, employeeId);
        }
        
                $(".table").on('click', '.btn_details', function(e) {
            e.preventDefault();
            var ii = $(this).index('.btn_details')
            // var valueQNT= document.querySelectorAll(".btn_details")[ii].getAttribute('data-orderId');
            var data_ref = document.querySelectorAll(".btn_details")[ii]
            var orderId = data_ref.getAttribute('data-orderId');
            // $('#imprimer_order_ID_update').val(order_ref);

            $.ajax({
                type: "GET",
                url: 'searchTrackingsDetails/'+orderId,
                 
                success: function(response) {
                    $('#Data_Order').empty();
                    $.each(response, function(key, item) {
                        
                        $('#Data_Order').append(
                            `
                            <tr>
                                <td>${ new Date(item.created_at).toISOString().split('T')[0] + ' at ' + new Date(item.created_at).toLocaleTimeString() } </td>
                                <td>${ item.product } </td>
                                <td>
                                   <input type='number' class='input_QNT_router' min='1' disabled style='width:60px;' value="${item.oldQuantity}"/>
                                </td>
                                <td>
                                   <input type='number' class='input_QNT_router' min='1' disabled style='width:60px;' value="${item.newQuantity}"/>
                                </td>
                                <td>${ item.user } </td>
                                <td>${ item.type } </td>
                                
                            </tr>
                        `);
                        $('#Qnt_retour').val(item.QNT);
                    });
                    $('#Total').text(response.total);
                    $('#profit').text(response.totalProfit);
                }
            });
        });


        getChiffreAffaireClient();
    </script>
@endsection
