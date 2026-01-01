@extends('admin.layout')

@section('content')
<h2>Edit Kategori</h2>

<form method="POST" action="{{ route('admin.kategori.update', $kategori->id) }}">
    @csrf
    @method('PUT')

    <label>Nama Kategori</label><br>
    <input type="text" name="nama" value="{{ $kategori->nama }}" required><br><br>

    <button type="submit">Update</button>
</form>
@endsection
