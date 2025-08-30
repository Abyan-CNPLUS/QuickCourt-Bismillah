<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Venue Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Daftar Venue Pending Approval</h2>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($venues->isEmpty())
      <div class="alert alert-info">Tidak ada venue pending saat ini.</div>
    @else
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama Venue</th>
            <th>Kategori</th>
            <th>Kota</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($venues as $venue)
          <tr>
            <td>{{ $venue->name }}</td>
            <td>{{ $venue->category->name ?? '-' }}</td>
            <td>{{ $venue->city->name ?? '-' }}</td>
            <td>{{ $venue->address }}</td>
            <td>
              <span class="badge bg-warning text-dark">{{ $venue->approval_status }}</span>
            </td>
            <td>
              <form action="{{ route('admin.venues.approve', $venue->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Approve</button>
              </form>
              <form action="{{ route('admin.venues.reject', $venue->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</body>
</html>
