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
    <div class="row">

        <div class="col-9">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card" style="box-shadow: 2px 2px 2px black;">
                        <div class="card-body">
                            <h6 class="">Total des vente</h6>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1"></h1>
                                <div id="totalPrice"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card" style="box-shadow: 2px 2px 2px green;">
                        <div class="card-body">
                            <h6 class="">Total des Commande </h6>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1"></h1>
                                <div id="totalCommands"></div>
                            </div>
                        </div>
                    </div>
                </div>
 
              
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card" style="box-shadow: 2px 2px 2px red;">
                        <div class="card-body">
                            <h6 class="">Product Plus Vendu</h6>
                            <div class=" d-inline-block">
                                <div id="productPlusVendu" style="font-size:medium "></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card" style="box-shadow: 2px 2px 2px rgb(146, 207, 66);">
                        <div class="card-body">
                            <h6 class="">Cpmmission</h6>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1"></h1>
                                <div id="commission"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card" style="box-shadow: 2px 2px 2px rgb(37, 151, 204);">
                        <div class="card-body">
                            <h6 class="">Commercial</h6>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1"></h1>
                                <div id="commercial"></div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>


        <form action="" class="d-flex justify-center mb-5 col-3">
            <div class="row">
                <input type="text" name="CSRF" id="CSRF" hidden value="{{ Session::token() }}">
                <div class="col-12 ">
                    <label for="">Date début</label>
                    <input type="date" class="form-control date_debut" name="date_debut">
                </div>
                <div class="col-12">
                    <label for="">Date fin</label>
                    <input type="date" class="form-control date_fin" name="date_fin">
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Employee</label>
                        <select class="form-control" name="employee" id="employee">
                            <option value="">Choisir Un Employee</option>
                            @foreach ($employyes as $id => $employye)
                                <option value="{{ $id }}">{{ $employye }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex justify-center align-middle">
                    <button type="submit" class="btn btn-info col-6 mt-3 btn_search rounded">Recherche</button>&nbsp;
                    <a href="{{ url('statistics/repport') }}"
                        class="btn col-6 btn-warning mt-3 btn_cancel rounded ">Annuler</a>
                </div>
                <button type="button" class="btn col-11 center ml-3  mt-3 text-white btn_pdf rounded" style="background: rgb(53, 90, 214);">Telecharger
                    PDF</button>&nbsp;
            </div>
        </form>





    </div>
@endsection

@section('js_index_page')
    <script>
        $('.btn_search').on('click', function(e) {
            e.preventDefault()
            fetchData()
        })

        function fetchData() {
            var date_debut = $('.date_debut').val();
            var date_fin = $('.date_fin').val();
            var totalCommand = $('#totalCommands').text()
            var productPlusVendu = $('#productPlusVendu').text()
            var commission = $('#commission').text()
            var commercial = $('#commercial').text()
            var employee = $('#employee').val()
            var csrf = $('#CSRF ').val();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                url: "{{ route('searchStatistics') }}",
                data: {
                    date_debut,
                    date_fin,
                    employee
                },
                success: function(data) {
                    if (data.commercial == null) data.commercial = {
                        name: null
                    };
                    $('#commercial').text(data.commercial.name)
                    if (data.prdPlusVendu == null) data.prdPlusVendu = {
                        product_name: null
                    }
                    $('#productPlusVendu').text(data.prdPlusVendu.product_name)
                    if (data.totalOrders == null) data.totalOrders = {
                        profit: null
                    }
                    $('#commission').text(data.totalOrders.profit)
                    if (data.totalOrders == null) data.totalOrders = {
                        totalOrders: null,
                        totalPrice: null
                    }
                    $('#totalCommands').text(data.totalOrders.totalOrders)
                    $('#totalPrice').text(data.totalOrders.totalPrice)


                }
            });
        };
        document.addEventListener('DOMContentLoaded', function() {
            fetchData();
        });
        $('.btn_pdf').on('click', function(e) {
            e.preventDefault()
            var date_debut = $('.date_debut').val();
            var date_fin = $('.date_fin').val();
            var employee = $('#employee').val()
            var csrf = $('#CSRF ').val();
            var totalCommand = $('#totalCommands').text()
            var totalPrice = $('#totalPrice').text()
            var productPlusVendu = $('#productPlusVendu').text()
            var commission = $('#commission').text()
            var commercial = $('#commercial').text()
            console.log(date_debut,date_fin,csrf,employee,totalCommand,commission,commercial,productPlusVendu)
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrf
                },

                url: "{{ route('pdfSearchStatistics') }}",
                data: {
                    date_debut:date_debut,
                    date_fin:date_fin,
                    totalCommand:totalCommand,
                    productPlusVendu:productPlusVendu,
                    totalPrice:totalPrice,
                    commercial:commercial,
                    commission:commission,
                    employee:employee
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(res) {
                  var blob = new Blob([res], { type: 'application/pdf' });
                    var link = document.createElement('a');
                    link.download = 'Rapport.pdf';
                    link.href = window.URL.createObjectURL(blob);
                    console.log(res);
                    console.log(blob);
                    console.log(link.href);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                
                }
                
              });
        })
       
    </script>
@endsection
