@extends('layouts.panel.app')

@section('jss')   
    <script>
        async function fetchData1() {
            try {
                const response = await fetch('/api/checkCertificados');
                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }
                const data = await response.json();
                // procesar los datos obtenidos

                createChartCertificado(data);

            } catch (error) {
                console.error(error);
            }
        }
        async function fetchData2() {
            try {
                const response = await fetch('/api/checkAsesores');
                if (!response.ok) {
                    throw new Error('Error al obtener los datos');
                }
                const data = await response.json();
                // procesar los datos obtenidos
                createChartAsesores(data);
            } catch (error) {
                console.error(error);
            }
        }
        fetchData1()
        fetchData2()

        function createChartCertificado(data) {
            if (data.encontrado) {
                var ctx = document.getElementById('pieChartCertificados').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data.data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Certificados generados este mes'
                            },
                            datalabels: {
                                color: '#fefefe',
                                formatter: (value, context) => {
                                    return value;
                                }
                            }
                        },
                    },
                    plugins: [ChartDataLabels]

                });
            } else {
                var ctx = document.getElementById('pieChartCertificados');
                var ctx1 = document.getElementById('msg-not-data-certificados');
                ctx1.classList.remove("d-none");
                ctx.classList.add("d-none");
            }
        }

        function createChartAsesores(data) {

            if (data.encontrado) {
                var ctx = document.getElementById('pieChartAsesores').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data.data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Certificados por asesores de este mes'
                            },
                            datalabels: {
                                color: '#fefefe',
                                formatter: (value, context) => {
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]

                });
            } else {
                var ctx = document.getElementById('pieChartAsesores');
                var ctx1 = document.getElementById('msg-not-data-asesores');
                ctx1.classList.remove("d-none");
                ctx.classList.add("d-none");
            }
        }
    </script>

    <script>
        var data = {
            labels: {!! json_encode($data['labels']) !!},
            datasets: [
                @foreach ($data['dataset'] as $dataset)
                    {
                        label: "{{ $dataset['nombreCurso'] }}",
                        data: {!! json_encode($dataset['contador']) !!},
                        fill: false,
                        tension: 0.1,
                        backgroundColor: "{{ $dataset['backgroundColor'] }}"
                    },
                @endforeach
            ]
        };
    </script>
    <script>
        var ctx = document.getElementById('certificados').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Certificados emitidos el dia de hoy',
                        color: '#94AEC9'
                    },
                },
            }
        });
    </script>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-12 card">
            <div class="card-body row text-center justify-content-center">
                <img class="text-center justify-content-center icon-bg-white" src="{{ asset('images/dashboard/dashboard2.png') }}"
                    alt="bienvenidos-svg" style="max-width: 400px">

                <img class="text-center justify-content-center icon-bg-dark" src="{{ asset('images/dashboard/dashboard1.png') }}"
                    alt="bienvenidos-svg" style="max-width: 400px">

                <div class="background-dashboad-lema mb-4">
                    <b>
                        <h4>Nuestro propósito es ayudar a las personas brindando</h4>
                        <h4>estrategias de formación en trabajo seguro en alturas</h4>
                        <h4>y competencias.</h4>
                    </b>
                </div>

                <div class="row mb-1 mt-4">
                    <div class="col-md-6 col-12 col-lg-3 mb-2  ">
                        @can('cliente.index')
                            <a href="{{ route('index.clientes') }}" class="zoom-effect">
                            @endcan

                            <img src="{{ asset('images/icon-cliente.svg') }}" alt="icon-clientes" 
                                class="icon-bg-white justify-content-center text-center icon-bg">

                            <img src="{{ asset('images/icon-cliente-dark.svg') }}" alt="icon-clientes"
                                class="icon-bg-dark justify-content-center text-center icon-bg" >

                            <div class="d-flex">
                                <div class="circle-cantidad-primary text-center justify-content-center">
                                    <div class="circle-cantidad text-center justify-content-center">
                                        <h3 class="text-center justify-content-center">{{ $contadorClientes }}</h3>
                                    </div>
                                </div>
                                <div class="rectangle-text text-center justify-content-center">
                                    <h3>{{ __('Clientes') }}</h3>
                                </div>                                
                            </div>
                            @can('cliente.index')
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-6 col-12 col-lg-3 mb-2 ">
                        @can('instructor.index')
                            <a href="{{ route('index.instructores') }}">
                            @endcan
                            <img src="{{ asset('images/icon-instructores.svg') }}" alt="icon-instructores"
                                class="icon-bg-white justify-content-center text-center icon-bg">

                            <img src="{{ asset('images/icon-instructores-dark.svg') }}" alt="icon-instructores"
                                class="icon-bg-dark justify-content-center text-center icon-bg" >

                            <div class="d-flex">
                                <div class="circle-cantidad-primary text-center justify-content-center">
                                    <div class="circle-cantidad text-center justify-content-center">
                                        <h3 class="text-center justify-content-center">{{ $contadorInstructores }}</h3>
                                    </div>
                                </div>
                                <div class="rectangle-text text-center justify-content-center">
                                    <h3 class="">{{ __('Instructores') }}</h3>
                                </div>
                                
                            </div>
                            @can('instructor.index')
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-6 col-12 col-lg-3 mb-2 ">
                        @can('asesor.index')
                            <a href="{{ route('index.asesores') }}">
                            @endcan
                            <img src="{{ asset('images/icon-asesores.svg') }}" alt="icon-asesores" 
                                class="icon-bg-white justify-content-center text-center icon-bg">

                            <img src="{{ asset('images/icon-asesores-dark.svg') }}" alt="icon-asesores"
                                class="icon-bg-dark justify-content-center text-center icon-bg">

                            <div class="d-flex">
                                <div class="circle-cantidad-primary text-center justify-content-center">
                                    <div class="circle-cantidad text-center justify-content-center">
                                        <h3 class="text-center justify-content-center">{{ $contadorAsesores }}</h3>
                                    </div>
                                </div>
                                <div class="rectangle-text text-center justify-content-center">
                                    <h3 class="">{{ __('Asesores') }}</h3>
                                </div>
                                
                            </div>
                            @can('asesor.index')
                            </a>
                        @endcan
                    </div>
                    <div class="col-md-6 col-12 col-lg-3 mb-2 ">
                        @can('certificado.index')
                            <a href="{{ route('index.certificados') }}">
                            @endcan
                            <img src="{{ asset('images/icon-certificados.svg') }}" alt="icon-certificados"
                                class="icon-bg-white justify-content-center text-center icon-bg">

                            <img src="{{ asset('images/icon-certificados-dark.svg') }}" alt="icon-certificados"
                                class="icon-bg-dark justify-content-center text-center icon-bg" >

                            <div class="d-flex">
                                <div class="circle-cantidad-primary text-center justify-content-center">
                                    <div class="circle-cantidad text-center justify-content-center">
                                        <h3 class="text-center justify-content-center">{{ $contadorCertificados }}</h3>
                                    </div>
                                </div>
                                <div class="rectangle-text text-center justify-content-center">
                                    <h3 class="">{{ __('Certificados') }}</h3>
                                </div>
                            </div>
                            @can('certificado.index')
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center justify-content-center d-none" id="msg-not-data-certificados">
                        ¡{{ __('No hay información que mostrar para los certificados de este mes') }}!</h4>
                    <canvas id="pieChartCertificados" style="max-height: 800px"></canvas>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-2 ">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center justify-content-center d-none" id="msg-not-data-asesores">
                        ¡{{ __('No hay información que mostrar para los asesores de este mes') }}!</h4>
                    <canvas id="pieChartAsesores" style="max-height: 800px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <canvas id="certificados" height="100" style="max-height: 800px"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
