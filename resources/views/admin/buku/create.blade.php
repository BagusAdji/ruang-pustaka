@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="/css/admin/buku.css">
@endpush
@section('page-title', 'Tambah Buku')
@section('content')

    <div class="modern-form-container">

        <div class="form-header">
            <h2>Tambah Buku Baru</h2>
        </div>

        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul') }}"
                        placeholder="Masukkan judul lengkap...">
                </div>

                <div class="form-group">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-input" value="{{ old('penulis') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" class="form-input" value="{{ old('penerbit') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="form-input" value="{{ old('tahun_terbit') }}"
                        placeholder="Contoh: 2024">
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="jumlah" class="form-input" value="{{ old('jumlah') }}">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Sinopsis</label>
                    <textarea name="sinopsis" class="form-input" rows="3">{{ old('sinopsis') }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Cover Buku</label>
                    <div class="upload-area">
                        <input type="file" name="cover" accept="image/*" onchange="previewImage(event)"
                            class="form-input" style="border:none; background:transparent; padding:0;">
                        <div class="img-preview-container">
                            <img id="preview" src="/img/no-cover.png" class="img-preview" alt="Preview Cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Buku</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            if (event.target.files.length > 0) {
                document.getElementById('preview').src = URL.createObjectURL(event.target.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if ($errors->any())
            let errorMessages = '';
            @foreach (array_unique($errors->all()) as $error)
                errorMessages += '<li>{{ $error }}</li>';
            @endforeach
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan!',
                html: '<ul style="text-align: left;">' + errorMessages + '</ul>',
            });
        @endif
    </script>
@endsection
