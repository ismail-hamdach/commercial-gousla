<div class="p-4 card" id='SelectClient'>
    <h3>Les clients <span style="color:red;">*</span></h3>
    <div class="d-flex ">
        <a href="{{ route('clients.create') }}" class="btn btn-success"><i class=" fas fa-plus-circle"></i></a>
        <input type="text" id="clientSearch" wire:model="searchTirm" class="form-control" placeholder="Rechercher un client" autocomplete="off">
    </div>
    <div id="DivClientSearch" class=" w-100 p-3 border" style="max-height: 230px;overflow-y: scroll;">
        @forelse ($clients as $client)
            <div class='content_searchClient' class='border-bottom p-1' style="cursor: pointer">
                <div class='row'>
                    <div class='col-md-12 font-bold'>{{ $client->name }}</div>
                    <input type="hidden" name="idSearch" class="idSearch" value="{{ $client->id }}">
                    <input type="hidden" name="nameSearch" class="nameSearch" value="{{ $client->name }}">
                    <input type="hidden" name="phoneSearch" class="phoneSearch" value="{{ $client->phone }}">
                    <input type="hidden" name="emailSearch" class="emailSearch" value="{{ $client->email }}">
                </div>
                <div class='row'> 
                    <div class='col-md-12' id="phone">{{ $client->phone }}</div>
                </div>
            </div>
            <hr>
        @empty
            <div class='content_searchClient' class='border-bottom p-1'>
                <div class='row'> 
                    <div class='col-md-12'>Aucune résultat</div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<div class="card d-none" id='cardClient'>
    <div class="p-4">
        <h3>Client</h3>
        <div class="d-flex align-items-center">
            <img src="{{ asset('assets/images/user.png') }}" style="height: 60px;" alt="">
            <div class="ml-4">
                <div id="Name" class="h3"></div>
                <div id="Email"></div>
                <div id="Phone"></div>
            </div>
        </div>
    </div>
    <span  class="btn btn-danger btn_Delete">Supprimer</span>
</div>

@section("js_client")
<script>

     $("#DivClientSearch").on('click','.content_searchClient',function() {
        var ii = $(this).index('.content_searchClient')
        var name= document.querySelectorAll(".nameSearch")[ii].value
        var phone= document.querySelectorAll(".phoneSearch")[ii].value
        var email= document.querySelectorAll(".emailSearch")[ii].value
        
        // document.getElementById('Name').innerHTML = name;
        // document.getElementById('Email').innerHTML = email;
        // document.getElementById('Phone').innerHTML = phone;
        // $('#cardClient').toggleClass('d-none');
        // $('#SelectClient').toggleClass('d-none');
        console.log(ii,name,phone,email);
        
    });

    

    $('.btn_Delete').on('click',function(){
        $('#cardClient').toggleClass('d-none');
        $('#SelectClient').toggleClass('d-none');
        $('.nameSearch').val('');
        $('.emailSearch').val('');
        $('.phoneSearch').val('');
        $('#clientSearch').val('');
        $('.content_searchClient').empty();
    });

</script>

@endsection
