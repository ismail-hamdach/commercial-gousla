@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Editer une commande</h2>
                </div>
                <hr>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="p-4 card">
                        <h3>Informations de la commande</h3>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>ID</label>
                                <input type="text" class="form-control" value="{{ $order->id }}" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Référence</label>
                                <input type="text" id="_ref" class="form-control" value="{{ $order->ref }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Nom de client</label>
                                <input type="text" class="form-control" value="{{ $order->client->name }}" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>E-Mail</label>
                                <input type="text" class="form-control" value="{{ $order->client->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Phone</label>
                                <input type="text" class="form-control" value="{{ $order->client->phone }}" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Adresse</label>
                                <input type="text" class="form-control" value="{{ $order->client->addresse }}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Total</label>
                                <input type="text" class="form-control" value="{{ $order->total }} DH" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-6 mb-2">
                                <label>Profit</label>
                                <input type="text" class="form-control"
                                    value="{{ $order->DetailsOrdes->sum('profit') }} DH" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-2">
                                <label>Methode de Paiement</label>
                                <input type="text" class="form-control" value="{{ $order->payment_method }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 card">
            <div class="p-4">
                <h3>Les catégories <span style="color:red;">*</span></h3>
                <div class="d-flex">
                    @if (auth()->user()->hasRole('admin'))
                        <a href="#" data-toggle="modal" id="add_cetegorieOrder" data-target="#exampleModalCenter"
                            class="btn btn-success"><i class=" fas fa-plus-circle"></i></a>
                    @endif
                    <select name="categorie" id="categorie" wire:model="categ" class="w-100 form-control" autocomplete="on"
                        onchange="searchForProductsByCategory(this.value)">
                        <option selected="false" disabled="disabled">Choisissez une catégories</option>
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
                        <a href="{{ route('products.create') }}" class="btn btn-success"><i
                                class=" fas fa-plus-circle"></i></a>
                    @endif
                    <input type="text" class="form-control" wire:model="searchTirm" placeholder="Rechercher un produit"
                        oninput="searchForProductsByName(this.value)">
                </div>
                <div class="responsive-table p-4 overflow-auto" id="ContentP">
                    <div class="product-grid">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>QNT</th>
                                    <th>Prix</th>
                                    <th>Percentage</th>
                                    <th>Profit</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orderdetails">
                                @foreach ($orderData['DetailsOrder'] as $key => $detailOrder)
                                    <tr id="_{{ $key }}_">

                                        <input type="hidden" id="_{{ $key }}_idDetail"
                                            value="{{ $detailOrder->id }}" />

                                        <td class="text-center">{{ $detailOrder->product_name }}</td>
                                        <td class="text-center">
                                            <input type='number' class='input_QNT_router' id="_{{ $key }}_qnt"
                                                style='width:60px;' min="1"
                                                onchange="updateRowData(this, '{{ '_' . $key }}')"
                                                value='{{ $detailOrder->QNT }}' />
                                        </td>
                                        <td class="text-center">
                                            <span id="_{{ $key }}_price">{{ $detailOrder->price }}</span> DH
                                        </td>
                                        <td class="text-center">
                                            <span
                                                id="_{{ $key }}_percentage">{{ $detailOrder->percentage }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span id="_{{ $key }}_profit">{{ $detailOrder->profit }}</span> DH
                                        </td>
                                        <td class="text-center">
                                            <span id="_{{ $key }}_total">{{ $detailOrder->total }}</span> DH
                                        </td>
                                        <td class="text-center">
                                            <form id="_{{ $key }}_form" method="POST"
                                                action="/orderdetail/{{ $detailOrder->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="dOID" value="{{ $detailOrder->id }}" />
                                                <button class="btn btn-danger" type="button"
                                                    onclick="deleteDetailProduct('_{{ $key }}_form')">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <button class="btn btn-success w-100" onclick="updateCommande()">Valider</button>
                </div>
            </div>
        </div>
    </div>

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
        let selectedCategoryId;
        var products;

        const updateRowData = (elmnt, keyID) => {
            let quantity = elmnt.value;
            let unitPrice = $(`#${ keyID }_price`).text();
            let profitPercentage = $(`#${ keyID }_percentage`).text();

            let totalPrice = (parseFloat(unitPrice) * parseInt(quantity)).toFixed(2);
            let totalProfit = ((totalPrice * parseFloat(profitPercentage)) / 100).toFixed(2);

            $(`#${ keyID }_total`).text(totalPrice);
            $(`#${ keyID }_profit`).text(totalProfit);
        }

        const deleteDetailProduct = (formId) => {
            if (confirm('Voulez-vous vraiment supprimé ce article?'))
                $(`#${formId}`).submit();
        }

        const updateCommande = () => {
            let orderDetails = [];
            let newCommandes = [];

            [...$('#orderdetails').children()].forEach((tr) => {
                const trID = tr.id;
                if (tr.querySelector(`#isNewOrderDetail${trID}`)) {
                    newCommandes = [
                        ...newCommandes, {
                            quantity: $(`#${trID}_qnt`).val(),
                            profit: $(`#${trID}_profit`).text(),
                            total: $(`#${trID}_total`).text(),
                            product_name: $(`#${trID}_productName`).text(),
                            percentage: $(`#${trID}_percentage`).text(),
                            price: $(`#${trID}_price`).text(),
                        }
                    ];


                } else {
                    orderDetails = [...orderDetails, {
                        id: $(`#${trID}idDetail`).val(),
                        quantity: $(`#${trID}qnt`).val(),
                        profit: $(`#${trID}profit`).text(),
                        total: $(`#${trID}total`).text(),
                    }];
                }

            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                url: "/updateorderdetail",
                method: 'PUT',
                data: {
                    data: JSON.stringify({
                        oldCommandes: orderDetails,
                        newCommandes,
                    }),
                    ref: $('#_ref').val(),
                },
                success: () => {
                    window.location = "/orders";
                },
                error: (err) => {
                    alert(err.responseJSON().message);
                }
            })
        }

        const searchForProductsByName = (productName) => {

            if (typeof selectedCategoryId != 'undefined') {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });

                $.ajax({
                    url: "/productsbyname",
                    method: 'POST',
                    data: {
                        cId: selectedCategoryId,
                        pName: productName
                    },
                    success: (res) => {
                        $('.product-grid').html(``);

                        if (res.products.length) {

                            window.products = res.products;

                            res.products.forEach((product, key) => {
                                $('.product-grid').append(
                                    `
                                    <div class="card addToSession" style="cursor: pointer" onclick="addProductToList(${key}, '_${product.id}_newProduct')">
                                        <img class="card-img-top" src="/product_image/${product.image}" alt="Card image cap">
                                        <div class="card-body">
                                            <input type="hidden" id="" />
                                            <h5 class="card-title" id="productName_${product.id}">${product.name}</h5>
                                            <div class="d-flex justify-content-between">
                                                <span id="productPrice_${product.id}">${product.price} DH</span>
                                                <span id="productQuantity_${product.id}">Qté : ${product.QNT}</span>
                                            </div>
                                        </div>
                                    </div>
                                `
                                )
                            });

                        }

                    },
                    error: () => {
                        alert('Erreur pendant le demande');
                    }
                })
            }


        }

        const searchForProductsByCategory = (categoryId) => {

            selectedCategoryId = categoryId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                url: "/productsbycategorie",
                method: 'POST',
                data: {
                    cId: categoryId
                },
                success: (res) => {
                    $('.product-grid').html(``);

                    if (res.products.length) {

                        window.products = res.products;

                        res.products.forEach((product, key) => {
                            $('.product-grid').append(
                                `
                                <div class="card addToSession" style="cursor: pointer" onclick="addProductToList(${key}, '_${product.id}_newProduct')">
                                    <img class="card-img-top" src="/product_image/${product.image}" alt="Card image cap">
                                    <div class="card-body">
                                        <input type="hidden" id="" />
                                        <h5 class="card-title" id="productName_${product.id}">${product.name}</h5>
                                        <div class="d-flex justify-content-between">
                                            <span id="productPrice_${product.id}">${product.price} DH</span>
                                            <span id="productQuantity_${product.id}">Qté : ${product.QNT}</span>
                                        </div>
                                    </div>
                                </div>
                            `
                            )
                        });

                    }

                },
                error: () => {
                    alert('Erreur pendant le demande');
                }
            })
        }

        const addProductToList = (ID, TagID) => {
            let newProduct = true;

            [...$('#orderdetails').children()].forEach((tr) => {
                if (tr.querySelector(`#isNewOrderDetail${TagID}`)) {

                    tr.querySelector(`#${TagID}_qnt`).value = parseInt(tr.querySelector(`#${TagID}_qnt`)
                        .value) + 1;
                    tr.querySelector(`#${TagID}_total`).innerText = parseInt(tr.querySelector(`#${TagID}_qnt`)
                        .value) * parseFloat(tr.querySelector(`#${TagID}_price`).innerText)
                    tr.querySelector(`#${TagID}_profit`).innerText = (parseFloat(tr.querySelector(
                        `#${TagID}_total`).innerText) * parseFloat(tr.querySelector(
                        `#${TagID}_percentage`).innerText)) / 100

                    newProduct = false;
                }
            });

            if (newProduct) {

                $('#orderdetails').append(`
                <tr id="${TagID}">

                    <input type="hidden" id="isNewOrderDetail${TagID}" value="1" />

                    <td class="text-center" id="${TagID}_productName">${window.products[ID].name}</td>
                    <td class="text-center">
                        <input type='number' class='input_QNT_router' id="${TagID}_qnt" style='width:60px;' min="1" onchange="updateRowData(this, '${TagID}')" value='1' />
                    </td>
                    <td class="text-center">
                        <span id="${TagID}_price">${window.products[ID].price}</span> DH
                    </td>
                    <td class="text-center">
                        <span id="${TagID}_percentage">${window.products[ID].percentage}</span>
                    </td>
                    <td class="text-center">
                        <span id="${TagID}_profit">${window.products[ID].profit}</span> DH
                    </td>
                    <td class="text-center">
                        <span id="${TagID}_total">${window.products[ID].price}</span> DH
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger" type="button" onclick="deleteNewProduct('${TagID}')">Supprimer</button>
                    </td>
                </tr>
            `)

            }
        }

        const deleteNewProduct = (id) => {
            $(`#${id}`).remove();
        }
    </script>
@endsection
