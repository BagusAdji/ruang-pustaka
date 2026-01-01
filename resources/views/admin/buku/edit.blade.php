@extends('admin.layout')
@push('css')
    <link rel="stylesheet" href="/css/admin/buku.css">
@endpush
@section('page-title', 'Edit Buku')

@section('content')
    <div class="modern-form-container">

        <div class="form-header">
            <h2>Edit Data Buku</h2>
        </div>

        <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">ID Buku</label>
                    <input type="text" class="form-input" value="{{ $buku->id }}" readonly
                        style="background-color: #e9ecef; cursor: not-allowed;">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul', $buku->judul_buku) }}"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}"
                                {{ old('kategori_id', $buku->kategori_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Stok</label>
                    <input type="number" name="jumlah" class="form-input" value="{{ old('jumlah', $buku->jumlah) }}"
                        required>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Cover Buku</label>
                    <div class="upload-area">
                        <input type="file" name="cover" class="form-input" accept="image/*"
                            onchange="previewImage(event)" style="border:none; background:transparent; padding:0;">
                        <small style="display:block; color:gray; margin-bottom:10px;">Biarkan kosong jika tidak ingin
                            mengubah cover</small>

                        <div class="img-preview-container">
                            @if ($buku->cover)
                                <img id="preview" src="{{ asset('storage/' . $buku->cover) }}" class="img-preview">
                            @else
                                <img id="preview" src="/img/no-cover.png" class="img-preview">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
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
@endsection
