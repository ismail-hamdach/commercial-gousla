@extends('Dashboard.layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Gestion des
                clients</h2>
            <hr>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @else
        @endif
        <div class="card">
            <h5 class="card-header">Ajouter un client</h5>
            <div class="card-body">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                            <label>Nom de client <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old("name") }}" placeholder="nom de client" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                            <label>Email <span style="color:red;">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old("email") }}" placeholder="email" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                            <label for="validationCustom03">Adresse <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="addresse" value="{{ old("addresse") }}" placeholder="adresse" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                            <label>Téléphone <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="phone" value="{{ old("phone") }}" placeholder="Téléphone" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
                            <label for="validationCustom03">ICE :</label>
                            <input type="text" class="form-control" name="ICE" value="{{ old("ICE") }}" placeholder="ICE" >
                        </div>
                        <!--<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">-->
                        <!--    <label for="validationCustom03">Localisation :</label>-->
                        <!--       <button class=" btn-success form-control" type="button" onclick="prendreLocalisation()" >Prendre Localisation</button>-->
                        <!--</div>-->
                    </div>
                    <input type="text"  hidden class="form-control" id="Latitude" name="Latitude" value="{{ old("Latitude") }}" placeholder="Latitude" >
                    <input type="text"  hidden  class="form-control" id="Longitude" name="Longitude" value="{{ old("Longitude") }}" placeholder="Longitude" >
                    <div class="form-row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <button class="  btn-primary" type="submit">Valider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    if (window.ReactNativeWebView)  window.ReactNativeWebView.postMessage("location");
     window.addEventListener('message', (event) => {
            // Handle the message received from the native environment
            console.log('Received message from native:', event.data);
            let parsedData  = JSON.parse(event.data);
            document.getElementById("Longitude").value = parsedData.longitude ; 
            document.getElementById("Latitude").value = parsedData.latitude ; 
         });
    function prendreLocalisation(){
          const Latitude = document.getElementById("Latitude");
          const Longitude = document.getElementById("Longitude");
          function success(position) {  
            document.getElementById("Longitude").value = position.latitude ; 
            document.getElementById("Latitude").value = position.latitude ; 
            toastr.success("terminer avec succès")
        
          }
        
          function error() {
              toastr.error("Impossible de récupérer votre position")
          }
        
          if (!navigator.geolocation) {;
            toastr.success("La géolocalisation n'est pas supportée par votre navigateur")
          } else {
            navigator.geolocation.getCurrentPosition(success, error);
          }
    }
</script>
@endsection