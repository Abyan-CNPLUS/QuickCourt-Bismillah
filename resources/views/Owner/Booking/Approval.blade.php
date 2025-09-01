<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Status Approval</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($menus as $menu)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->name }}</td>
                <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td>
                    @if($menu->approval_status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($menu->approval_status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($menu->approval_status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($menu->approval_status == 'pending')
                        <form action="{{ route('menus.approve', $menu->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('menus.reject', $menu->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                                Reject
                            </button>
                        </form>
                    @elseif($menu->approval_status == 'approved')
                        <span class="text-success">✔ Sudah disetujui</span>
                    @elseif($menu->approval_status == 'rejected')
                        <span class="text-danger">✘ Ditolak</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data menu</td>
            </tr>
        @endforelse
    </tbody>
</table>
