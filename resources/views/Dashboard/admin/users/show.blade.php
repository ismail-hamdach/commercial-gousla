@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Gestion des utilisateurs</h2>

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
                        @if (Auth::user()->hasRole('master'))
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="mb-0">Touts les utilisateurs</h5>
                                <a href="{{ route('users.create') }}" class=" text-success">Ajouter un utilisateur <i
                                        class=" fas fa-plus-circle"></i></a>

                            </div>
                        @endif
                        <div class="card-body">
                            <div class="table-responsive">
                                <input type="hidden" value="Utilisateurs data" id="dataa">
                                <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        {{ $role->name }}
                                                    @endforeach
                                                </td>
                                                <td style="width:10%;">
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-primary rounded">Editer</a>
                                                        <a href="#" class="btn btn-danger rounded "
                                                            data-toggle="modal" data-userId="{{ $user->id }}"
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
                            <input type="hidden" id="user_ID" name="user_ID" value="">
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

@section('js_user_page')
    <script>
        $('#deletemodal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var userID = button.attr('data-userId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #user_id').val(userID)
            modal.find('form').attr('action', '/users/' + userID)
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
