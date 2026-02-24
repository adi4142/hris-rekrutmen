@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 mt-5">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text mr-2"></i> Verifikasi Email</h4>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <p class="text-muted">
                            Kode verifikasi telah dikirim ke email <strong>{{ Auth::user()->email }}</strong>. 
                            Silakan masukkan kode tersebut di bawah ini untuk mengaktifkan akun Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('emails.verify') }}">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="verification_code" class="font-weight-bold">Kode Verifikasi</label>
                            <input id="verification_code" type="text" 
                                class="form-control form-control-lg text-center @error('verification_code') is-invalid @enderror" 
                                name="verification_code" required autofocus 
                                placeholder="CONTOH: A1B2C3"
                                style="letter-spacing: 5px; font-weight: bold; font-size: 1.5rem;">

                            @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block btn-lg shadow">
                                <i class="fas fa-check-circle mr-2"></i> Verifikasi Sekarang
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-1 text-muted small">Belum menerima kode?</p>
                        <form method="POST" action="{{ route('emails.verify.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 text-primary font-weight-bold">
                                <i class="fas fa-redo mr-1"></i> Kirim Ulang Kode
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted">
                        <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background: #f4f6f9;
    }
    .card {
        border-radius: 15px;
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 10px;
    }
    .btn-primary:hover {
        background-color: #0069d9;
    }
</style>
@endpush
