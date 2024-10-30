@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <div class="d-flex justify-content-between text-center">
                <h2 class="pageheader-title">Gestion des Clients</h2>
            </div>
            <hr>
        </div>
    </div>
</div>
  <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">check client</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-2 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_check_client">
            </div>
            <div class="col-md-2 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_get_check_client">
            </div>
            <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <label for="">Client</label>
                        <select class="form-control" name="client" id="client">
                            <option value="">Choisir Un Employee</option>
                            @foreach ($clients as $id => $client)
                                <option value="{{ $id }}">{{ $client }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            <div class="col-md-2 col-sm-12">
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
            <div class="col-md-2 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchCheckCleints()"
                    class="btn btn-info mt-3 btn_search rounded">Recherche</button>&nbsp;
                <button onclick="getCheckClients()" class="btn btn-warning mt-3 btn_cancel rounded">Annuler</button>
            </div>
            <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
        </div>
    </div>
    <div class="table-responsive bg-white">
        <table class="table">
            <thead class="bg-light">
                <tr class="border-0">
                    <th>Utilisateur</th>
                    <th>Nom Client</th>
                    <th>date</th>
                </tr>
            </thead>
            <tbody id="checkClient">
            </tbody>
        </table>
    </div>


@endsection

@section('js_client_page')
    <script>
        function getCheckClient(startDate = null, endDate = null , client =  null  , employee = null ) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/check',
                method: 'POST',
                data: {
                    _token: token,
                    client: client,
                    employee: employee,
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#checkClient').html('');
                    $(result).each((item, data) => {
                        $('#checkClient').append(`
                            <tr>
                                <td>${ data.user }</td>
                                <td>${ data.client }</td>
                                <td>${ data.date }</td>
                            </tr>
                        `);
                    })
                }
            });
        }

      
        function searchCheckCleints() {
            let startDate = document.getElementById('date_debut_check_client').value;
            let endDate = document.getElementById('date_fin_get_check_client').value;
            let employee = document.getElementById('employee').value;
            let client = document.getElementById('client').value;
            getCheckClient(startDate, endDate ,client ,employee );
        }

        getCheckClient();
    </script>

@endsection