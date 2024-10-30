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
<div class="row" >
   
 <div id="reader" ></div>
  <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
</div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"   type="text/javascript"></script>
    <script>
      var scaned= false ;  
      function onScanSuccess(decodedText, decodedResult) {
          // handle the scanned code as you like, for example:
            if (window.scaned) return 0 ; 
                 $.ajax({
                    type: "POST",
                    url: "{{ route('client.scaned') }}",
                    data: {
                        clientId: decodedText,
                        _token: document.getElementById('csrf').value
                    },
                    success: function(response) {
                        window.scaned =  true ; 
                        toastr.success(response.msg);
                        window.location.href =  '/';
                    },
                    error: function(xhr) {
                        toastr.error(xhr.msg); ;
                        toastr.error("Scan avec error"); ;
                    }
               });
           
        }
        
        function onScanFailure(error) {
          // handle scan failure, usually better to ignore and keep scanning.
          // for example:
          console.warn(`Code scan error = ${error}`); 
        }
        
        let html5QrcodeScanner = new Html5QrcodeScanner(
          "reader",
          { fps: 10, qrbox: {width: 250, height: 250} },
          /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        document.getElementById('html5-qrcode-anchor-scan-type-change').hidden  = true
        document.querySelector('[alt="Info icon"]').hidden  = true
        document.getElementById('html5-qrcode-button-camera-permission').classList.add("btn", "btn-primary", "rounded") 
        document.getElementById('reader').style.border ="none"; 
        setTimeout(()=>{
            document.querySelector('[alt="Camera based scan"]').src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAACXBIWXMAAAsTAAALEwEAmpwYAAADfElEQVR4nO2bz0uUQRjHP+XiWrDZJSKiqLRtQQhcQ/bSQRBaMMSK/oEioTwEXYKOJhh1TzoFFQiR/gEd9FiZl05JeCghg1BIMDoka4zMxtO077wz++vdfd0vPJfZ2Zn5vPPOj/eZZ+B/nQHGgAngQYCp37JEp6xD+xRD2lbIIWAW2Ha0VaLTqkc7ZzXbP1IJyx6FKFsgOr33bOuyCS179gcwDgwDgwE2AKSi492pe8DSvmHNsCG4ZuSYlbBdxEddArpQHNNjAlg9lbjpvuC7iZ7RigkjxE8jgk/BMykS8sRPecGnWFvAQUoCpxzsJJDAXWq52FciPaHLcqmzvdo9nASWPNa9OUfYy8AW8KXEMjfvUd+SBTpfDnDac6Hf1g8pTI9F/n6RniyjvnQ1gTMizwrwMsDWRL4OB+ApkT8n0jtE+pqlvhWRL+MKfF8kDDoAT1sA3tQAWJUZpGkH4EFzWcrqzfiCZbvYzMApvfdWjL04qpmBy1ILOECtHo7DK70OLAbYZsAY3gM81BOjzP/dAXjTUt96rYC7PTcBBWPnc9bhP+dE/nZdhk+dqo1VU0JvF11hnxn/z4X8512JWf25B7RqWxsNpJxonBq3sVeuBRxz5Tx6+AjQV8LUFng/MQPuAX5ZJqcPxAx4yGFG7qxXo3uAG8AocB04HpL/gs47anxz24D3AreBJ4bJo5WD1EGdxi5K2SdL/ouWHipnWZpvdOAhC/CdZgA2X+lrwDHcX+miXSlzRySBnxqv+5R2BsZKcyET2VapI9FG1FG9Z74Xkm88BPizo4clcj0SjVZDxqaegA1JX8RHt14KcgDEVlMt4AZRwuOsp6Anokp7+IWHA2De8wCv5i4eX+DIXTyZCp14vsCRO/EyouDWyYOjI/40cNWw183gl54uA/gw8DNkDMYKOBcC+9v4GKkpcBb4po8UU3UAfgvcNey8UU61j0sXNWNvvQ7Efd20NT0Qn6xDyEMlwFUPeZisQ1BLv0hXgSxhijyoJVlh2FJKhyapD/VLuMknbOljtcOW0AW6BImdCHDjdHh6JdqiDExrZuV3PfDEbgsfvrXbAsTTMb4C0G1cAVAfMDuaEdAb+qk08iWPAw6XPBSDvOTxqtJrPGrvHZUWK73Gg06QPR1mX2n8i1oFzWRd/9V7riaysKtuzoGaNZCqO6x9aoL6O2aL+gNhs3dqBwlUmwAAAABJRU5ErkJggg=='
        },100)
          
    </script>
@endsection