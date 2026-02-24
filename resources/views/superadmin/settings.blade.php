@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Sistem')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<form action="{{ route('superadmin.settings.update') }}" method="POST">
    @csrf

    {{-- ═══ PERUSAHAAN ═══ --}}
    @if(isset($settings['perusahaan']))
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-left:4px solid #007bff;">
            <i class="fas fa-building text-primary mr-2"></i>
            <h6 class="mb-0 font-weight-bold text-primary">Informasi Perusahaan</h6>
        </div>
        <div class="card-body">
            @php $labels = [
                'company_name'=>'Nama Perusahaan','company_email'=>'Email Perusahaan','company_phone'=>'Telepon',
                'company_address'=>'Alamat','company_website'=>'Website','company_tagline'=>'Tagline / Slogan'
            ]; @endphp

            @foreach($settings['perusahaan'] as $key => $value)
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ $labels[$key] ?? ucwords(str_replace('_',' ',$key)) }}</label>
                <div class="col-sm-9">
                    @if($key === 'company_address')
                        <textarea name="{{ $key }}" class="form-control" rows="2">{{ $value }}</textarea>
                    @else
                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $value }}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ REKRUTMEN ═══ --}}
    @if(isset($settings['rekrutmen']))
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-left:4px solid #28a745;">
            <i class="fas fa-user-tie text-success mr-2"></i>
            <h6 class="mb-0 font-weight-bold text-success">Pengaturan Rekrutmen</h6>
        </div>
        <div class="card-body">
            @php $rekLabels = [
                'recruitment_default_status'=>'Status Lamaran Default',
                'recruitment_min_age'=>'Usia Minimum Pelamar (tahun)',
                'recruitment_max_age'=>'Usia Maksimum Pelamar (tahun)',
                'recruitment_vacancy_duration'=>'Durasi Default Lowongan (hari)',
                'recruitment_max_applicants'=>'Maks. Pelamar Per Lowongan',
                'recruitment_auto_close_vacancy'=>'Tutup Otomatis Saat Penuh',
                'recruitment_notify_applicant'=>'Kirim Notifikasi Email',
                'recruitment_min_score_pass'=>'Nilai Minimum Lulus Seleksi',
            ]; @endphp

            @foreach($settings['rekrutmen'] as $key => $value)
            <div class="form-group row">
                <label class="col-sm-5 col-form-label">{{ $rekLabels[$key] ?? ucwords(str_replace('_',' ',$key)) }}</label>
                <div class="col-sm-7">
                    @if($key === 'recruitment_default_status')
                        <select name="{{ $key }}" class="form-control">
                            <option value="applied" {{ $value=='applied'?'selected':'' }}>Applied (Diterima)</option>
                            <option value="pending" {{ $value=='pending'?'selected':'' }}>Pending (Menunggu)</option>
                            <option value="process" {{ $value=='process'?'selected':'' }}>Process (Diproses)</option>
                        </select>
                    @elseif(in_array($key, ['recruitment_auto_close_vacancy','recruitment_notify_applicant']))
                        <select name="{{ $key }}" class="form-control">
                            <option value="1" {{ $value=='1'?'selected':'' }}>Aktif</option>
                            <option value="0" {{ $value=='0'?'selected':'' }}>Nonaktif</option>
                        </select>
                    @else
                        <input type="number" name="{{ $key }}" class="form-control" value="{{ $value }}" min="0">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ EMAIL / SMTP ═══ --}}
    @if(isset($settings['email']))
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-left:4px solid #fd7e14;">
            <i class="fas fa-envelope mr-2" style="color:#fd7e14;"></i>
            <h6 class="mb-0 font-weight-bold" style="color:#fd7e14;">Konfigurasi Email (SMTP)</h6>
            <span class="badge badge-warning ml-auto">Langsung update .env</span>
        </div>
        <div class="card-body">
            <div class="alert alert-info py-2 mb-3">
                <i class="fas fa-info-circle mr-1"></i>
                Konfigurasi ini digunakan untuk pengiriman semua email sistem. Pastikan data SMTP valid.
            </div>

            @php $emailLabels = [
                'mail_host'=>'SMTP Host','mail_port'=>'SMTP Port','mail_username'=>'Username / Email Pengirim',
                'mail_password'=>'Password / App Password','mail_encryption'=>'Enkripsi',
                'mail_from_address'=>'Alamat From','mail_from_name'=>'Nama Pengirim',
            ]; @endphp

            @foreach($settings['email'] as $key => $value)
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ $emailLabels[$key] ?? $key }}</label>
                <div class="col-sm-9">
                    @if($key === 'mail_password')
                        <input type="password" name="{{ $key }}" class="form-control" value="{{ $value }}" autocomplete="new-password"
                               placeholder="App Password Gmail (bukan password akun)">
                        <small class="text-muted">Gunakan <strong>App Password</strong> jika pakai Gmail dengan 2FA.</small>
                    @elseif($key === 'mail_port')
                        <select name="{{ $key }}" class="form-control">
                            <option value="465" {{ $value=='465'?'selected':'' }}>465 (SSL)</option>
                            <option value="587" {{ $value=='587'?'selected':'' }}>587 (TLS)</option>
                            <option value="25"  {{ $value=='25' ?'selected':'' }}>25 (Standar)</option>
                        </select>
                    @elseif($key === 'mail_encryption')
                        <select name="{{ $key }}" class="form-control">
                            <option value="ssl" {{ $value=='ssl'?'selected':'' }}>SSL</option>
                            <option value="tls" {{ $value=='tls'?'selected':'' }}>TLS</option>
                            <option value=""    {{ $value==''   ?'selected':'' }}>Tidak Ada</option>
                        </select>
                    @else
                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $value }}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Tombol Simpan ── --}}
    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-save mr-2"></i> Simpan Semua Pengaturan
        </button>
    </div>
</form>

@push('scripts')
<script>
document.querySelectorAll('.btn-generate-code').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var input = document.getElementById(this.getAttribute('data-target'));
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var code  = '';
        for (var i = 0; i < 10; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
        input.value = code;
        input.classList.add('bg-warning');
        setTimeout(function(){ input.classList.remove('bg-warning'); }, 800);
    });
});
</script>
@endpush

@endsection
