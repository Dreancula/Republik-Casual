<style>
    /* LIVE CHAT BOT CUSTOMER - REPUBLIK CASUAL PRESTIGE THEME */
    .rc-chat-trigger {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #EAE6DF; /* Sand Cream */
        color: #000000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(234, 230, 223, 0.3);
        cursor: pointer;
        z-index: 1050;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: none;
    }
    .rc-chat-trigger:hover {
        transform: scale(1.08) rotate(5deg);
        background: #ffffff;
    }
    .rc-chat-window {
        position: fixed;
        bottom: 105px;
        right: 30px;
        width: 380px;
        height: 520px;
        background: #0f0f11;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        display: flex;
        flex-direction: column;
        z-index: 1050;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        backdrop-filter: blur(20px);
    }
    .rc-chat-window.active {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }
    .rc-chat-header {
        padding: 1.25rem 1.5rem;
        background: rgba(255,255,255,0.02);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rc-chat-body {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .rc-msg {
        max-width: 80%;
        padding: 0.8rem 1.1rem;
        border-radius: 16px;
        font-size: 0.88rem;
        line-height: 1.4;
    }
    .rc-msg.bot {
        background: rgba(255, 255, 255, 0.04);
        color: #ffffff;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .rc-msg.user {
        background: #EAE6DF;
        color: #000000;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        font-weight: 500;
    }
    .rc-chat-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.2);
    }
    .rc-chat-input-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .rc-chat-input-group input {
        flex: 1;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 50px;
        color: #fff;
        padding: 0.65rem 1.2rem;
        font-size: 0.85rem;
        transition: all 0.3s;
    }
    .rc-chat-input-group input:focus {
        outline: none;
        border-color: #EAE6DF;
        background: rgba(255,255,255,0.06);
    }
    .rc-btn-send {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #EAE6DF;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        transition: all 0.2s;
    }
    .rc-btn-send:hover {
        background: #fff;
        transform: scale(1.05);
    }

    .btn-quick-reply {
    background-color: rgba(255, 255, 255, 0.04);
    color: #cccccc;
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.25s ease;
    letter-spacing: 0.3px;
    font-weight: 500;
    font-family: inherit;
}

.btn-quick-reply:hover {
    background-color: #EAE6DF;
    border-color: #EAE6DF;
    color: #000000;
}
</style>

<button class="rc-chat-trigger" id="chatTrigger">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
</button>

<div class="rc-chat-window" id="chatWindow">
    <div class="rc-chat-header d-flex align-items-center justify-content-between" style="padding: 10px 15px;">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('image/icon_rc.png') }}" alt="Republik Casual" style="width: 24px; height: 24px; object-fit: contain; display: block;">
            <div class="d-flex flex-column align-items-start" style="line-height: 1.2;">
                <span class="fw-bold" style="font-size: 0.85rem; color: #fff; letter-spacing: 0.5px; text-transform: uppercase;">REPUBLIK CASUAL</span>
                <small style="color: #999999; font-size: 0.7rem; white-space: nowrap;">Ada yang bisa kami bantu?</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" style="font-size: 0.75rem; box-shadow: none; margin: 0;" id="closeChat"></button>
    </div>
    
    <div class="rc-chat-body" id="chatBody">
        <div class="rc-msg bot">
            Welcome to Republik Casual. Saya asisten virtual Anda. Ada yang bisa saya bantu mengenai katalog produk atau pesanan Anda hari ini?
        </div>

        <div class="rc-quick-replies d-flex flex-wrap gap-2 mt-3 px-2" id="quickReplies">
            <button type="button" class="btn-quick-reply" data-msg="Apakah ada promo minggu ini?">Cek Promo</button>
            <button type="button" class="btn-quick-reply" data-msg="Bagaimana cara order produk?">Cara Order</button>
            <button type="button" class="btn-quick-reply" data-msg="Apakah produk celana cargo ready stok?">Cek Stok Cargo</button>
            <button type="button" class="btn-quick-reply" data-msg="Bisa minta kontak WhatsApp admin fisik?">Hubungi Admin</button>
        </div>
    </div>
    
    <div class="rc-chat-footer">
        <div class="rc-chat-input-group">
            <input type="text" id="chatInput" placeholder="Tulis pertanyaan Anda...">
            <button class="rc-btn-send" id="btnSendChat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatTrigger = document.getElementById('chatTrigger');
        const chatWindow = document.getElementById('chatWindow');
        const closeChat = document.getElementById('closeChat');
        const chatInput = document.getElementById('chatInput');
        const btnSendChat = document.getElementById('btnSendChat');
        const chatBody = document.getElementById('chatBody');
        let quickReplies = document.getElementById('quickReplies');
        const initialChatHTML = chatBody.innerHTML;

        if(chatTrigger) {
            chatTrigger.addEventListener('click', () => chatWindow.classList.toggle('active'));
            closeChat.addEventListener('click', () => {
                chatWindow.classList.remove('active');
                chatBody.innerHTML = initialChatHTML;
                quickReplies = document.getElementById('quickReplies');
            });

            function sendMessage() {
                const messageText = chatInput.value.trim();
                if (messageText === '') return;

                appendMessage(messageText, 'user');
                chatInput.value = '';

                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'rc-msg bot text-muted';
                typingIndicator.style.fontStyle = 'italic';
                typingIndicator.innerText = 'Memproses...';
                chatBody.appendChild(typingIndicator);
                chatBody.scrollTop = chatBody.scrollHeight;

                fetch('/chatbot/respond', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: messageText })
                })
                .then(response => response.json())
                .then(data => {
                    typingIndicator.remove();
                    appendMessage(data.reply, 'bot');
                    if (quickReplies) {
                        quickReplies.style.display = '';
                        chatBody.appendChild(quickReplies);
                    }
                })
                .catch(error => {
                    typingIndicator.remove();
                    appendMessage('Maaf, asisten kami sedang beristirahat. Silakan hubungi kami beberapa saat lagi.', 'bot');
                    if (quickReplies) {
                        quickReplies.style.display = '';
                        chatBody.appendChild(quickReplies);
                    }
                });
            }

            function appendMessage(text, sender) {
                const msgDiv = document.createElement('div');
                msgDiv.className = `rc-msg ${sender}`;
                msgDiv.innerText = text;
                chatBody.appendChild(msgDiv);
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            btnSendChat.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });

            const quickReplyButtons = document.querySelectorAll('.btn-quick-reply');
            quickReplyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    chatInput.value = this.getAttribute('data-msg');
                    if (quickReplies) quickReplies.style.display = 'none';
                    btnSendChat.click();
                });
            });
        }
    });
</script>