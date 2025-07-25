@extends('layouts.app')
@section('title', 'Manajemen Metode Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Manajemen Metode Pembayaran</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPaymentMethod">
                        <i class="ti ti-plus me-1"></i>Tambah Metode Pembayaran
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis</th>
                                    <th>Nama</th>
                                    <th>Nomor</th>
                                    <th>Nama Pemilik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethods as $index => $method)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($method->type == 'e-wallet')
                                            <span class="badge bg-info">💳 E-Wallet</span>
                                        @else
                                            <span class="badge bg-success">🏦 Transfer Bank</span>
                                        @endif
                                    </td>
                                    <td>{{ $method->name }}</td>
                                    <td class="fw-bold text-primary">{{ $method->account_number }}</td>
                                    <td>{{ $method->account_name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-edit" 
                                            data-id="{{ $method->id }}"
                                            data-type="{{ $method->type }}"
                                            data-name="{{ $method->name }}"
                                            data-account-number="{{ $method->account_number }}"
                                            data-account-name="{{ $method->account_name }}"
                                            data-instructions="{{ $method->instructions }}"
                                            data-bs-toggle="modal" data-bs-target="#modalPaymentMethod">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $method->id }}" data-name="{{ $method->name }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="ti ti-inbox fs-1 text-muted"></i>
                                        <p class="text-muted">Belum ada metode pembayaran</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah/Edit Metode Pembayaran --}}
<div class="modal fade" id="modalPaymentMethod" tabindex="-1" aria-labelledby="modalPaymentMethodLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formPaymentMethod" autocomplete="off">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            <input type="hidden" name="payment_method_id" id="paymentMethodId">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPaymentMethodLabel">
                        <i class="ti ti-credit-card me-2"></i>Tambah Metode Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Metode</label>
                                <select name="type" id="paymentType" class="form-select" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="e-wallet">💳 E-Wallet</option>
                                    <option value="rekening">🏦 Transfer Bank</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Metode</label>
                                <input type="text" name="name" id="paymentName" class="form-control" placeholder="Contoh: Dana, BCA, Mandiri" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor Rekening/E-Wallet</label>
                                <input type="text" name="account_number" id="accountNumber" class="form-control" placeholder="Contoh: 1234567890" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Pemilik</label>
                                <input type="text" name="account_name" id="accountName" class="form-control" placeholder="Contoh: UKM Kelapa Sawit" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Instruksi Pembayaran</label>
                        <textarea name="instructions" id="paymentInstructions" class="form-control" rows="3" placeholder="Contoh: Transfer ke rekening yang tertera, lalu kirim bukti transfer"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    
                    <div id="paymentMethodAlert" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Reset modal saat ditutup
document.getElementById('modalPaymentMethod').addEventListener('hidden.bs.modal', function () {
    document.getElementById('formPaymentMethod').reset();
    document.getElementById('methodField').value = 'POST';
    document.getElementById('paymentMethodId').value = '';
    document.getElementById('modalPaymentMethodLabel').innerHTML = '<i class="ti ti-credit-card me-2"></i>Tambah Metode Pembayaran';
    
    // Remove validation classes
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.getElementById('paymentMethodAlert').classList.add('d-none');
});

// Handle edit button
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const type = this.dataset.type;
        const name = this.dataset.name;
        const accountNumber = this.dataset.accountNumber;
        const accountName = this.dataset.accountName;
        const instructions = this.dataset.instructions;
        
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('paymentMethodId').value = id;
        document.getElementById('paymentType').value = type;
        document.getElementById('paymentName').value = name;
        document.getElementById('accountNumber').value = accountNumber;
        document.getElementById('accountName').value = accountName;
        document.getElementById('paymentInstructions').value = instructions;
        
        document.getElementById('modalPaymentMethodLabel').innerHTML = '<i class="ti ti-edit me-2"></i>Edit Metode Pembayaran';
    });
});

// Handle delete button
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if (confirm(`Apakah Anda yakin ingin menghapus metode pembayaran "${name}"?`)) {
            fetch(`{{ route('admin.payment-methods.index') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
    });
});

// Handle form submission
document.getElementById('formPaymentMethod').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    
    const formData = new FormData(this);
    const method = document.getElementById('methodField').value;
    const id = document.getElementById('paymentMethodId').value;
    
    let url = '{{ route("admin.payment-methods.store") }}';
    if (method === 'PUT') {
        url = `{{ route('admin.payment-methods.index') }}/${id}`;
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            // Handle validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field.replace('_', ''));
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = data.errors[field][0];
                        }
                    }
                });
            } else {
                document.getElementById('paymentMethodAlert').textContent = data.message || 'Terjadi kesalahan';
                document.getElementById('paymentMethodAlert').classList.remove('d-none');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('paymentMethodAlert').textContent = 'Terjadi kesalahan saat menyimpan data';
        document.getElementById('paymentMethodAlert').classList.remove('d-none');
    })
    .finally(() => {
        btn.disabled = false;
    });
});
</script>
@endpush