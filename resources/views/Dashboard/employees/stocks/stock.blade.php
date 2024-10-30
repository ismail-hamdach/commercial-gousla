@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Gestion des depot pour les employees</h2>
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
                            <form action="" class="d-flex justify-center mb-5">
                                <div class="row">
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
                                        <a href="{{ route('employeesstocks.index') }}"
                                            class="btn btn-warning mt-3 btn_cancel rounded">Annuler</a>
                                    </div>
                                </div>
                            </form>
                            <h5 class="mb-0"></h5>
                            <a href="{{ route('employeesstocks.create') }}" class=" text-success">Ajouter un depot<i
                                    class=" fas fa-plus-circle"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <input type="hidden" value="Utilisateurs data" id="dataa">
                                <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Gérer par</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stocks as $stock)
                                            <tr>
                                                <td class="text-center">{{ $stock->id }}</td>
                                                <td class="text-center">{{ $stock->name }}</td>
                                                <td class="text-center">{{ $stock->user->name }}</td>
                                                <td style="width:10%;">
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('employeesstocks.edit', $stock->id) }}"
                                                            class="btn btn-primary rounded">Editer</a>
                                                        <a href="#" class="btn btn-danger rounded "
                                                            data-toggle="modal" data-stockId="{{ $stock->id }}"
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
                          {{--   <input type="hidden" id="product_ID" name="product_ID" value=""> --}}
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
            var stockId = button.attr('data-stockId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
           /*  modal.find('.modal-footer #categorie_ID').val(categoryId) */
            modal.find('form').attr('action', '/employeesstocks/' + stockId)
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
