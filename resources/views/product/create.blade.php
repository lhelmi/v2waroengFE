@extends('layouts.app')
@section('content')
<div class="container">
    <div class="p-2 border border-success rounded-bottom shadow p-3 mb-5 bg-body-tertiary rounded" style="--bs-bg-opacity: .5;">
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Barcode</label>
                <input type="text" class="form-control" id="barcode" name="barcode">
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Harga Jual</label>
                <input type="text" class="form-control" id="price" name="price">
            </div>
            <div class="mb-3">
                <label for="barcode" class="form-label">Harga Beli</label>
                <input type="text" class="form-control" id="purchase_price" name="purchase_price">
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" checked>
                <label class="form-check-label" for="flexSwitchCheckChecked">Aktif</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script type="module">

    </script>
@endsection
