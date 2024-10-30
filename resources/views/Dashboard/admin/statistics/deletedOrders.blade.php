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
                <button type="button" onclick="searchDeletedOrders()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getChiffreAffaireClient()"
                    class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
        </div>
    </div>
    <div class="table-responsive bg-white">
        <div style="max-height: 40vh; overflow-y: auto;">
            <table class="table">
                <thead class="bg-light">
                    <tr class="border-0">
                        <th>Ref</th>
                        <th>Date de Creation</th>
                        <th>Date d'Annulation</th>
                        <th>Client</th>
                        <th>Total</th>
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
                                        <th>produit </th>
                                        <th>QNT</th>
                                        <th>Prix</th>
                                        <th>Percentage</th>
                                        <th>Profit</th>
                                        <th>Total</th>
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
                url: '/statistics/searchDeletedOrders',
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
                                <td>${ new Date(data.deleted_at).toISOString().split('T')[0] + ' at ' + new Date(data.deleted_at).toLocaleTimeString() } </td>
                                <td>${ data.name } </td>
                                <td>${ data.total } </td>
                                <td>  <a href="" data-toggle="modal"
                                      data-orderId="${ data.ref }" data-target="#modal_details"
                                      class="btn btn-primary rounded btn_details">Détails</a>
                                </td>
                            </tr>
                        `);
                    })
                }
            });
        }

        function searchDeletedOrders() {
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
            var order_ref = data_ref.getAttribute('data-orderId');
            // $('#imprimer_order_ID_update').val(order_ref);

            $.ajax({
                type: "GET",
                url: "{{ route('orders.details') }}",
                data: {
                    order_ref: order_ref
                },
                success: function(response) {
                    $('#Data_Order').empty();
                    $.each(response.DetailsOrder, function(key, item) {
                        $('#Data_Order').append("<tr><td>" + item.product_name +
                            "</td><td><input type='hidden' class='input_id_detailOrder_router' data-input_id_detailOrder_router='" +
                            item.id +
                            "'/><input type='number' class='input_QNT_router' min='1' disabled style='width:60px;' value='" +
                            item.QNT + "'/></td><td>" + item.price + " DH</td><td>" + item
                            .percentage + " %</td><td>" + item.profit + " DH</td><td>" +
                            item.total + " DH</td></tr>");
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
