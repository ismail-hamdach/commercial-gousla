@extends('Dashboard.layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between text-center">
                    <h2 class="pageheader-title">Gestion des Clients des employees</h2>
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
                            <h5 class="mb-0">Touts les Clients</h5>
                            {{-- <a href="{{ route('clients.create') }}" class=" text-success">Ajouter un Clients <i class=" fas fa-plus-circle"></i></a>         --}} 
                        </div>

                        <div class="card-body">
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
                                        <a href="{{ route('employees.clients') }}"
                                            class="btn btn-warning mt-3 btn_cancel rounded">Annuler</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <input type="hidden" value="Utilisateurs data" id="dataa">
                                <table id="example" class="table table-striped table-bordered second" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Localisation</th>
                                            <th>Adresse</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>ICE</th>
                                            @if (boolval($user_id == 0))
                                                <th>Gérer par</th>
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $client)
                                            <tr>
                                                <td>{{ $client->id }}</td>
                                                <td>{{ $client->name }}</td>
                                                <td style="width:10%;">
                                                    <div class="d-flex justify-content-between">
                                                            <button onclick="showLocation('{{ $client->Latitude  }}','{{ $client->Longitude  }}')" class="btn btn-success rounded">Location</button>
                                                    </div>
                                                </td>
                                                <td>{{ $client->addresse }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->phone }}</td>
                                                <td>{{ $client->ICE }}</td>
                                                @if (boolval($user_id == 0))
                                                    <td>{{ $client->user->name }}</td>
                                                @endif
                                                <td style="width:10%;">
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('clients.edit', $client->id) }}"
                                                            class="btn btn-primary rounded">Editer</a>
                                                        <a href="#" class="btn btn-danger rounded "
                                                            data-toggle="modal" data-clientId="{{ $client->id }}"
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
                            <input type="hidden" id="client_ID" name="client_ID" value="">
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

@section('js_client_page')
    <script>
        $('#deletemodal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var clientID = button.attr('data-clientId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #client_ID').val(clientID)
            modal.find('form').attr('action', '/clients/' + clientID)
        });
        
    function showLocation(lan ,lng){
        console.log(lan ,lng)
         if (window.ReactNativeWebView)  window.ReactNativeWebView.postMessage(`${lan},${lng}`);
         else open( `https://www.google.com/maps?q=${parseFloat(lan),parseFloat(lng)}&z=17&hl=fr` );
    }
     function getQrCode (clientName ,id){
          var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: id,
        width: 128,
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });

      // Wait a bit for the QR code to render
      setTimeout(async () => {
        // Capture the QR code element as an image
        const qrElement = document.getElementById('qrcode');
        const canvas = await html2canvas(qrElement);
        const imgData = canvas.toDataURL('image/png');

        // Create a new PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Add the QR code image to the PDF
        doc.addImage(imgData, 'PNG', 15, 40, 180, 180);

        // Optionally add text or other content
        doc.setFontSize(22);
        doc.text(clientName, 105, 30, null, null, 'center');
        // doc.text('devsolcom', 105, 30, null, null, 'center');

        // Save the PDF
        doc.save('QRCode.pdf');
        document.getElementById('qrcode').innerHTML =  '' ; 
      }, 500);
                 
     }
    
        $('#deletemodal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var clientID = button.attr('data-clientId') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-footer #client_ID').val(clientID)
            modal.find('form').attr('action','/clients/'+clientID)
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
