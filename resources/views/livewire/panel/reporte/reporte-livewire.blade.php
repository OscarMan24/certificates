<div wire:init="generateChartData">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                     <h5 class="card-title">{{ __('Reportes') }}
                    </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar desde') }}</span>
                            <input type="date" class="form-control @error('startDate') is-invalid @enderror"
                                wire:model="startDate">
                            @error('startDate')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar hasta') }}</span>
                            <input type="date" class="form-control @error('endDate') is-invalid @enderror"
                                wire:model="endDate">
                            @error('endDate')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar por cursos') }}</span>
                            <select class="form-control @error('searchCurso') is-invalid @enderror"
                                wire:model="searchCurso">
                                <option value="" selected>{{ __('Todos') }}</option>
                                @foreach ($this->Cursos as $item)
                                    <option value="{{ $item->id }}">
                                        {{ '(' . $item->consecutive . ')' . ' - ' . $item->name }}</option>
                                @endforeach
                            </select>
                            @error('searchCurso')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Organizar por') }}</span>
                            <select class="form-control @error('organizarPor') is-invalid @enderror"
                                wire:model="organizarPor">
                                <option value="fecha">{{ __('Fecha') }}</option>
                                <option value="total">{{ __('Total') }}</option>
                            </select>
                            @error('organizarPor')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                         <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar') }}</span> <br>
                            <button class="btn btn-outline-primary btn-block w-100" onclick="recargarPagina()"><i class="fas fa-search"></i></button>
                        </div>
                       
                    </div>
                </div>
            </div>

            @if (count($chartData['datasets']) > 0)
                <div class=" row">
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="justify-content-center" style="max-height: 700px;">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">{{ __('Curso') }}</th>
                                                    <th scope="col">{{ __('Total') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($certificadosTotales as $item)
                                                    <tr>
                                                        <th scope="row">{{ $item['name'] }}</th>
                                                        <td>{{ $item['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                   
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center justify-content-center" style="max-height: 700px;  display: flex;">
                                    <canvas id="myChart"></canvas>
                                </div>                   
                            </div>
                        </div>
                     </div>
                </div>
            @else
              <div class="card">
                    <div class="card-body">
                        <div class="text-center justify-content-center" style="max-height: 700px;  display: flex;">
                           <h3>{{  __('No se encontraron datos con estos parametros') }}</h3>
                        </div>                   
                    </div>
                </div>                
            @endif
            
        </div>
    </div>

    <script>
        function recargarPagina() {
            location.reload();
        }
    </script>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var type = @json($chartData['type']);
        var datasets = @json($chartData['datasets']);

        var chartConfig = {
            type: type,
            data: {
                // Datos del gráfico generados dinámicamente por Livewire
                labels: @json($chartData['labels']),
                datasets: datasets
            },
            options: {
                responsive: true,
                animation: {
                    onComplete: () => {
                       // delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default') {
                            delay = context.dataIndex * 300 + context.datasetIndex * 100;
                        }
                        return delay;
                    },
                },
                plugins:{
                    datalabels: {
                        color: '#fefefe',
                        formatter: (value, context) => {
                            return value;
                        }
                    }
                }
            }
        };

        if (type === 'line') {
            chartConfig.options.scales = {
                y: {
                    beginAtZero: true
                }
            };
        } else if (type === 'pie') {
            datasets.forEach(dataset => {
                dataset.borderWidth = 0.5;
            });
            chartConfig.plugins = [ChartDataLabels];
            
        }

        var myChart = new Chart(ctx, chartConfig);
    </script>
    <style>
        .table .thead-dark th {
            color: #fff;
            background-color: #203554;
        }

        body.dark .table .thead-dark th {
            background-color: #94AEC9;
        }
    </style>
</div>