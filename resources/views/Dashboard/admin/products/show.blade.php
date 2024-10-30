@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Gestion des produits</h2>
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
                            <a href="{{ route('products.create') }}" class=" text-success">Ajouter un produit <i
                                    class=" fas fa-plus-circle"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <input type="hidden" value="Utilisateurs data" id="dataa">
                                <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Référence</th>
                                            <th>Nom</th>
                                            <th title="prix d'achat">Prix d'achat   </th>
                                            <th title="prix de vent">Prix  (DH)</th>
                                            <th>Quantité</th>
                                            <th>Percentage %</th>
                                            <th>Disponibilité</th>
                                            <th>Image</th>
                                            <th>Dépot</th>
                                            <th>Catégorie</th>
                                            <th>Gérer par</th>
                                            @if (Auth::user()->hasRole('admin|gerant product') || Auth::user()->hasRole('master'))
                                                <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-center">{{ $product->id }}</td>
                                                <td class="text-center">{{ $product->ref }}</td>
                                                <td class="text-center">{{ $product->name }}</td>
                                                <td class="text-center">{{ $product->priceAchat }}</td>
                                                <td class="text-center">{{ $product->price }}</td>
                                                <td class="text-center">{{ $product->QNT }}</td>
                                                <td class="text-center">{{ $product->percentage }} %</td>
                                                <td class="text-center">
                                                    @if ($product->QNT > 0)
                                                        <span class="badge badge-success">Disponible</span>
                                                    @else
                                                        <span class="badge badge-danger">indisponible</span>
                                                    @endif
                                                </td>
                                                <td class="text-center"><img
                                                        src="{{ asset('product_image/' . $product->image) }}" width="50px"
                                                        alt=""></td>
                                                <td class="text-center">{{ $product->depot->name }}</td>
                                                <td class="text-center">{{ $product->categorie->name }}</td>
                                                <td class="text-center">{{ $product->user->name }}</td>
                                                @if (Auth::user()->hasRole('admin|gerant product') || Auth::user()->hasRole('master'))
                                                    <td style="width:10%;">
                                                        <div class="d-flex justify-content-between">
                                                            <a href="{{ route('products.edit', $product->id) }}"
                                                                class="btn btn-primary rounded">Editer</a>
                                                            <a href="#" class="btn btn-danger rounded "
                                                                data-toggle="modal" data-productId="{{ $product->id }}"
                                                                data-target="#deletemodal">Supprimer</a>
                                                        </div>
                                                    </td>
                                                @endif
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
                            <input type="hidden" id="product_ID" name="product_ID" value="">
                            <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="btn btn-danger rounded">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================================== -->
        <!-- end modal delete -->
    </div>
@endsection

@section('js_product_page')
    <script>
        $('#deletemodal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var productID = button.attr('data-productId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #product_ID').val(productID)
            modal.find('form').attr('action', '/products/' + productID)
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
