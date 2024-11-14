<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BILLING STATEMENT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        

        body {
            margin: 0;
            padding: 0;
        }

        .paperBorder {
            position: fixed;
            width: 7.3in;
            height: 10.7in;
            border: 1px solid #000;
        }

        .header {
            height: 1.55in;
        }

        .divborder{
            border: 1px solid #000;
        }
        .netiLogo{
            width: 2in;
            margin-left: 245px;
            margin-top: 10px;
        }
        .formNumber{
            margin-left: 169px;
            margin-top: -30px;
            font-family: Calibri, sans-serif;
            font-size: 12px;
            
        }
        .serialNumber{
            margin-left: 610px;
            margin-top: -20px;
            font-family: Calibri, sans-serif;
            font-size: 12px;
            text-decoration: underline;
        }

        .netiInfo{
            text-align: center;
            font-size: 11px;
            margin-top: -28px;
            font-family: Arial, sans-serif;
            color: #44749F;
            line-height:1.7;
        }

        .vat{
            text-align: center;
            font-family: Calibri, sans-serif;
            line-height:1.5;
        }

        .billingtitle{
            margin-left: 5px;
            margin-right: 5px;
            align-items: center;
        }

        .recipient{
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 3px;
            height: 90px;
        }
        .receiver{
            float: left;
            width: 510px;
            height: 100%;
        }
        .divDate{
            width: 100%;
            height: 100%;
        }
        .bold{
            font-weight: bold;
        }
        .calibri{
            font-family: Calibri, sans-serif;
        }


        .leftTO {
            float: left;
            width: 30px;
            height: 100%;
        }
        .rightRecipient {
            width: 100%;
            height: 100%;
        }

        .particularsAndamount{
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 3px;
            height:660px;
        }

        .footer{
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 3px;
            height:75px;
        }

        .bold{
            font-weight: bold;
        }
        .century-gothic {
        font-family: "Century Gothic", sans-serif;
        }
        .traineelistTable{
            margin-top: -140px;
            margin-left:  70px;
            width: 460px;
        }

        .amountDescTable{
            margin-top: -25px;
            margin-left:  70px;
            width: 460px;
        }
        .bankInfoTable{
                margin-top:-10px;
                margin-left:10px;
        }

        .signatureTable{
            width: 100%;
        }

        .amountWOVat{
            margin-top: -140px;
            width: 100%;
        }

        .totalAmount{
            margin-top: -25px;
            width: 100%;
        }

        p{
            line-height: .11;
        }

        .preparedBy-Signature {
        position: absolute; /* Position relative to the nearest positioned ancestor */
        top: 963px; /* Adjust the top position as needed */
        left: 1px; /* Adjust the left position as needed */
        z-index: 2; /* Adjust the stacking order; higher values are on top */
        height:50px;
        width: 120px;
        }

        .notedBy-Signature {
        position: absolute; /* Position relative to the nearest positioned ancestor */
        top: 963px; /* Adjust the top position as needed */
        left: 250px; /* Adjust the left position as needed */
        z-index: 2; /* Adjust the stacking order; higher values are on top */
        height:50px;
        width: 120px;
        }

        .gm-Signature {
        position: absolute; /* Position relative to the nearest positioned ancestor */
        top: 963px; /* Adjust the top position as needed */
        left: 450px; /* Adjust the left position as needed */
        z-index: 2; /* Adjust the stacking order; higher values are on top */
        height:50px;
        width: 120px;
        }

    </style>
</head>

<body>

    {{-- Paper Border --}}
    <div class="paperBorder">

        {{-- header --}}
        <div class="header">

                    {{-- NYK logo --}}
                    <img src="{{ public_path('assets/images/oesximg/NETI.png') }}" alt="" class="netiLogo">

                    <label class="formNumber">F-NETI-082</label>

                    {{-- serial number --}}
                    <label class="serialNumber">S.N.    {{$billingserialnumber}}</label>

                    {{-- neti info --}}
                    <div class="netiInfo">
                            <small >
                                Knowledge Avenue, Carmeltown, Canlubang<br>
                                4028 Calamba City, Laguna, Philippines<br>
                                Website: http//www.nykfil.com.ph<br>
                                email:neti@neti.com.ph
                            </small>
                    </div>

                    {{-- vat & tin --}}
                    <div class="vat">
                            <label style="font-size:10px;">VAT REGISTERED<br>TIN # 209-246-198-000</label>
                    </div>

                    {{-- billing title --}}
                    <div class="billingtitle divborder">
                            <label style="font-weight: bold; margin-left:5px;">DEBIT / CREDIT MEMO</label>
                    </div>

                    {{-- recipient --}}
                    <div class="recipient divborder">
                                {{-- receiver --}}
                                <div class="receiver ">
                                    
                                    <div  class="leftTO">
                                        <label class="bold calibri" style="font-size: 11px; margin-left:3px;">TO:</label>
                                    </div>
                                    <div  class="rightRecipient" >
                                        <label class="calibri" style="font-size: 11px;margin-left:40px;margin-top:12px;">
                                            {!! $company_data->billingReceivingInfo !!}
                                        </label>
                                    </div >
                                    
                                   
                                    
                                </div>
                                {{-- date --}}
                                <div class="divDate " >
                                        <p class="calibri"  style="font-size: 12px;margin-top:12px;"><b>DATE:</b><br></p>
                                        <p class="calibri"  style="font-size: 12px;">{{ date('d-M-Y') }}</p>
                                </div>
                    </div>

                    {{-- billing statement body --}}
                    <div class="particularsAndamount divborder"  >

                                <table style="width:100%;">
                                        <tr>
                                                <th class="text-center" style="border-bottom:1px solid #000;border-right:1px solid #000;width:570px;">PARTICULARS</th>
                                                <th class="text-center" style="border-bottom:1px solid #000;">AMOUNT</th>
                                        </tr>

                                        <tr>
                                            <td class="century-gothic bold" style="border-right:1px solid #000;width:570px;font-size:10px;">
                                                <label style="margin-left:25px;margin-top:25px;">TRAINING FEE:</label><br>
                                                <label style="margin-left:45px;">{{ $schedule_data->course->coursecode }} {{ $schedule_data->course->coursename }} ({{ $startdateformat }} - {{ $enddateformat }})</label>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="century-gothic divBorder" style="border-right:1px solid #000;width:570px;font-size:11px;height:260px;">{{--background-color:green;--}}
                                                

                                                {{-- TRAINEE LIST TABLE --}}
                                                {{-- TRAINEE LIST TABLE --}}
                                                <table class="traineelistTable century-gothic">
                                                            <tr>
                                                                    <th class=""></th>
                                                                    <th class=""></th>
                                                                    <th class="" style="width:130px;">(Total amount with VAT)</th>
                                                            </tr>
                                                            @php
                                                                $counter = 1;
                                                                $totalWVat = 0;
                                                            @endphp
                                                            @foreach ($traineeList as $tlist)
                                                                    <tr>
                                                                        <td class="">{{ $counter++; }} {{ $tlist->rankacronym }} {{$tlist->l_name}}, {{$tlist->f_name}} {{$tlist->m_name}} {{$tlist->suffix}}</td>
                                                                        <td class="text-right">
                                                                            @if ($counter == 2) <!-- Show "USD" only when counter is equal to 2 -->
                                                                                USD
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-right">{{ number_format((.12 * $tlist->rateusd) + $tlist->rateusd, 2) }}</td>
                                                                    </tr>    
                                                                    @php
                                                                        $totalWVat += (.12 * $tlist->rateusd) + $tlist->rateusd;
                                                                    @endphp
                                                            @endforeach
                                                            
                                                </table>
                                                {{-- TRAINEE LIST TABLE END--}}
                                                {{-- TRAINEE LIST TABLE END--}}


                                            </td>
                                            <td style="height:260px;">{{--background-color:yellow;--}}

                                                {{-- AMOUNT WITHOUT VAT --}}
                                                {{-- AMOUNT WITHOUT VAT --}}
                                                <table class="amountWOVat" style="font-size:11px;">
                                                        <tr>
                                                            <th colspan="2" class="text-center ">(w/o VAT)</th>
                                                        </tr>
                                                        @php 
                                                            $totalWOVAT = 0;
                                                        @endphp
                                                        @foreach($traineeList as $key => $tlist)
                                                        <tr>
                                                            <td>{{ $key === 0 ? 'USD' : '' }}</td>
                                                            <td class="text-right ">{{ number_format($tlist->rateusd, 2) }}</td>
                                                        </tr>
                                                        @php 
                                                            $totalWOVAT += $tlist->rateusd;
                                                        @endphp
                                                        @endforeach
                                                        
                                                </table>
                                                {{-- AMOUNT WITH VAT END--}}
                                                {{-- AMOUNT WITH VAT END--}}
                                                
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="century-gothic " style="border-right:1px solid #000;width:570px;font-size:12px;height:100px;">{{--background-color:blue;--}}
                                                

                                                {{-- AMOUNT DESC --}}
                                                {{-- AMOUNT DESC --}}
                                                <table class="amountDescTable century-gothic">
                                                    
                                                            <tr>
                                                                <td  >Subtotal</td>
                                                                <td class=" text-right">USD</td>
                                                                <td class="text-right  " style="width:130px;border-top:1px solid #000;">{{ number_format($totalWVat , 2 ) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" >Add: 12% VA</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" >Add: Provision for Bank Charge</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" class="bold">TOTAL AMOUNT DUE</td>
                                                            </tr>
                                                </table>
                                                {{-- AMOUNT DESC END--}}
                                                {{-- AMOUNT DESC END--}}


                                            </td>
                                            <td style="height:100px;">{{--background-color:red;--}}

                                                {{-- TOTAL AMOUNT --}}
                                                {{-- TOTAL AMOUNT --}}
                                                <table class="totalAmount" style="font-size:12px;">
                                                        <tr>
                                                            <td style="border-top:1px solid #000;">USD</td>
                                                            <td class="text-right" style="border-top:1px solid #000;">{{ number_format($totalWOVAT , 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class="text-right">{{ number_format($totalWVat - $totalWOVAT , 2) }}</td>
                                                        </tr>
                                                        @php
                                                        $bankcharge = $company_data->bank_charge;
                                                        $totalamount = $totalWVat + $bankcharge;
                                                        @endphp
                                                        <tr>
                                                            <td colspan="2" class="text-right">{{ number_format($bankcharge , 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="border-top:1px solid #000;border-bottom:1px solid #000;">USD</th>
                                                            <th class="text-right" style="border-top:1px solid #000;border-bottom:1px solid #000;">{{ number_format($totalamount , 2); }}</th>
                                                        </tr>
                                                </table>
                                                {{-- TOTAL AMOUNT END--}}
                                                {{-- TOTAL AMOUNT END--}}

                                            </td>
                                        </tr>

                                        <tr>
                                            <td  style="border-right:1px solid #000;width:570px;font-size:11px;height:194px;">{{--background-color:orange;--}}

                                                    {{-- Bank Info --}}
                                                    {{-- Bank Info --}}
                                                    <table class="bankInfoTable">
                                                            <tr>
                                                                    <td style="width:20px;">1.</td>
                                                                    <td colspan="2" >Please remit the total amount to our bank account as follows:</td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td>ACCOUNT NAME :</td>
                                                                <td>{{ $bank_data->accountname }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td>ACCOUNT NUMBER :</td>
                                                                <td>{{ $bank_data->accountnumber }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td>BANK :</td>
                                                                <td>{{ $bank_data->bankname }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td>TERMS OF PAYMENT</td>
                                                                <td>WITHIN THIRTY (30) DAYS UPON RECEIPT OF THE BILLING STATEMENT.</td>
                                                            </tr>

                                                            <tr>
                                                                <td>2.</td>
                                                                <td colspan="2">Send scanned copy of proof of payment to: <b>collection@neti.com.ph</b></td>
                                                            </tr>

                                                            <tr>
                                                                <td>3.</td>
                                                                <td colspan="2">NETI is a registered PEZA entity, as such exempted from withholding taxes.</td>
                                                            </tr>

                                                    </table>
                                                    {{-- Bank Info End--}}
                                                    {{-- Bank Info End--}}

                                            </td>
                                            <td style="">{{--background-color:violet;--}}

                                            </td>
                                        </tr>



                                </table>

                    </div>


                    {{-- footer --}}
                    <div class="footer divborder"  >

                            <table class="signatureTable" >
                                    <tr style="font-size: 11px;">
                                            <td   style="height:30px;width:33.33%;"><label style="top: 0;">PREPARED BY:</label></td>
                                            <td  style="width:33.33%;"><label style="top: 0;">NOTED BY:</label></td>
                                            <td  style="width:33.33%;"><label style="top: 0;">APPROVED BY:</label></td>
                                    </tr>
                                    <tr style="font-size: 11px;">
                                            <th  style="height:30px;" >
                                                @if( $enroled_data->is_SignatureAttached == 1)
                                                        <img src="storage/signatures/bell.png" class="preparedBy-Signature">
                                                @endif
                                                <label style="bottom:0;transform: translateY(100%);">JCABREJAS</label>
                                            </th>
                                            <th >
                                                @if( $enroled_data->is_SignatureAttached == 1)
                                                        <img src="storage/signatures/test.png" class="notedBy-Signature">
                                                @endif
                                                <label style="bottom:0;transform: translateY(100%);">MAMARTINEZ/AAANTONIO
                                                </label>
                                            </th>
                                            <th >
                                                @if( $enroled_data->is_GmSignatureAttached == 1)
                                                        <img src="storage/signatures/CLEMENTE.png" class="gm-Signature">
                                                @endif
                                                <label style="bottom:0;transform: translateY(100%);">EZCLEMENTE, JR.</label>
                                            </th>
                                    </tr>
                            </table>

                    </div>

        </div>

    </div>


</body>

</html>