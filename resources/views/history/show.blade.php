<!-- @extends('layouts.app') -->

@section('content')
    <div class="container">
        <h1>Borrow Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Book: {{ $borrow->book->title }}</h5>
                <p><strong>Status:</strong> {{ $borrow->status }}</p>
                <p><strong>Borrow Date:</strong> {{ $borrow->tanggal_pinjam->format('d M Y') }}</p>
                <p><strong>Return Date:</strong> {{ $borrow->tanggal_kembali ? $borrow->tanggal_kembali->format('d M Y') : 'Not Returned Yet' }}</p>
                <p><strong>Penalty:</strong> {{ $borrow->denda ? 'Rp ' . number_format($borrow->denda, 2) : 'No Penalty' }}</p>
                <p><strong>Loan Code:</strong> {{ $borrow->kode_peminjaman }}</p>
            </div>
        </div>
    </div>
@endsection
