@extends('layouts.app')

@section('title', 'Detail Payroll')
@section('page_title', 'Detail Penggajian Periode ' . date("F", mktime(0, 0, 0, $payroll->period_month, 10)) . ' ' . $payroll->period_year)

@section('content')
<div class="mb-3">
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Gaji Karyawan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Gaji Pokok</th>
                        <th>Total Tunjangan</th>
                        <th>Total Potongan</th>
                        <th>Gaji Bersih (Take Home Pay)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payroll->details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $detail->employee->name ?? 'Unknown' }}</strong><br>
                            <small class="text-muted">NIP: {{ $detail->nip }}</small>
                        </td>
                        <td>Rp {{ number_format($detail->basic_salary, 0, ',', '.') }}</td>
                        <td class="text-success">+ Rp {{ number_format($detail->total_allowance, 0, ',', '.') }}</td>
                        <td class="text-danger">- Rp {{ number_format($detail->total_deduction, 0, ',', '.') }}</td>
                        <td class="font-weight-bold">Rp {{ number_format($detail->total_salary, 0, ',', '.') }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-detail-{{ $detail->payroll_detail_id }}">
                                <i class="fas fa-list"></i> Rincian
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Rincian -->
                    <div class="modal fade" id="modal-detail-{{ $detail->payroll_detail_id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Rincian Komponen Gaji: {{ $detail->employee->name }}</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-success border-bottom pb-2">Pemasukan (Tunjangan)</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Gaji Pokok
                                                    <span>Rp {{ number_format($detail->basic_salary, 0, ',', '.') }}</span>
                                                </li>
                                                @php $hasAllowance = false; @endphp
                                                @foreach($detail->components->where('type', 'allowance') as $comp)
                                                    @php $hasAllowance = true; @endphp
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $comp->name }}
                                                        <span>Rp {{ number_format($comp->amount, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                                @if(!$hasAllowance)
                                                    <li class="list-group-item text-muted small">Tidak ada tunjangan tambahan</li>
                                                @endif
                                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light font-weight-bold">
                                                    Total Pemasukan
                                                    <span>Rp {{ number_format($detail->basic_salary + $detail->total_allowance, 0, ',', '.') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-danger border-bottom pb-2">Pemotongan</h6>
                                            <ul class="list-group list-group-flush">
                                                @php $hasDeduction = false; @endphp
                                                @foreach($detail->components->where('type', 'deduction') as $comp)
                                                    @php $hasDeduction = true; @endphp
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $comp->name }}
                                                        <span>Rp {{ number_format($comp->amount, 0, ',', '.') }}</span>
                                                    </li>
                                                @endforeach
                                                @if(!$hasDeduction)
                                                    <li class="list-group-item text-muted small">Tidak ada potongan</li>
                                                @endif
                                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light font-weight-bold">
                                                    Total Potongan
                                                    <span>Rp {{ number_format($detail->total_deduction, 0, ',', '.') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-4 p-3 bg-dark text-white rounded d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">GAJI BERSIH (TAKE HOME PAY)</h5>
                                        <h4 class="mb-0 font-weight-bold text-warning">Rp {{ number_format($detail->total_salary, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data detail gaji untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
