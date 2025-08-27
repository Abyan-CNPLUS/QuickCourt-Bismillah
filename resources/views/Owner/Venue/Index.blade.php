<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Owner | Venue</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        {{-- Navbar --}}
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Owner</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Venue</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">My Venues</h6>
                </nav>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <a href="javascript:;" class="text-white font-weight-bold px-0">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">{{ Auth::user()->name }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        {{-- End Navbar --}}

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    {{-- Flash message --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Venue List</h6>
                            <a href="{{ route('owner.venues.create') }}" class="btn btn-primary">+ Add Venue</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">City</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($venues as $index => $venue)
                                            <tr>
                                                <td class="text-sm text-center">
                                                    {{ $venues->firstItem() + $index }}
                                                </td>
                                                <td class="text-center">
                                                    @if($venue->images && $venue->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $venue->images->first()->image_url) }}" alt="Venue Image" width="80" class="rounded">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td class="text-sm text-center">{{ $venue->name }}</td>
                                                <td class="text-sm text-center">{{ $venue->category->name ?? '-' }}</td>
                                                <td class="text-sm text-center">{{ $venue->city->name ?? '-' }}</td>
                                                <td class="text-sm text-center">
                                                    <span class="badge
                                                        {{ $venue->status == 'approved' ? 'bg-success' :
                                                           ($venue->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ ucfirst($venue->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('owner.venues.edit', $venue->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('owner.venues.destroy', $venue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No venues found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-center mt-3">
                                {{ $venues->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- JS --}}
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>
