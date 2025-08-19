<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Venue</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
    <title>
        kinoko
    </title>
    <!--Fonts and icons-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    @include('layouts.sidebardash')

    <main class="main-content position-relative border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">venue</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Venue</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center hidden">

          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class=" text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">{{Auth::User()->name}}</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    {{-- end nav --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 >Venue List</h6>
                 <a href="{{ route('admin.venues.create') }}" class="btn btn-primary">Add</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">

                    <thead>
                        <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">No</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Image</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Name Venue</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Address</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Price</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                        </tr>
                    </thead>
    <tbody>
                 @isset($venues)
                 @foreach($venues as $venue)
        <tr>
            <td class="text-sm text-center">
                @if(method_exists($venues, 'currentPage'))
                    {{ ($venues->currentPage() - 1) * $venues->perPage() + $loop->iteration }}
                @else
                    {{ $loop->iteration }}
                @endif
            </td>
            <td>
                @if($venue->images->isNotEmpty())
                <img src="{{ asset('storage/' . $venue->images->first()->image_url) }}" alt="Venue Image" width="100">
                @endif
            </td>
            <td class="text-sm text-center">{{ $venue->name ?? 'N/A' }}</td>
            <td class="text-sm text-center">{{ $venue->address ?? 'N/A' }}</td>
            <td class="text-sm text-center">Rp {{ number_format($venue->price, 2, ',', '.') }}</td>
            <td class="text-sm text-center">
                <a href="{{ route('admin.venues.edit', $venue->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach

        @if($venues->isEmpty())
        <tr>
            <td colspan="5" class="text-center text-muted">No venues found.</td>
        </tr>
        @endif
    @else
        <tr>
            <td colspan="5" class="text-center text-danger">Venues data not available.</td>
        </tr>
    @endisset
</tbody>
</table>
</div>
<div class="pagination-wrapper d-flex flex-column align-items-center gap-3 mt-4 mb-4">
    {{-- Default pagination links --}}
   <div class="pagination-wrapper d-flex justify-content-between align-items-center mt-4">
    {{-- Pagination links --}}
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm mb-0">
            {{-- Previous Page Link --}}
            @if ($venues->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $venues->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($venues->getUrlRange(1, $venues->lastPage()) as $page => $url)
                @if ($page == $venues->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($venues->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $venues->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
</div>
</div>
</div>
</div>
</div>
</main>


 <script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    {{-- <script src="../assets/js/plugins/chartjs.min.js"></script> --}}
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#5e72e4",
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: false,
            }
            },
            interaction: {
            intersect: false,
            mode: 'index',
            },
            scales: {
            y: {
                grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                padding: 10,
                color: '#fbfbfb',
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            x: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                color: '#ccc',
                padding: 20,
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            },
        },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>

</body>
</html>
