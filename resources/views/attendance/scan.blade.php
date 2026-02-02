@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Absensi Kehadiran</h5>
                </div>
                <div class="card-body text-center">
                    <div id="camera_container" class="mb-3" style="position: relative; display: inline-block;">
                        <video id="video" width="100%" height="auto" autoplay class="rounded border"></video>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <div id="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 2px dashed rgba(255,255,255,0.5); pointer-events: none; border-radius: 8px;"></div>
                    </div>

                    <div id="result_container" class="mb-3 d-none">
                        <img id="photo_preview" src="" class="img-fluid rounded border">
                    </div>

                    <div class="user-info mb-3">
                        <h6>{{ $employee->name }}</h6>
                        <p class="text-muted mb-0">NIP: {{ $employee->nip }}</p>
                        <p class="badge badge-info">{{ Carbon\Carbon::now()->format('d M Y') }}</p>
                    </div>

                    @if(!$attendance)
                        <button id="btn-absen" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-sign-in-alt"></i> Absen Masuk
                        </button>
                    @elseif(!$attendance->time_out)
                        <button id="btn-absen" class="btn btn-danger btn-lg btn-block">
                            <i class="fas fa-sign-out-alt"></i> Absen Keluar
                        </button>
                    @else
                        <div class="alert alert-success">
                            Anda sudah selesai absen hari ini.
                        </div>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Lihat Riwayat</a>
                    @endif

                    <div id="status_message" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const btnAbsen = document.getElementById('btn-absen');
    const photoPreview = document.getElementById('photo_preview');
    const cameraContainer = document.getElementById('camera_container');
    const resultContainer = document.getElementById('result_container');
    const statusMessage = document.getElementById('status_message');

    // Access webcam
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Error accessing camera: ", err);
            statusMessage.innerHTML = `<div class="alert alert-danger">Kamera tidak dapat diakses. Pastikan izin kamera diberikan.</div>`;
        });

    if (btnAbsen) {
        btnAbsen.addEventListener('click', function() {
            // Disable button
            btnAbsen.disabled = true;
            btnAbsen.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

            // Capture photo
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');

            // Show preview
            photoPreview.src = imageData;
            cameraContainer.classList.add('d-none');
            resultContainer.classList.remove('d-none');

            // Send to server
            fetch('{{ route("attendance.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image: imageData,
                    nip: '{{ $employee->nip }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusMessage.innerHTML = `<div class="alert alert-success">${data.success}</div>`;
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    statusMessage.innerHTML = `<div class="alert alert-danger">${data.error || 'Terjadi kesalahan.'}</div>`;
                    btnAbsen.disabled = false;
                    btnAbsen.innerHTML = 'Coba Lagi';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusMessage.innerHTML = `<div class="alert alert-danger">Gagal menghubungi server.</div>`;
                btnAbsen.disabled = false;
                btnAbsen.innerHTML = 'Coba Lagi';
            });
        });
    }
</script>

<style>
    .badge-info {
        background-color: #17a2b8;
        color: white;
        padding: 5px 10px;
    }
    .fas {
        margin-right: 5px;
    }
</style>
@endsection
