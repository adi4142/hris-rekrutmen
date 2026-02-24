<div id="chatbox" style="height:300px;overflow-y:auto;border:1px solid #ccc;padding:10px"></div>

<input type="text" id="message" placeholder="Tanyakan sesuatu..." class="form-control">
<button onclick="sendMessage()" class="btn btn-primary mt-2">Kirim</button>

<script>
function sendMessage() {
    let message = document.getElementById('message').value;
    if (!message) return;

    let chatbox = document.getElementById('chatbox');
    chatbox.innerHTML += `<b>Kamu:</b> ${message}<br>`;

    fetch("{{ route('chatbot.chat') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        chatbox.innerHTML += `<b>Bot:</b> ${data.reply}<br><br>`;
        document.getElementById('message').value = '';
        chatbox.scrollTop = chatbox.scrollHeight;
    });
}
</script>
