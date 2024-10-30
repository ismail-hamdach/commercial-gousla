<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- <link rel="stylesheet" href="style.css" /> -->
        <title>Receipt example</title>
        <style>
            * {
                font-size: 13px;
                /*font-family: "Times New Roman";*/
                font-weight: 600;
            }

            th {
                font-weight: 900;
            }

            #header td,
            #header th,
            #header tr,
            #header {
                border-top: none !important;
                border: none !important;
                width: auto;
                min-width: 50px;
                margin-left: 30px;
                margin-right: 1px;
               
            }

            #header {
                margin-bottom: 15px;
                
            }

            td,
            th,
            tr,
            table {
                border-top: 1px solid black;
                border-collapse: collapse;
                width: 90vw;
                margin-left: auto;
                margin-right: auto;
            }

            td.description,
            th.description {
                text-align: left;
            }
            /* 
            td.quantity,
            th.quantity {
                width: 40px;
                max-width: 40px;
                word-break: break-all;
            }

            td.price,
            th.price {
                width: 40px;
                max-width: 40px;
                word-break: break-all;
            } */

            .centered {
                text-align: center;
                align-content: center;
            }

            /* .ticket {
                width: 155px;
                max-width: 155px;
            } */

            img {
                /*max-width: 100px;*/
                /*width: 100px;*/
            }
            body {
              font-family: "Arial Unicode MS", Arial, sans-serif;
             
            }
              @media print{
                @page {
                    margin:0;
                } 
                body{
                    margin:0;
                }
                #printButton{
                    display:none;
                }
                
             }
        </style>
          
            <script>
                function printTicket() {
                    setTimeout(() => {
                
                        window.ReactNativeWebView.postMessage(document.getElementsByTagName("html")[0].innerHTML);
                        window.print();
                    }, 500)
                    window.print();
                }
               
            </script>
    </head>
    <body class="centered"  >
        <div class="ticket"> 
             <img
                width='200px'
                alt="Logo"
                onclick="location.href= '{{asset('/')}}' "
                src="{{ asset('assets/images/miniprint.png') }}"
            /> 

            <p class="centred" > Bon de  livraison</p>
            <div class='centred ' style='margin: auto;width: 80%;'>
            <table id="header"  style="text-align: start;" >
                <tr >
                    <th  style="text-align: start;">Date</th>
                    <td> {{date('d-m-Y', strtotime($data['date_order'])) }}</td>
                </tr>
                <tr>
                    <th  style="text-align: start;"> Ref : </th>
                    <td> {{$data['Referance_order'] }}</td>
                </tr>
                <tr>
                    <th  style="text-align: start;"> client :</th>
                    <td> {{$data['client'] }}</td>
                </tr>
              
                <tr>
                    <th  style="text-align: start;"> Adresse </th>
                    <td> {{$data['client_addresse']}}</td>
                </tr>

            </table>
            </div>
            <div
                style="
                    border: dotted black 1px;
                    width: 90vw;

                    margin-left: auto;
                    margin-right: auto;
                "
            ></div>
            <table class="">
    <thead>
        <tr >
            <th ></th>
            <th  style="text-align: start;"  >Produit</th>
            <th ></th>
            <th  style="text-align: start;">Qté</th>
            <th ></th>
            <th style="text-align: start;" >Price</th>
            <th style="text-align: start;">Total </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($DetailsOrder as $product) 
            <tr >
                <td  colspan='3 mb-1' style="text-align: start;">
                 <div  style="  margin-top:5px ; margin-bottom:5px ;">
                           <span class="   " style="  border: 1px solid #000;"
                         >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <label class="form-check-label "  style="text-align: start;"  >
                          {{$product["product_name"]}}
                        </label> 
                     
                    </div>
                </td>
                <td  colspan='2 mb-1' style="text-align: start; margin-left:5px">
                     x {{$product["QNT"]}}
                    
                </td>
                <td  style="text-align: start;">{{$product["price"]}}</td>
                <td  style="text-align: start;">{{$product["total"] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

            <br></br>
            <table>
                </tbody>
      
                    <tr class='mt-5'>
                        <th class="quantity" colspan='4'> prix total de la commande</th>
                       
                        <td class="price"> {{$data['total']}}  <span style="scale: 0.6;"> DH</span></td>
                    </tr>
                    
                    <tr class='mt-5'>
                        <td> </td>
                    </tr>
                    
                    <tr class="heading">
                            <td colspan='4'>Montant á Payer </td>

                            <!-- <td></td>
                            <td> number_format((float) $data['totalProfit'], 2, '.', '')   __('rial'] </td> -->
                            <td > <span >{{$data["payer"] }}</span> DH</td>
                        </tr>
                        
                         <tr class='mt-5'><td> </td></tr>
                          <tr class="heading">
                            <td colspan='4'>LE RESTE </td>

                            <!-- <td></td>
                            <td> number_format((float) $data['totalProfit'], 2, '.', '')   __('rial'] </td> -->
                            <td> <span >{{$data["rest"] }}</span>DH</td>
                        </tr>
                    </tr>
                    <tr class='mt-5'><td> </td></tr>
                   
                   
                        <tr class="heading">
                            <td colspan='4' > total credit</td>

                            <!-- <td></td>
                            <td> number_format((float) $data['totalProfit'], 2, '.', '')   __('rial'] </td> -->
                            <td>
                            <!--    <input type='number'-->
                            <!--style="outline:none;border:none;with:50px;font-size:16px" -->
                            <!--id='credit_an' -->
                            <!--onblur="creditCanged(this)" -->
                            {{$data['credit_an'] }}DH
                            </td>
                        </tr>
                        
                             <td colspan='4' > total des crédits</td>

                            <td>
                            
                             {{ $data['credit_an'] + $data['rest']}}  DH
                            </td>
                        </tr>
                        
                        <!--<tr class="heading">-->
                        <!--    <td colspan='4'> {{ __('total_credit_an') }}</td>-->

                           
                            <!-- <td></td>
                          <td> number_format((float) $data['totalProfit'], 2, '.', '')   __('rial'] </td> -->
                        <!--    <td>-->
                                <!--<input type='number' style='color:black;background-color:#fff;font-weight: bold;' disabled id='total_credit_an'  style="outline:none;border:none;with:50px"   />-->
                        <!--        <span hidden id='total_credit_an'>-->
                        <!--        DH</span></td>-->
                        <!--</tr>-->
                </tbody>
            </table>
            <div
                style="
                    border: dotted black 1px;
                    width: 90vw;
                   
                    margin-left: auto;
                    margin-right: auto;
                "
            ></div>
             <div> <br>
                <div class="centered " > Merci pour votre confiance .<div>
                <p class="centered "  >
                      {{ \Carbon\Carbon::now() }}
                </p>
                <div>
                      <b dir="ltr" > +212 667557904</b>
                     <br> {{__('ville')}}
                </div>
            </div> <br>
                
  <button onclick="printTicket()" id='printButton'
                style=" 
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                  
                
                 :hover 
                    background-color: #45a049;
                  "
                 >
             Imprimer</button>
                 
            
        </body>
       
          
    </html>