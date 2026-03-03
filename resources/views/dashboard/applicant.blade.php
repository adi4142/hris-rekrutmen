{{-- 
    Dashboard Pelamar (Tamu)
    Menampilkan statistik lamaran pribadi pelamar
    Variabel yang diterima dari ApplicantDashboardController:
    - $user, $applicant, $totalMyApplications, $latestApplication,
    - $pendingApplications, $acceptedApplications, $rejectedApplications,
    - $totalActiveVacancies, $activeVacancies, $applicationHistory
--}}

@extends('layouts.applicant')

@section('title', 'Dashboard Pelamar')
@section('page_title', 'Dashboard Pelamar')

@section('content')
{{-- Notifikasi jika belum memiliki profil --}}
@if(!$applicant)
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <i class="icon fas fa-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Anda belum melengkapi profil pelamar. 
            Silakan lengkapi profil Anda terlebih dahulu untuk dapat melamar lowongan.
            <a href="{{ route('applicant.profile') }}" class="btn btn-warning btn-sm ml-2">
                Lengkapi Profil <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Card Total Lamaran Saya --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalMyApplications }}</h3>
                <p>Lamaran Saya</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('applicant.applications') }}" class="small-box-footer">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Lamaran Pending --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingApplications }}</h3>
                <p>Menunggu Review</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('applicant.applications') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Lamaran Diterima --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $acceptedApplications }}</h3>
                <p>Lamaran Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('applicant.applications') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Card Lowongan Aktif --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalActiveVacancies }}</h3>
                <p>Lowongan Tersedia</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <a href="{{ route('applicant.vacancies') }}" class="small-box-footer">
                Lihat Lowongan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Status Lamaran Terbaru --}}
@if($latestApplication)
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-1"></i> Status Lamaran Terbaru
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><strong>{{ $latestApplication->jobVacancie->title ?? 'Posisi tidak tersedia' }}</strong></h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-building"></i> {{ $latestApplication->jobVacancie->departement->name ?? '-' }} |
                            <i class="fas fa-user-tag"></i> {{ $latestApplication->jobVacancie->position->name ?? '-' }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-calendar"></i> Dilamar pada: {{ $latestApplication->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        @if($latestApplication->status == 'pending')
                            <span class="badge badge-warning" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="fas fa-clock"></i> Menunggu Review
                            </span>
                        @elseif($latestApplication->status == 'accepted')
                            <span class="badge badge-success" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="fas fa-check"></i> Diterima
                            </span>
                        @elseif($latestApplication->status == 'rejected')
                            <span class="badge badge-danger" style="font-size: 1.2rem; padding: 10px 20px;">
                                <i class="fas fa-times"></i> Ditolak
                            </span>
                        @else
                            <span class="badge badge-secondary" style="font-size: 1.2rem; padding: 10px 20px;">
                                {{ ucfirst($latestApplication->status) }}
                            </span>
                        @endif
                    </div>
                    <div class="col-md-12 mt-3">
                        <a href="{{ route('applicant.application.detail', $latestApplication->application_id) }}" 
                            class="btn btn-info btn-sm" title="Lihat Detail">
                            <i class="fas fa-eye"> Lihat Detail</i> 
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Daftar Lowongan Aktif --}}
    <div class="col-md-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-briefcase mr-1"></i> Lowongan Tersedia
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($activeVacancies as $vacancy)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $vacancy->title }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $vacancy->departement->name ?? '-' }} | {{ $vacancy->position->name ?? '-' }}
                                </small>
                            </div>
                            @if($activeApplication && $activeApplication->vacancies_id == $vacancy->vacancies_id)
                                <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-check-circle"></i> Terkirim
                                </a>
                            @elseif($activeApplication)
                                <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-clock"></i> Menunggu Review
                                </a>
                            @else
                                <a href="{{ route('applicant.apply', $vacancy->vacancies_id) }}" class="btn btn-primary btn-sm">
                                    Lamar <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">
                        Tidak ada lowongan tersedia saat ini
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('applicant.vacancies') }}" class="btn btn-info btn-sm">
                    Lihat Semua Lowongan <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Riwayat Lamaran --}}
    <div class="col-md-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Riwayat Lamaran
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($applicationHistory->take(5) as $application)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $application->jobVacancie->title ?? 'Posisi tidak tersedia' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $application->created_at->format('d M Y') }}
                                </small>
                            </div>
                            @if($application->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($application->status == 'accepted')
                                <span class="badge badge-success">Diterima</span>
                            @elseif($application->status == 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">{{ $application->status }}</span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">
                        Anda belum pernah melamar
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('applicant.applications') }}" class="btn btn-success btn-sm">
                    Lihat Semua Riwayat <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Chatbot Widget HTML --}}
<div class="chat-widget minimized" id="chatWidget">
    <div class="chat-toggle-btn" onclick="toggleChat()">
        <i class="fas fa-comments"></i>
    </div>
    
    <div class="chat-header" onclick="toggleChat()">
        <span><i class="fas fa-robot mr-2"></i> HR Assistant</span>
        <i class="fas fa-times"></i>
    </div>
    
    <div class="chat-body" id="chatBody">
        <div class="message bot">
            Halo {{ $user->name }}! 👋<br><br>
            Saya HR Assistant, siap membantu Anda.<br><br>
            <div class="quick-replies">
                <button class="quick-reply-btn" onclick="quickReply('status lamaran')">Status Lamaran</button>
                <button class="quick-reply-btn" onclick="quickReply('lowongan kerja')">Lowongan</button>
                <button class="quick-reply-btn" onclick="quickReply('cara melamar')">Cara Melamar</button>
            </div>
        </div>
    </div>
    
    <div class="chat-footer">
        <div class="input-group">
            <input type="text" id="chatInput" class="form-control" placeholder="Ketik pertanyaan..." onkeypress="handleKeyPress(event)">
            <div class="input-group-append">
                <button class="btn btn-primary" onclick="sendChatMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle chatbot visibility
    function toggleChat() {
        const widget = document.getElementById('chatWidget');
        widget.classList.toggle('minimized');
        if (!widget.classList.contains('minimized')) {
            document.getElementById('chatInput').focus();
        }
    }

    // Handle Enter key press
    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            sendChatMessage();
        }
    }

    // Quick reply button
    function quickReply(message) {
        document.getElementById('chatInput').value = message;
        sendChatMessage();
    }

    // Send message to chatbot
    async function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        // Append user message
        appendChatMessage('user', message);
        input.value = '';

        // Show typing indicator
        const typingId = 'typing-' + Date.now();
        appendChatMessage('bot', '<div class="typing-indicator"><span></span><span></span><span></span></div>', typingId);

        try {
            const response = await fetch('{{ route("applicant.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Replace typing indicator with actual response
            const typingEl = document.getElementById(typingId);
            if (typingEl) {
                typingEl.innerHTML = data.reply;
            }
        } catch (error) {
            const typingEl = document.getElementById(typingId);
            if (typingEl) {
                typingEl.innerHTML = 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.';
            }
        }
    }

    // Append message to chat body
    function appendChatMessage(role, text, id = null) {
        const chatBody = document.getElementById('chatBody');
        const div = document.createElement('div');
        div.className = 'message ' + role;
        if (id) div.id = id;
        div.innerHTML = text;
        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
</script>
@endpush
