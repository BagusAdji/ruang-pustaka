@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="/css/admin/kategori.css">
@endpush
@section('page-title', 'Kategori')
@section('content')
    <div class="container">
        <header>
            <h1>Daftar Kategori</h1>
        </header>
        <button class="btn-add" type="button" id="btn-tambah" onclick="openAddModal()">
            + Tambah Kategori
        </button>
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama kategori</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kategori as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>
                                <button type="button" class="btn-edit"
                                    onclick="openEditModal('{{ route('admin.kategori.update', $item->id) }}', '{{ $item->nama_kategori }}')">
                                    Edit
                                </button>

                                <button type="button" class="btn-delete"
                                    onclick="openDeleteModal('{{ route('admin.kategori.destroy', $item->id) }}')">
                                    Hapus
                                </button>

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

    <div id="addModal" class="modal-overlay">
        <div class="modal-box">
            <h3>tambah Kategori</h3>

            <form id="Formadd" method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf

                <label>Nama Kategori</label><br>
                <input type="text" id="inputNamaKategori" name="nama_kategori" required style="width:100%"><br><br>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn-confirm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Ubah Kategori</h3>

            <form id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')

                <label>Nama Kategori</label><br>
                <input type="text" id="inputNamaKategori" name="nama_kategori" required style="width:100%"><br><br>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-confirm">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Hapus Kategori?</h3>
            <p>Kategori buku akan dihapus permanen</p>

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
        function openAddModal(actionUrl) {
            document.getElementById('addModal').classList.add('show');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('show');
        }

        function closeDeleteModal() {
            document.getElementById('addModal').classList.remove('show');
        }

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        function openEditModal(url, nama) {
            document.getElementById('formEdit').action = url;
            document.getElementById('inputNamaKategori').value = nama;
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }
    </script>
@endsection
