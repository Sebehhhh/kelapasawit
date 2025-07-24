@extends('layouts.app')
@section('title', 'Tambah Barang Masuk')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">Tambah Barang Masuk</div>
                <div class="card-body">
                    <form action="{{ route('admin.purchase-invoices.store') }}" method="POST" id="form-purchase-invoice">
                        @csrf
                        <div class="mb-3">
                            <label>Nama Supplier</label>
                            <input type="text" name="supplier_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Catatan</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                        <hr>
                        <h5>Detail Produk</h5>
                        <table class="table table-bordered" id="produk-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="product_id[]" class="form-control" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity[]" class="form-control qty-input" min="1" required></td>
                                    <td><input type="number" name="unit_price[]" class="form-control price-input" min="0" required></td>
                                    <td><input type="text" class="form-control subtotal-input" readonly></td>
                                    <td><button type="button" class="btn btn-danger btn-remove-row">&times;</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary mb-3" id="btn-add-row">Tambah Produk</button>
                        <div class="mb-3">
                            <label>Total</label>
                            <input type="text" name="total" id="total-input" class="form-control" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{ route('admin.purchase-invoices.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function updateSubtotal(row) {
    let qty = parseFloat(row.find('.qty-input').val()) || 0;
    let price = parseFloat(row.find('.price-input').val()) || 0;
    let subtotal = qty * price;
    row.find('.subtotal-input').val(subtotal ? subtotal.toLocaleString('id-ID') : '');
}
function updateTotal() {
    let total = 0;
    $('#produk-table tbody tr').each(function() {
        let subtotal = parseFloat($(this).find('.qty-input').val() || 0) * parseFloat($(this).find('.price-input').val() || 0);
        total += subtotal;
    });
    $('#total-input').val(total ? total.toLocaleString('id-ID') : '');
}
$(document).on('input', '.qty-input, .price-input', function() {
    let row = $(this).closest('tr');
    updateSubtotal(row);
    updateTotal();
});
$('#btn-add-row').click(function() {
    let row = $('#produk-table tbody tr:first').clone();
    row.find('input, select').val('');
    $('#produk-table tbody').append(row);
});
$(document).on('click', '.btn-remove-row', function() {
    if($('#produk-table tbody tr').length > 1) {
        $(this).closest('tr').remove();
        updateTotal();
    }
});
$('#form-purchase-invoice').submit(function() {
    $('#total-input').prop('disabled', false);
});
</script>
@endpush
@endsection 