<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            box-sizing: border-box;
        }
        body{
            width: 100%;
        }
     
        table {
            margin-top: 20px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
         

        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .heading td{
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #ddd;
            color: black;
            font-weight: bold;
        }
        h3{
            text-align: center;
        }
        .column {
            float: left;
            width: 30%;
            /* height: 180px; Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
         @page :first {
        header: page-header;
    }
        @page  {
  header: page-header;
  margin-top:150px;
  margin-bottom:80px;
  footer: page-footer;
}
    </style>
</head>
<body >
    <htmlpageheader name="page-header">
    <img src="{{ asset('assets/images/headerPdf.png') }}"  >
</htmlpageheader>
 
<htmlpagefooter name="page-footer">
  <img src="{{ asset('assets/images/footerPdf.png') }}"   >
  
</htmlpagefooter>

    <!--<div class="background-image ">-->
        <!--<img src="{{ asset('assets/images/gouslaPdf.png') }}" >-->
    <!--</div>-->
    <div   style="display: flex; flex-direction: column; justify-content: center;">
        <div class="row" style="display: flex; flex-direction: row; justify-content: space-between; width:100%;"> 
            <div class="column" style="display: flex; flex-direction: column; justify-content: center;float:left;">
               <!--<img src="{{ asset('assets/images/gousla.jpeg') }}" width="200px" height="60px" alt="">-->
            </div>
            <div class="column" style="display: flex; flex-direction: column; justify-content: center;float:right;">
                <div >
                    <strong>Réf : </strong>{{ $orders->ref }}
                </div>
                <div>
                    <strong>Date : </strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                </div>
            </div>
        </div>
        <div style="text-align:center; width:100%;border:1px solid #000;padding:5px;margin-top: 80px">
            <span style="font-size:26px;">Bon livraison</span>
        </div>
        <div class="row" style="display: flex;justify-content: space-between; width: 100%;">
            <div  class="column" style="display:flex; flex-direction: column; justify-content: center; border: 1px solid #eee; padding: 10px;">
                <h3>Client</h3>
                <div style="display: flex; flex-direction: column;">
                    <div><strong>Nom : </strong> <span><strong>{{ $clients[0]['name_client'] }} </strong></span></div>
                    <div><strong>Tél : </strong> <span>{{ $clients[0]['phone_client'] }}</span></div>
                    <div><strong>Adresse : </strong> <span>{{ $clients[0]['addresse_client'] }}</span></div>
                    @if ($clients[0]['ICE'] != '')
                        <div><strong>ICE : </strong> <span>{{ $clients[0]['ICE'] }}</span></div>
                    @endif
                </div>
            </div>
        </div>
        <div style="width: 100%; margin-top: 30px">
            <table  >
                <tr class="heading">
                    <td>Réf</td>
                    <td>Produit</td>
                    <td>Qté</td>
                    <td>Prix </td>
                    <td>Total</td>
                </tr>
                <tbody>
                    @php
                        $totalGlobal=0;
                    @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['QNT'] }}</td>
                            <td>{{ $item['peice'] }} DH</td>
                            <td>{{ number_format((float)$item['total'], 2, '.', '') }} DH</td>
                        </tr>
                        @php
                            $totalGlobal += $item['total'];
                        @endphp

                    @endforeach
              
                    
                </tbody>
                <tr class="heading">
                    <td colspan="4">Total global</td>
                    <td>{{ number_format((float)$totalGlobal, 2, '.', '') }} DH</td>
                </tr>
            </table>
        </div>
        
    </div>
    

 
</body>
</html>