@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Gestion des commandes d'employees</h2>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="row">
                <!-- ============================================================== -->
                <!-- data table  -->
                <!-- ============================================================== -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0"></h5>
                            {{-- <a href="{{ route('orders.create') }}" class=" text-success">Ajouter une Commande <i
                                    class=" fas fa-plus-circle"></i></a> --}}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <input type="hidden" value="Utilisateurs data" id="dataa">
                                <form action="" class="d-flex justify-center mb-5">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 ">
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control date_debut" name="date_debut">
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label for="">Date fin</label>
                                            <input type="date" class="form-control date_fin" name="date_fin">
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label for="">Employee</label>
                                            <select class="form-control" name="user_id">
                                                <option value="0">Tous</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                                            <button type="submit"
                                                class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                                            <a href="{{ route('employees.allorders') }}"
                                                class="btn btn-warning mt-3 btn_cancel rounded">Annuler</a>
                                        </div>
                                    </div>
                                </form>
                                <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Référence</th>
                                            <th>Nom de client</th>
                                            <th>email</th>
                                            <th>phone</th>
                                            <th>adresse</th>
                                            <th>payment method</th>
                                            @if(Auth::user()->hasRole('gerant Validation BL|admin|master') )
                                            <th>Validation</th>
                                            @endif
                                            <th>Date</th>
                                            <th>total</th>
                                            @if (boolval($user_id == 0))
                                                <th>Employee</th>
                                            @endif
                                            <th>Dérniere modification</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="text-center">{{ $order->id }}</td>
                                                <td class="text-center">{{ $order->ref }}</td>
                                                <td class="text-center">{{ $order->client->name }}</td>
                                                <td class="text-center">{{ $order->client->email }}</td>
                                                <td class="text-center">{{ $order->client->phone }}</td>
                                                <td class="text-center">{{ $order->client->addresse }}</td>
                                                <td class="text-center">{{ $order->payment_method }}</td>
                                                @if(Auth::user()->hasRole('gerant Validation BL|admin|master') )
                                                <td class="text-center">
                                                    @if ($order->validation == 0)
                                                        <a href="{{ route('orders.validation', $order->id) }}"
                                                            data-orderId="{{ $order->id }}"
                                                            class="btn btn-danger rounded btn_validation">Non valide</a>
                                                    @else
                                                        <a href="{{ route('orders.validation', $order->id) }}"
                                                            data-orderId="{{ $order->id }}"
                                                            class="btn btn-success rounded btn_validation">valide</a>
                                                    @endif
                                                </td>
                                                @endif
                                                <td class="text-center">{{ $order->created_at }}</td>
                                                <td class="text-center">{{ $order->total }} DH</td>
                                                @if (boolval($user_id == 0))
                                                    <td>
                                                        {{ $order->user->name }}
                                                    </td>
                                                @endif
                                                <td class="text-center">{{ $order->updated_at }}</td>
                                                <td style="width:10%;">
                                                    <div class="d-flex justify-content-between">
                                                        <a href="" data-toggle="modal"
                                                            data-orderId="{{ $order->ref }}" data-target="#modal_details"
                                                            class="btn btn-primary rounded btn_details">Détails</a>
                                                        @if (auth()->user()->hasRole('admin|gerant BL|master'))
                                                            <a href="/orders/{{ $order->id }}/edit"
                                                                class="btn btn-warning rounded">Editer
                                                            </a>
                                                        @endif
                                                        <a href="#" data-toggle="modal"
                                                            data-orderId="{{ $order->id }}" data-target="#deletemodal"
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
                </div>
                <!-- ============================================================== -->
                <!-- end data table  -->
                <!-- ============================================================== -->
            </div>
        </div>

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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark rounded" data-dismiss="modal">Annuler</button>
                        <form id="mini" action="" method="GET">
							@csrf
							<input id="imprimer_order_ID" name="order_ID" type="hidden" value="">
							<button class="btn btn-danger rounded" type="submit"
								onclick="event.preventDefault(); this.closest('form').submit();"> Imprimer petite forma</button>
						</form>
                        <form action="" method="GET">
                            @csrf
                            <input type="hidden" id="imprimer_order_ID" name="order_ID" value="">
                            <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="btn btn-danger rounded">Télécharger PDF</button>
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
        <!-- ====================================== -->
        <!-- end modal delete -->
    </div>
@endsection

@section('js_order_page')
    <script>
        $('#deletemodal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var orderID = button.attr('data-orderId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #order_ID').val(orderID)
            modal.find('form').attr('action', '/orders/' + orderID)
        });

        $('#modal_details').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var orderID = button.attr('data-orderId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #imprimer_order_ID_update').val(orderID)
            modal.find('.modal-footer #imprimer_order_ID').val(orderID)
            modal.find('form').attr('action', '/orders/PDF/' + orderID)
            modal.find('#mini').attr('action', '/orders/PDF/mini/' + orderID )
            modal.find('#form_router').attr('action', '/orders/router/')
        });
    </script>

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
@endsection

@section('js_orderDetails_page')
    <script>
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
                            "'/><input type='number' class='input_QNT_router' style='width:60px;' value='" +
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

        $(".table").on('change', '.input_QNT_router', function(e) {
            e.preventDefault();
            var ii = $(this).index('.input_QNT_router')
            var value_QNT = document.querySelectorAll(".input_QNT_router")[ii]
            var input_QNT_router = value_QNT.value;

            var ii = $(this).index('.input_QNT_router')
            var input_id_detailOrder_router_val = document.querySelectorAll(".input_id_detailOrder_router")[ii]
            var input_id_detailOrder_router = input_id_detailOrder_router_val.getAttribute(
                'data-input_id_detailOrder_router');

            $.ajax({
                type: "GET",
                url: "{{ route('order.router') }}",
                data: {
                    input_QNT_router: input_QNT_router,
                    input_id_detailOrder_router: input_id_detailOrder_router
                },
                success: function(response) {

                    if (response.code == 1) {
                        toastr.success(response.msg);
                        // selectDataProductSession();
                        // $('#ProductSession').empty();
                        // $('.checkbox_QNT').focus()
                        // selectTotalProductSession() 
                    }
                }
            });
        });
    </script>
@endsection
