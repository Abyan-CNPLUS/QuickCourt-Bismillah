<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Venue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Venue</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Kota</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Approval</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh data statis -->
                <tr>
                    <td>GOR Satria</td>
                    <td>Jakarta</td>
                    <td>Futsal</td>
                    <td>available</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Detail</a>
                        <button class="btn btn-sm btn-success">Approve</button>
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </td>
                </tr>
                <tr>
                    <td>Stadion Harapan</td>
                    <td>Bandung</td>
                    <td>Bulu Tangkis</td>
                    <td>booked</td>
                    <td><span class="badge bg-success">Accepted</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Detail</a>
                        <button class="btn btn-sm btn-success">Approve</button>
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </td>
                </tr>
                <tr>
                    <td>Lapangan Utama</td>
                    <td>Surabaya</td>
                    <td>Basket</td>
                    <td>available</td>
                    <td><span class="badge bg-danger">Rejected</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Detail</a>
                        <button class="btn btn-sm btn-success">Approve</button>
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
