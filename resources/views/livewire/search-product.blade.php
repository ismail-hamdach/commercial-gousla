<div class="col-sm-9 col-md-6 col-lg-6 card ">
    <div class="p-4">
        <h3>Les catégories <span style="color:red;">*</span></h3>
        <div class="d-flex">
            @if (auth()->user()->hasRole('admin'))
                <a href="#" data-toggle="modal" id="add_cetegorieOrder" data-target="#exampleModalCenter"
                    class="btn btn-success"><i class=" fas fa-plus-circle"></i></a>
            @endif
            <select name="categorie" id="categorie" wire:model="categ" class="w-100 form-control" autocomplete="on">
                <option value="" selected="false" >Choisissez une catégories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="p-4" id="DivProductSearch">
        <h3>Les produits <span style="color:red;">*</span></h3>
        <div class="d-flex">
            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('products.create') }}" class="btn btn-success"><i class=" fas fa-plus-circle"></i></a>
            @endif
            <input type="text" class="form-control" wire:model="searchTirm" placeholder="Rechercher un produit">
        </div>
        <div class="responsive-table p-4 h-full overflow-auto" id="ContentP">
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="card addToSession" style="cursor: pointer">
                       <!--  <img class="card-img-top" src="{{ asset('product_image/' . $product->image) }}"
                            alt="Card image cap"> -->
                        <div class="card-body">
                            <input type="hidden" name="idProduct[]" class="idProduct" value="{{ $product->id }}">
                            <input type="hidden" name="percentageProduct[]" class="percentageProduct"
                                value="{{ $product->percentage }}">
                            <input type="hidden" name="refProduct[]" class="refProduct" value="{{ $product->ref }}">
                            <input type="hidden" name="nameProduct[]" class="nameProduct" value="{{ $product->name }}">
                            <input type="hidden" name="priceProduct[]" class="priceProduct"
                                value="{{ $product->price }}">
                            <input type="hidden" name="QNTProduct[]" class="QNTProduct" value="{{ $product->QNT }}">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <div class="d-flex justify-content-between">
                                <span>{{ $product->price }} DH</span>
                                <span>Qté : {{ $product->QNT }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@section('js_product')
    {{-- @if (Session::has('error'))
    <script>
        toastr.error("{!! Session::get('error') !!}")
    </script>
    @endif --}}
    <script>
        $("#ContentP").on('click', '.addToSession', function() {
            var ii = $(this).index('.addToSession')
            var idProduct = document.querySelectorAll(".idProduct")[ii].value
            var refProduct = document.querySelectorAll(".refProduct")[ii].value
            var percentageProduct = document.querySelectorAll(".percentageProduct")[ii].value
            var nameProduct = document.querySelectorAll(".nameProduct")[ii].value
            var priceProduct = document.querySelectorAll(".priceProduct")[ii].value
            var QNTProduct = document.querySelectorAll(".QNTProduct")[ii].value

            $.ajax({
                type: "POST",
                url: "{{ route('store.session') }}",
                data: {
                    idProduct: idProduct,
                    percentageProduct: percentageProduct,
                    refProduct: refProduct,
                    nameProduct: nameProduct,
                    priceProduct: priceProduct,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.code == 1) {
                        toastr.success(data.msg);
                        selectDataProductSession();
                        selectTotalProductSession();
                        $('#ProductSession').empty();
                    }
                    if (data.code == 2) {
                        toastr.error(data.msg);
                    }
                    if (data.code == 3) {
                        toastr.error(data.msg);
                    }
                }
            });
        });
        //select data of product session
        function selectDataProductSession() {
            $.ajax({
                type: "GET",
                url: "{{ route('orders.fetchDataProductSession') }}",
                dataType: 'json',
                success: function(response) {
                    $.each(response.productSession, function(key, item) {
                        $('#ProductSession').append("<tr>" +
                            "<th scope='row'><input type='hidden' name='ref_product_added[]' value='" +
                            item.ref +
                            "' class='checkbox_ID' min=1 style='width: 60px'> <button data-id='" +
                            item.product_ref +
                            "' class='btn btn-light delete_ProdectSession'><i class='text-danger fas fa-trash'></i></button></th>" +
                            "<td><input type='hidden' name='name_product_added[]' value='" + item
                            .name + "' class='checkbox_Name' min=1 style='width: 60px'>" + item
                            .name + "</td>" +
                            "<td><input type='number' name='price_product_added[]' value='" + item
                            .peice + "' class='checkbox_Price' style='width: 60px'></td>" +
                            "<td><input type='number' name='QNT_product_added[]' value='" + item
                            .QNT + "' class='checkbox_QNT' min=1 style='width: 60px'></td>" +
                            "<td><input type='hidden' name='percentage_product_added[]' value='" +
                            item.percentage +
                            "' class='checkbox_percentage' min=1 style='width: 60px'>" + item
                            .percentage + "</td>" +
                            "<td><input type='hidden' name='profit_product_added[]' value='" + item
                            .profit + "' class='checkbox_profit' min=1 style='width: 60px'>" + item
                            .profit + "</td>" +
                            "<td>" + item.total + "</td>" +
                            "</tr>"
                        );
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
        selectDataProductSession();

        $("#ProductSession").on('click', '.delete_ProdectSession', function(e) {
            e.preventDefault();
            var ii = $(this).index('.delete_ProdectSession')

            var data_id = document.querySelectorAll(".delete_ProdectSession")[ii]
            var id = data_id.getAttribute('data-id');

            $.ajax({
                type: "GET",
                url: "{{ route('orders.DeleteDataProductSession') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.code == 1) {
                        toastr.success(data.msg);
                        $('#ProductSession').empty();
                        selectDataProductSession();
                        selectTotalProductSession();

                    }
                }
            });
        });

        function selectTotalProductSession() {
            $.ajax({
                type: "GET",
                url: "{{ route('orders.fetchTotalProductSession') }}",
                dataType: 'json',
                success: function(response) {
                    $('.Total').text(response + " DH");
                    $('.TotalInput').val(response);
                }
            });
        }
        selectTotalProductSession();

        $("#ProductSession").on('focusout', '.checkbox_QNT', function() {
            var ii = $(this).index('.checkbox_QNT')
            var valueQNT = document.querySelectorAll(".checkbox_QNT")[ii].value
            var valueProfit = document.querySelectorAll(".checkbox_profit")[ii].value
            //var valuePercentage= document.querySelectorAll(".checkbox_percentage")[ii].value
            var data_id = document.querySelectorAll(".delete_ProdectSession")[ii]
            var product_ref = data_id.getAttribute('data-id');

            $.ajax({
                type: "GET",
                url: "{{ route('orders.UpdateDataProductSession') }}",
                data: {
                    valueQNT: valueQNT,
                    product_ref: product_ref,
                    valueProfit: valueProfit
                },
                success: function(data) {
                    if (data.code == 1) {
                        toastr.success(data.msg);
                        $('#ProductSession').empty();
                        selectDataProductSession();
                        $('.checkbox_QNT').focus();
                        selectTotalProductSession();

                    }
                    if (data.code == 2) {
                        toastr.error(data.msg);
                        $('#ProductSession').empty();
                        selectDataProductSession();
                        $('.checkbox_QNT').focus();
                        selectTotalProductSession();

                    }
                }
            });
        });

        //update price
        $("#ProductSession").on('focusout', '.checkbox_Price', function() {
            var ii = $(this).index('.checkbox_Price')
            var p_valueQNT = document.querySelectorAll(".checkbox_QNT")[ii].value
            var p_valueProfit = document.querySelectorAll(".checkbox_profit")[ii].value
            var p_checkbox_Price = document.querySelectorAll(".checkbox_Price")[ii].value
            var p_data_id = document.querySelectorAll(".delete_ProdectSession")[ii]
            var p_product_ref = p_data_id.getAttribute('data-id');

            $.ajax({
                type: "GET",
                url: "{{ route('orders.UpdatePriceProductSession') }}",
                data: {
                    valueQNT: p_valueQNT,
                    product_ref: p_product_ref,
                    valueProfit: p_valueProfit,
                    checkbox_Price: p_checkbox_Price
                },
                success: function(data) {
                    if (data.code == 1) {
                        toastr.success(data.msg);
                        $('#ProductSession').empty();
                        selectDataProductSession();
                        $('.checkbox_Price').focus();
                        selectTotalProductSession();

                    }
                    if (data.code == 2) {
                        toastr.error(data.msg);
                        $('#ProductSession').empty();
                        selectDataProductSession();
                        $('.checkbox_Price').focus();
                        selectTotalProductSession();

                    }
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
                    // $('#model_depot').modal('toggle');
                    // toastr.success("L'ajout avec succès")
                    //console.log(response.depots);
                    $.each(response.depots, function(key, item) {
                        $('#depotOrder').append("<option value='" + item.email + "'>" + item.name +
                            "</option>");
                    });
                }
            });
        }
        selectDataDepot();
    </script>
@endsection
