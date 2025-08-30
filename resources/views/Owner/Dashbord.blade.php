<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
  <title>Owner Dashboard - QuickCourt</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>

  {{-- Sidebar Owner --}}
  @include('layouts.sidebarowner')

  <main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Owner</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
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
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid py-4">
      <div class="row">
        {{-- Total Venue --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Venue</p>
                  <h5 class="font-weight-bolder">{{ $venueCount }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-building text-lg opacity-10"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Total Booking --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Booking</p>
                  <h5 class="font-weight-bolder">{{ $bookingCount }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-calendar-grid-58 text-lg opacity-10"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Food & Beverages --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Food & Beverages</p>
                  <h5 class="font-weight-bolder">{{ $foodcount }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-basket text-lg opacity-10"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Categories --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-uppercase font-weight-bold">Categories</p>
                  <h5 class="font-weight-bolder">{{ $categories->count() }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-collection text-lg opacity-10"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Chart + Categories List --}}
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Booking Overview</h6>
            </div>
            <div class="card-body p-3">
              <canvas id="bookingChart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Categories</h6>
            </div>
            <div class="card-body p-3">
              <ul class="list-group">
                @foreach($categories as $category)
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex align-items-center">
                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                      <i class="ni ni-{{ $loop->odd ? 'mobile-button' : 'tag' }} text-white opacity-10"></i>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                    </div>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark">
                      <i class="ni ni-bold-right"></i>
                    </button>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Core JS -->
  <script src="{{ asset('argon/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('argon/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('argon/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('argon/js/plugins/smooth-scrollbar.min.js') }}"></script>

  <script>
    const ctx = document.getElementById('bookingChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Booking Bulanan',
                data: @json($data),
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94, 114, 228, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
    });
  </script>
</body>
</html>
