@extends('layouts.applicant')

@section('title', 'HR Assistant')

@section('content')
<style>
    .chat-container {
        max-width: 900px;
        margin: 0 auto;
        height: calc(100vh - 180px);
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg,#002fffff 0%, #0976d6ff 100%);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .chat-header {
        background: linear-gradient(135deg,#002fffff 0%, #0976d6ff 100%);
        backdrop-filter: blur(10px);
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chat-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #00d4ff 0%, #7c3aed 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .chat-header-text h4 {
        margin: 0;
        color: #fff;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .chat-header-text span {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 25px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        background: #f8fafc;
    }

    .message {
        max-width: 80%;
        padding: 15px 20px;
        border-radius: 20px;
        position: relative;
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-user {
        align-self: flex-end;
        background: linear-gradient(135deg, #002fffff 0%, #0976d6ff 100%);
        color: #fff;
        border-bottom-right-radius: 5px;
    }

    .message-bot {
        align-self: flex-start;
        background: #fff;
        color: #374151;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .message-bot::before {
        content: '🤖';
        position: absolute;
        left: -35px;
        top: 10px;
        font-size: 24px;
    }

    .message-time {
        font-size: 0.7rem;
        opacity: 0.7;
        margin-top: 5px;
        text-align: right;
    }

    .chat-input-container {
        background: #fff;
        padding: 20px 25px;
        border-top: 1px solid #e5e7eb;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        padding: 15px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 30px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        outline: none;
    }

    .chat-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .chat-send-btn {
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 50%;
        background: linear-gradient(135deg, #002fffff 0%, #0976d6ff 100%);
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .chat-send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .typing-indicator {
        display: none;
        align-self: flex-start;
        padding: 15px 20px;
        background: #fff;
        border-radius: 20px;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-left: 35px;
    }

    .typing-indicator.show {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(1) { animation-delay: 0s; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-8px); }
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 25px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .quick-action-btn {
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        font-size: 0.8rem;
        color: #667eea;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quick-action-btn:hover {
        background: #667eea;
        color: #fff;
        border-color: #667eea;
    }

    .welcome-message {
        text-align: center;
        padding: 30px;
    }

    .welcome-message h5 {
        color: #374151;
        margin-bottom: 10px;
    }

    .welcome-message p {
        color: #6b7280;
        font-size: 0.9rem;
    }
</style>

<div class="chat-container">
    <!-- Header -->
    <div class="chat-header">
        <div class="chat-avatar">🤖</div>
        <div class="chat-header-text">
            <h4>HR Assistant AI</h4>
            <span class="status-indicator">
                <span class="status-dot"></span>
                Online - Siap membantu Anda
            </span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <button class="quick-action-btn" onclick="quickSend('Status lamaran saya')">📋 Status Lamaran</button>
        <button class="quick-action-btn" onclick="quickSend('Lowongan yang tersedia')">💼 Lowongan</button>
        <button class="quick-action-btn" onclick="quickSend('Cara melamar kerja')">📝 Cara Melamar</button>
        <button class="quick-action-btn" onclick="quickSend('Info profil saya')">👤 Profil Saya</button>
        <button class="quick-action-btn" onclick="quickSend('Tahapan seleksi')">📊 Tahapan Seleksi</button>
    </div>

    <!-- Messages -->
    <div class="chat-messages" id="chatMessages">
        <div class="welcome-message">
            <h5>👋 Selamat datang, {{ auth()->user()->name }}!</h5>
            <p>Saya HR Assistant AI, siap membantu Anda dengan pertanyaan seputar lamaran kerja dan rekrutmen.</p>
        </div>
    </div>

    <!-- Typing Indicator -->
    <div class="typing-indicator" id="typingIndicator">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Input -->
    <div class="chat-input-container">
        <div class="chat-input-wrapper">
            <input 
                type="text" 
                id="messageInput" 
                class="chat-input" 
                placeholder="Ketik pertanyaan Anda..." 
                maxlength="1000"
                onkeypress="handleKeyPress(event)"
            >
            <button class="chat-send-btn" id="sendBtn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');

    function getCurrentTime() {
        return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function addMessage(content, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'message-user' : 'message-bot'}`;
        messageDiv.innerHTML = `
            <div class="message-content">${content}</div>
            <div class="message-time">${getCurrentTime()}</div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTyping() {
        typingIndicator.classList.add('show');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTyping() {
        typingIndicator.classList.remove('show');
    }

    async function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, true);
        messageInput.value = '';
        sendBtn.disabled = true;

        // Show typing indicator
        showTyping();

        try {
            const response = await fetch("{{ route('applicant.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            
            hideTyping();
            
            if (data.status === 'success') {
                addMessage(data.reply);
            } else {
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi. 🙏');
            }
        } catch (error) {
            console.error('Error:', error);
            hideTyping();
            addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi. 🔧');
        }

        sendBtn.disabled = false;
        messageInput.focus();
    }

    function quickSend(message) {
        messageInput.value = message;
        sendMessage();
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

    // Focus input on load
    document.addEventListener('DOMContentLoaded', function() {
        messageInput.focus();
    });
</script>
@endpush
@endsection
