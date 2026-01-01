@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="/css/admin/peminjman.css">
@endpush
@section('page-title', 'Peminjaman')
@section('content')
    <div class="container">
        <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="filter-container">

            <input type="text" name="search" class="filter-input" placeholder="Cari nama peminjam, buku, atau ID..."
                value="{{ request('search') }}">

            <select name="status" class="filter-select">
                <option value="">-- Semua Status --</option>
                <option value="booking" {{ request('status') == 'booking' ? 'selected' : '' }}>Booking</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Kembali</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>

            <button type="submit" class="btn-search">Cari</button>

            @if (request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-reset">Reset</a>
            @endif

        </form>

        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($peminjaman as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->users->nama }}</td>
                            <td>{{ $item->buku->judul_buku }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M, Y') }}</td>
                            </td>
                            <td>{!! $item->status_badge !!}</td>

                            <td>
                                <a href="{{ route('admin.peminjaman.detail', $item->id) }}" class="btn-view">View
                                    Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:20px;">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection
