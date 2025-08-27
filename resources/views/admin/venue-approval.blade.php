<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Venues</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Pending Venues</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Owner</th>
                <th>Venue Name</th>
                <th>Address</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($venues as $venue)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $venue->owner->name ?? 'N/A' }}</td>
                <td>{{ $venue->name }}</td>
                <td>{{ $venue->address }}</td>
                <td>Rp {{ number_format($venue->price, 0, ',', '.') }}</td>
                <td>
                    <form action="{{ route('admin.update-venue-status', $venue->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button name="status" value="accepted" class="btn btn-success btn-sm">Accept</button>
                        <button name="status" value="rejected" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No pending venues.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Back to Venues</a>
</div>
</body>
</html>
