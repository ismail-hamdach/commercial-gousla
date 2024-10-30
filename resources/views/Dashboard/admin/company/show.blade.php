@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <div class="d-flex justify-content-between text-center">
                <h2 class="pageheader-title">Paramètres de l'entreprise</h2>
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
                        <a href="{{ route('companys.create') }}" class=" text-success">Ajouter paramètres de l'entreprise <i class=" fas fa-plus-circle"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <input type="hidden" value="Utilisateurs data" id="dataa">
                            <table id="example" class="table table-striped table-bordered second"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>adresse</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>GSM</th>
                                        <th>ICE</th>
                                        <th>RC</th>
                                        <th>TP</th>
                                        <th>IF</th>
                                        <th>logo</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companys as $company)
                                        <tr>
                                            <td>{{ $company->id }}</td>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->addresse }}</td>
                                            <td>{{ $company->email }}</td>
                                            <td>{{ $company->phone }}</td>
                                            <td>{{ $company->GSM }}</td>
                                            <td>{{ $company->ICE }}</td>
                                            <td>{{ $company->RC }}</td>
                                            <td>{{ $company->TP }}</td>
                                            <td>{{ $company->IF }}</td>
                                            <td class="text-center"><img src="{{ asset('Company_logo/'. $company->logo ) }}" width="50px" alt=""></td>
                                            <td style="width:10%;">
                                                <div class="d-flex justify-content-between">
                                                        <a href="{{ route('companys.edit',$company->id) }}" class="btn btn-primary rounded">Editer</a>
                                                        <a href="#"
                                                            class="btn btn-danger rounded "
                                                            data-toggle="modal"
                                                            data-companyId="{{ $company->id }}"
                                                            data-target="#deletemodal">Supprimer</a>
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
                        <input type="hidden" id="company_ID" name="company_ID" value="">
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" class="btn btn-danger rounded">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ====================================== -->
    <!-- end modal delete -->
</div>
@endsection

@section('js_company_page')
    <script>
        $('#deletemodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var companyID = button.attr('data-companyId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #company_ID').val(companyID)
            modal.find('form').attr('action','/companys/'+companyID)
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