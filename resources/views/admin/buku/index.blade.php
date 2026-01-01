@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="/css/admin/buku.css">
@endpush
@section('page-title', 'Buku')
@section('content')

    <div class="container">
        <div class="header">
            <div class="filter-container">

                {{-- PENCARIAN --}}
                <form action="{{ route('admin.buku.index') }}" method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Cari judul buku..." value="{{ request('search') }}">
                    <button type="submit">Cari</button>
                </form>

                {{-- FILTER KATEGORI & STOK --}}
                <form action="{{ route('admin.buku.index') }}" method="GET" class="filter-box">

                    <select name="kategori">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select name="stok">
                        <option value="">-- Semua Stok --</option>
                        <option value="tersedia" {{ request('stok') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ request('stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>

                    <button type="submit" class="btn-filter">Terapkan</button>

                    {{-- RESET BUTTON --}}
                    <a href="{{ route('admin.buku.index') }}" class="btn-reset">
                        Reset
                    </a>
                </form>

            </div>
            <a href="{{ route('admin.buku.create') }}" class="btn-add">+ Tambah Buku</a>
        </div>


        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ISBN</th>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($buku as $index => $item)
                        <tr>
                            <td>{{ $buku->firstItem() + $index }}</td>
                            <td>
                                <div style="width: 150px; margin: 0;">
                                    {!! $item->barcode !!}
                                </div>

                                <small class="isbn">
                                    {{ $item->isbn_formatted }}
                                </small>
                            </td>
                            <td>
                                <img src="{{ asset('storage/' . $item->cover) }}" class="cover-img">
                            </td>

                            <td>{{ $item->judul_buku }}</td>
                            <td>{{ $item->kategori->nama_kategori }}</td>
                            <td>{{ $item->jumlah }}</td>

                            <td>
                                <a href="{{ route('admin.buku.edit', $item->id) }}" class="btn-edit">Edit</a>

                                <button type="button" class="btn-delete"
                                    onclick="openDeleteModal('{{ route('admin.buku.destroy', $item->id) }}')">
                                    Hapus
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:20px;">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PERBAIKAN: Menambahkan Link Pagination --}}
        <div class="pagination-wrapper">
            {{ $buku->withQueryString()->links() }}
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Hapus Buku?</h3>
            <p>Data buku akan dihapus permanen beserta cover-nya.</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn-confirm">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }
    </script>
@endsection
