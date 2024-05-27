<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data['consecutivo'] . ' ' . $data['nombre_curso'] }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}">


 </head>

<body>
    
    <style>
        .colorprimario {
            color: #ffce00 !important;
        }

        .colorgris {
            color: #606060 !important
        }

        .certificado-titulo {
            //font-family: 'Poppins-Regular' !important;
            font-size: 50px !important;
        }
        
        .certificado {
            background-image: url({{ $data['certificado'] }});
            background-repeat: no-repeat;
            background-size: contain;
        }

        .carnet {
            background-image: url({{ $data['carnet'] }});
            background-repeat: no-repeat;
            background-size: contain;
        }

        html,
        body {
            margin: 1px;
            padding: 1px;
            color: #203554;
            //font-family: 'Poppins', !important;
        }

        .page-break {
            page-break-after: always;
        }

        .page-container {
            background: none
        }

        body {
            background: none
        }
        .colorNavy {
            color: #203554;
        }
         .colorClou {
            color: #94AEC9;
        }
    </style>

    <div class="certificado d-inline-flex" style="height: 99%; width: 100%; margin-top: 5px;">
        <h5 class="text-end " style="margin-top: 54px; margin-right: 96px">{{ $data['consecutivo'] }}
        </h5>
        <h1 class="text-center justify-content-center certificado-titulo " style="margin-top: 300px; margin-left: 40px">
            {{ Str::upper($data['nombre_cliente']) }}</h1>

        <h3 class="justify-content-center  "
            style="margin-top: 10px; margin-left:620px; font-wight: 100;font-family: Helvetica-Oblique">
            {{ $data['documento_identidad'] }}</h3>
      
        <div class="col-2 d-none" style="margin-left: 500px; margin-top: -5px;"> 
            <h5 class="text-start"  >
                {{ $data['nombreAliado'] }}
            </h5>
        </div>
        <div class="col-2 d-none" style="margin-left: 800px; margin-top: -100px"> 
            <h5 class="text-start" >
                {{ $data['nitAliado'] }}
            </h5>
        </div>

        <div class="col-6 d-none" style="margin-left: 590px; margin-top: -10px"> 
            <h5 class="text-start" >
                {{ $data['representanteLegalAliado'] }}
            </h5>
        </div>

        <div class="col-3 d-none"  style="margin-left: 500px; margin-top: -10px"> 
            <h5 class="text-start" >
                {{ $data['economicSector'] }}
            </h5>
        </div>
        <div class="col-2 d-none" style="margin-left: 800px; margin-top: -100px"> 
            <h5 class="text-start" >
                {{ $data['arlAliado'] }}
            </h5>
        </div>            
               

        <h2 class="justify-content-center text-center  "
            style=" margin-top:80px; font-wight: 800; font-size: 44px; margin-left: 40px ">
            {{ $data['nombre_curso'] }}
        </h2>

        <<h5 class="justify-content-center d-none "
            style="margin-left: 520px; font-wight: 600; font-size:14px; margin-top:30px">
            { Str::upper($data['desde']) }}
        </h5>
        <h5 class="justify-content-center d-none "
          style="margin-left: 615px; font-wight: 600; font-size:14px; margin-top:-25px">
            { Str::upper($data['hasta']) }}
        </h5>
        <h5 class="justify-content-center d-none "
          style="margin-left: 678px; font-wight: 600; font-size:14px; margin-top:-8px">
            { $data['horas'] }}
        </h5>
        <h5 class="justify-content-center d-none "
            style="margin-left: 879px; font-wight: 600; font-size:14px; margin-top:9px">
            { Str::upper($data['emitido']) }}
        </h5>

        <div class="col-12 d-flex" style="display: flex; margin-top: 65px; margin-left:80px">
            <!--<div class="col-6">
                <div>
                    <img src="{ asset('storage/representantes/' . $data['firma_representante_legal']) }}"
                        width="100" alt="firma-representante-legal" style="margin-left: 290px; margin-top:5px ">
                    <h3 class="justify-content-center text-center  "
                        style="margin-left: 50px; margin-top: 0px">
                        { $data['nombre_representante_legal'] }}</h3>
                </div>
            </div>-->
            <div class="col-6" style="margin-left: 600px">
                <div >
                    <img src="{{ asset('storage/instructores/' . $data['firma_instructor']) }}" width="100"
                        alt="firma-representante-instructor" style="margin-left: 185px; margin-top: 120px ">
                    <h3 class="justify-content-center text-center  "
                        style="margin-left: -140px; margin-top: 57px; margin-bottom: -300px">
                        {{ $data['nombre_instructor'] }}
                        <h5 class="justify-content-center text-center "
                            style=" margin-top: -57px">
                            {{ $data['resolucion_instructor'] }}</h5>
                    </h3>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="page-break"></div> --}}

    <div class="page-container">
        <div class="carnet col-12 row" style="display:inline-flex; height: 99%; width: 100%;">
            <h4 class="text-left  justify-content-left"
                style="margin-top: 146px; margin-left:395px">
                {{ $data['consecutivo'] }}
            </h4>
            <div class="col-6" style="padding-right: 500px; margin-top: 55px; margin-left:120px">
                <h3 class="justify-content-left text-left  "> {{ $data['nombre_curso'] }}
                </h3>
                <h3 class="justify-content-left text-left  "
                    style="margin-top: 6px; margin-left: 13px"> {{ $data['nombre_cliente'] }}
                </h3>
                <h3 class="justify-content-left text-left  "
                    style="margin-top: 8px; margin-left:100px">
                    {{ $data['documento_identidad'] }}
                </h3>
                <h3 class="justify-content-left text-left  "
                    style="margin-top: 8px; margin-left: 163px;">
                    {{ Str::upper($data['fecha_expedicion']) }}
                </h3>
               
            </div>
            <div class="col-6">
                <div style="margin-left: 900px; margin-top: -170px">
                    <img src="{{ asset('storage/representantes/' . $data['firma_representante_legal']) }}" width="100"
                    alt="firma-representante-legal" >
                    <h3 class="justify-content-center text-center  "
                        style="margin-top: -11px; margin-left: -265px">
                        {{ $data['nombre_representante_legal'] }}</h3>
                </div>                
            </div>
        </div>
    </div>
</body>

</html>
