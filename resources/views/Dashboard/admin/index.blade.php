@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
                <h2 class="pageheader-title">Table de board</h2>
            <hr>
        </div>
    </div>
</div>
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
        <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
            <button type="submit" class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
            <a href="{{ route('dashboard') }}" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</a>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card"  style="box-shadow: 2px 2px 2px black;">
            <div class="card-body">
                <h5 class="">Total des commandes</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $totalOrder }}</h1>
                </div>
            </div>
            <div id="sparkline-revenue"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-a12">
        <div class="card"   style="background-color:#ffc108 ;">
            <div class="card-body">
                <h5 class="">Chiffre d'affaire / mois</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $Turnover }} DH</h1>
                </div>
                @if ($percentage > 0)    
                    <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                        <span><i class="fa fa-fw fa-arrow-up"></i></span><span>{{ number_format((float)$percentage ,2,',','') }}%</span>
                    </div>
                @else
                    <div class="metric-label d-inline-block float-right text-danger font-weight-bold">
                        <span><i class="fa fa-fw fa-arrow-down"></i></span><span>{{ $percentage }}%</span>
                    </div>
                @endif
            </div>
            <div id="sparkline-revenue2"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card"  style="background-color:rgb(37 213 242) ;">
            <div class="card-body">
                <h5 class="">Total des produits</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $Products }}</h1>
                </div>
            </div>
            <div id="sparkline-revenue3"></div>
        </div>
    </div>
    @if (Auth::user()->hasRole('admin|master'))
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card"  style="background-color:#bdc3ff ;">
                <div class="card-body">
                    <h5 class="">Profit</h5>
                    <div class="metric-value d-flex justify-between align-items-center w-100">
                        <h1 class="mb-1 d-none" id="profit-visible">{{ $profit }} DH</h1> <i class="fas fa-eye h3" style="cursor: pointer" id="btn-visible"></i>
                    </div>
                </div>
                <div id="sparkline-revenue4"></div>
            </div>
        </div>
    @endif
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card"  style="background-color:#0ee740 ;">
            <div class="card-body">
                <h5 class="">Commande</h5>
                <div class="metric-value d-inline-block">
                    <a href="{{ route('orders.create') }}" class="btn btn-success">Ajoute un Commande</a>
                </div>
            </div>
            <div id="sparkline-revenue4"></div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card"  style="box-shadow: 2px 2px 2px green;">
            <div class="card-body">
                <h5 class="">Commande valide / mois</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $totalOrdervalid }}</h1>
                </div>
            </div>
            <div id="sparkline-revenue4"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card"  style="box-shadow: 2px 2px 2px red;">
            <div class="card-body">
                <h5 class="">Commande Invalide / mois</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $totalOrderInvalid }}</h1>
                </div>
            </div>
            <div id="sparkline-revenue4"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card" style="box-shadow: 2px 2px 2px rgb(37, 151, 204);">
            <div class="card-body">
                <h5 class="">Total des client</h5>
                <div class="metric-value d-inline-block">
                    <h1 class="mb-1">{{ $clients }}</h1>
                </div>
            </div>
            <div id="sparkline-revenue4"></div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card" style="box-shadow: 2px 2px 2px rgb(37, 151, 204);">
            <div class="card-body">
                <h5 class="">Total des retours</h5>
                <div class="metric-value d-inline-block">
                {{$returnOrdersCount}} Pièces <h1 class="mb-1">{{abs($returnOrdersTotal)}} DH</h1>
                </div>
            </div>
            <div id="sparkline-revenue4"></div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class=" col-12">
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
                            @foreach($Orders as $order)
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
                                        @if(auth()->user()->hasRole('gerant BL|admin|master'))
                                         <a href="#"
                                            data-toggle="modal"
                                            data-orderId="{{ $order->id }}"
                                            data-target="#deletemodal"
                                            class="btn btn-danger rounded">Supprimer</a>
                                        @endif
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


        <!-- modal delete -->
    <!-- =============================== -->
    <div class="modal fade" id="modal_details" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <td >TOTAL GLOBAL</td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td colspan="4" id="Total" ></td>
                            </tr> 
                        </table>                       
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark rounded"
                        data-dismiss="modal">Annuler</button>
                        <form id="mini" action="" method="GET">
							@csrf
							<input id="imprimer_order_ID" name="order_ID" type="hidden" value="">
							<button class="btn btn-danger rounded" type="submit"
								onclick="event.preventDefault(); this.closest('form').submit();"> Imprimer petite forma</button>
						</form>
				 
                    <form action="" method="GET">
                        @csrf
                        <input type="hidden" id="imprimer_order_ID" name="order_ID" value="">
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-danger rounded">Imprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ====================================== -->
    <!-- end modal delete -->

    <!-- modal delete -->
    <!-- =============================== -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-dark rounded"
                        data-dismiss="modal">Annuler</button>
                    <form action="" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" id="order_ID" name="order_ID" value="">
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-danger rounded">Supprimer</button>
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
        $('#deletemodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var orderID = button.attr('data-orderId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #order_ID').val(orderID)
            modal.find('form').attr('action','/orders/'+orderID)
        });

        $('#modal_details').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var orderID = button.attr('data-orderId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #imprimer_order_ID').val(orderID)
            modal.find('form').attr('action','/orders/PDF/'+orderID)
            modal.find('#mini').attr('action', '/orders/PDF/mini/' + orderID )
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

        $(".table" ).on('click','.btn_details',function(e) {
                e.preventDefault();
                var ii = $(this).index('.btn_details')
                // var valueQNT= document.querySelectorAll(".btn_details")[ii].getAttribute('data-orderId');
                
                var data_ref =  document.querySelectorAll(".btn_details")[ii]
                var order_ref = data_ref.getAttribute('data-orderId');

                $.ajax({
                    type: "GET",
                    url: "{{ route('orders.details') }}",
                    data: {order_ref:order_ref},
                    success: function (response) {
                        $('#Data_Order').empty();
                        $.each(response.DetailsOrder, function (key,item) { 
                         $('#Data_Order').append("<tr><td>"+item.id+"</td><td>"+item.product_name+"</td><td>"+item.QNT+"</td><td>"+item.price+" DH</td><td>"+item.total+" DH</td></tr>");
                        });
                        $('#Total').text(response.total);
                    }
                });
            });

    </script>
@endsection

@section('js_index_page')
    <script>
        $(document).ready(function(){
            $('.btn_search').on('click',function(){
                var date_debut = $('.date_debut').val();
                var date_fin = $('.date_fin').val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard') }}",
                    data:{date_debut:date_debut,date_fin:date_fin},
                    success: function (data) {
                        console.log('good');
                    }
                });
            });
            $('#btn-visible').on('click',function(){
                $('#profit-visible').toggleClass('d-none');
            });
        });
    </script>
@endsection