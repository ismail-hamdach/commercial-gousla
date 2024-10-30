@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Meilleur Classement Clients</h2>
                <hr>
            </div>
        </div>
    </div>
    <div class="d-flex justify-center mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-12 ">
                <label for="">Date début</label>
                <input type="date" class="form-control date_debut" id="date_debut_client">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Date fin</label>
                <input type="date" class="form-control date_fin" id="date_fin_client">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="">Clients</label>
                <select class="form-control" id="client">
                    <option value="0">Tous</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-12 d-flex justify-center align-middle">
                <button type="button" onclick="searchChiffreAffireClient()"
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
                        <th>Nom Client</th>
                        <th>Chiffre Affaire</th>
                    </tr>
                </thead>
                <tbody id="bestChiffreClient">
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
@endsection

@section('js_index_page')
    <script>
        function getChiffreAffaireClient(startDate = null, endDate = null, clientId = null) {
            var token = $("#csrf").val();

            $.ajax({
                url: '/statistics/clientclassement',
                method: 'POST',
                data: {
                    _token: token,
                    _target: 'chiffreAffaireClient',
                    clientId: clientId,
                    startDate: startDate,
                    endDate: endDate,
                },
                success: (result) => {
                    $('#bestChiffreClient').html('');
                    $(result).each((item, data) => {
                        $('#bestChiffreClient').append(`
                            <tr>
                                <td>${ data.name }</td>
                                <td>${ data.chiffreAffaire } DH</td>
                            </tr>
                        `);
                    })
                }
            });
        }

        function searchChiffreAffireClient() {
            let startDate = document.getElementById('date_debut_client').value;
            let endDate = document.getElementById('date_fin_client').value;
            let clientId = document.getElementById('client').value;
            getChiffreAffaireClient(startDate, endDate, clientId);
        }

        getChiffreAffaireClient();
    </script>
@endsection
