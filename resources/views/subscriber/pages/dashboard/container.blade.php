<div class="page-content-dash">
    <style>
        .chart-container {
    position: relative;
    width: 100%;
    height: 400px; /* or any height you want */
}

    </style>
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mt-3">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Total Internal Radio 
                                    {{-- <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i> --}}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-radio-2-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $totalInternalRadios }}">{{ $totalInternalRadios }}</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Total External Radio 
                                    {{-- <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i> --}}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-radio-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $totalExternalRadios }}">{{ $totalExternalRadios }}</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Total Likes 
                                    {{-- <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i> --}}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-heart-2-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $totalLikes }}">{{ $totalLikes }}</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Total Followers 
                                    {{-- <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i> --}}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-user-6-fill display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $totalFollowers }}">{{ $totalFollowers }}</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Total Listeners 
                                    {{-- <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i> --}}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-broadcast-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $nonUniqueListeners2 + $totalListeners2}}">{{ $nonUniqueListeners2 + $totalListeners2}}</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-2">
                <label for="month-select">Select Month</label>
                <select id="month-select" wire:model="month" class="form-control">
                    <option value="{{ now()->month }}">{{ \Carbon\Carbon::createFromDate(null, now()->month, 1)->format('F') }}</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}</option>
                    @endfor
                    <option value="all">All</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="year-select">Select Year</label>
                <select id="year-select" wire:model="year" class="form-control">
                    @for($y = now()->year - 5; $y <= now()->year; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        
        <!-- Listeners Chart -->
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            Listeners Chart 
                            @if($month === 'all')
                                (Year {{ $year }})
                            @else
                                ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }})
                            @endif
                        </h4>
                    </div>
                    <div class="card-body" style="position: relative; height:27vh; width:auto" wire:ignore>
                        <canvas id="listenersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="mx-auto p-3">
                        <lord-icon
                        src="https://cdn.lordicon.com/rzgcaxjz.json"
                        trigger="loop"
                        state="loop-roll"
                        colors="primary:#556677,secondary:#cc0022"
                        style="width:128px;height:128px">
                    </lord-icon>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Stats of 
                            @if($month === 'all')
                                (Year {{ $year }})
                            @else
                                ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }})
                            @endif:</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Total Unique Listeners: {{ array_sum($uniqueData) }}
                        </li>
                        <li class="list-group-item">
                            Total Non-Unique Listeners: {{ array_sum($nonUniqueData) }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Second Chart: Radio Counts & Highest Peak -->
        <div class="row mb-5">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Highest Peak Listeners</h4>
                    </div>
                    <div class="card-body" style="position: relative; height:27vh; width:auto" wire:ignore>
                        <canvas id="peakChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="mx-auto p-3">
                        <lord-icon
                        src="https://cdn.lordicon.com/rzgcaxjz.json"
                        trigger="loop"
                        state="loop-roll"
                        colors="primary:#556677,secondary:#cc0022"
                        style="width:128px;height:128px">
                    </lord-icon>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Radio Stats:</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Total Internal Radio: {{ $totalInternalRadios }}</li>
                        <li class="list-group-item">Total External Radio: {{ $totalExternalRadios }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("livewire:load", function(){
            // Create the listeners chart and store it in window.
            var ctx = document.getElementById('listenersChart').getContext('2d');
            window.listenersChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Unique Listeners',
                            data: @json($uniqueData),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Non-Unique Listeners',
                            data: @json($nonUniqueData),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                        }
                        // {
                        //     label: 'Highest Peak Listeners',
                        //     data: @json($highestPeakData),
                        //     borderColor: 'rgba(75, 192, 192, 1)',
                        //     backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        //     fill: true,
                        // }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: {
                            tension: 0.4, //Default smoothness for all lines. You can also apply it to the dataset.
                        }
                    }
                }
            });
            
            // Create the peak chart as well, if needed.
            var ctx2 = document.getElementById('peakChart').getContext('2d');
            window.peakChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Highest Peak Listeners',
                            data: @json($highestPeakData),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: {
                            tension: 0.4, //Default smoothness for all lines. You can also apply it to the dataset.
                        }
                    }
                }
            });
            
            // Listen for Livewire event and update the charts.
            Livewire.on('listenersDataUpdated', function(data) {
                // Update listenersChart
                window.listenersChart.data.labels = data.labels;
                window.listenersChart.data.datasets[0].data = data.uniqueData;
                window.listenersChart.data.datasets[1].data = data.nonUniqueData;
                // window.listenersChart.data.datasets[2].data = data.highestPeakData;
                window.listenersChart.update();
                
                // Update peakChart if needed.
                window.peakChart.data.labels = data.labels;
                window.peakChart.data.datasets[0].data = data.highestPeakData;
                window.peakChart.update();
            });
        });
    </script>
@endpush
