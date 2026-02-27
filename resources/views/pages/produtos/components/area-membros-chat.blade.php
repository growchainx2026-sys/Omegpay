@php
    if (!isset($alunos)) {
        $alunos = \App\Models\Pedido::where('produto_id', $produto->id)
            ->where('status', 'pago')
            ->with('aluno')
            ->get()
            ->pluck('aluno')
            ->unique('id')
            ->filter();
    }
    $lastMessagesByAluno = $lastMessagesByAluno ?? [];
@endphp

<style>
/* Design Clean para Chat */
.chat-container {
    display: flex;
    flex-direction: row;
    height: 600px;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

body.dark-mode .chat-container {
    background: #0f172a;
    border-color: #1e293b;
}

.chat-sidebar {
    width: 300px;
    background: #f9fafb;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

body.dark-mode .chat-sidebar {
    background: #1e293b;
    border-right-color: #334155;
}

.chat-sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #ffffff;
}

body.dark-mode .chat-sidebar-header {
    background: #0f172a;
    border-bottom-color: #1e293b;
}

.chat-sidebar-header h6 {
    margin: 0;
    font-weight: 500;
    color: #111827;
    font-size: 15px;
}

body.dark-mode .chat-sidebar-header h6 {
    color: #e2e8f0;
}

.chat-search {
    padding: 12px 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #ffffff;
}

body.dark-mode .chat-search {
    background: #0f172a;
    border-bottom-color: #1e293b;
}

.chat-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    background: #ffffff;
}

body.dark-mode .chat-search input {
    border-color: #1e293b;
    background: #0f172a;
    color: #e2e8f0;
}

.chat-search input:focus {
    outline: none;
    border-color: var(--gateway-primary-color, #0b6856);
}

body.dark-mode .chat-search input:focus {
    border-color: var(--gateway-primary-color, #0b6856);
}

.chat-list {
    flex: 1;
    overflow-y: auto;
    padding: 8px;
}

.chat-item {
    padding: 12px 16px;
    border-radius: 0;
    margin-bottom: 0;
    border-bottom: 1px solid #e5e7eb;
    cursor: pointer;
    transition: all 0.2s;
    background: #ffffff;
    border-left: 3px solid transparent;
    position: relative;
}

body.dark-mode .chat-item {
    background: #1e293b;
    border-bottom-color: #334155;
}

.chat-item:hover {
    background: #f3f4f6;
}

body.dark-mode .chat-item:hover {
    background: #334155;
}

.chat-item.active {
    background: #f0fdf4;
    border-left-color: var(--gateway-primary-color, #0b6856);
}

body.dark-mode .chat-item.active {
    background: rgba(11, 104, 86, 0.15);
}

.chat-item-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
}

.chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gateway-primary-color, #0b6856);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
    overflow: hidden;
}
.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.chat-item.active .chat-avatar {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.chat-item-info {
    flex: 1;
    min-width: 0;
}

.chat-item-name {
    font-weight: 500;
    font-size: 14px;
    color: #111827;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

body.dark-mode .chat-item-name {
    color: #e2e8f0;
}

.chat-item.active .chat-item-name {
    color: var(--gateway-primary-color, #0b6856);
    font-weight: 600;
}

body.dark-mode .chat-item.active .chat-item-name {
    color: var(--gateway-primary-color, #0b6856);
}

.chat-item-time {
    font-size: 11px;
    color: #6b7280;
    margin: 0;
}

.chat-item.active .chat-item-time {
    color: #6b7280;
}

body.dark-mode .chat-item.active .chat-item-time {
    color: #94a3b8;
}

.chat-item-preview {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-item-unread {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ef4444;
    flex-shrink: 0;
}

.chat-item.has-unread .chat-item-name {
    font-weight: 600;
}

.chat-toast-new-msg {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: #0f172a;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    z-index: 9999;
    font-size: 14px;
    animation: chatToastIn 0.3s ease;
}

@keyframes chatToastIn {
    from { opacity: 0; transform: translateX(-50%) translateY(10px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

.chat-item.active .chat-item-preview {
    color: var(--gateway-primary-color, #0b6856);
    font-weight: 500;
}

body.dark-mode .chat-item.active .chat-item-preview {
    color: var(--gateway-primary-color, #0b6856);
}

.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

body.dark-mode .chat-main {
    background: #0f172a;
}

.chat-main-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

body.dark-mode .chat-main-header {
    background: #0f172a;
    border-bottom-color: #1e293b;
}

.chat-main-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chat-main-header h6 {
    margin: 0;
    font-weight: 600;
    color: #111827;
    font-size: 16px;
}

.chat-main-header .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f9fafb;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

body.dark-mode .chat-messages {
    background: #0f172a;
}

.chat-message {
    display: flex;
    gap: 12px;
    max-width: 70%;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-message.sent {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.chat-message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gateway-primary-color, #0b6856);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 600;
    font-size: 12px;
    flex-shrink: 0;
}

.chat-message-content {
    background: #ffffff;
    padding: 12px 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

body.dark-mode .chat-message-content {
    background: #1e293b;
    border-color: #334155;
}

.chat-message.sent .chat-message-content {
    background: var(--gateway-primary-color, #0b6856);
    color: #ffffff;
    border-color: var(--gateway-primary-color, #0b6856);
}

.chat-message-text {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    color: #111827;
}

.chat-message.sent .chat-message-text {
    color: #ffffff;
}

.chat-message-time {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

.chat-message.sent .chat-message-time {
    color: rgba(255, 255, 255, 0.8);
}

.chat-input-area {
    padding: 20px;
    border-top: 1px solid #e5e7eb;
    background: #ffffff;
}

body.dark-mode .chat-input-area {
    background: #0f172a;
    border-top-color: #1e293b;
}

.chat-input-form {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.chat-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    resize: none;
    min-height: 48px;
    max-height: 120px;
    transition: all 0.2s;
    background: #ffffff;
}

body.dark-mode .chat-input {
    border-color: #1e293b;
    background: #0f172a;
    color: #e2e8f0;
}

.chat-input:focus {
    outline: none;
    border-color: var(--gateway-primary-color, #0b6856);
}

body.dark-mode .chat-input:focus {
    border-color: var(--gateway-primary-color, #0b6856);
}

.chat-send-btn {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: var(--gateway-primary-color, #0b6856);
    color: #ffffff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.chat-send-btn:hover {
    opacity: 0.9;
}

.chat-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    text-align: center;
    color: #6b7280;
}

.chat-empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.chat-empty-state h6 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #111827;
}

.chat-empty-state p {
    font-size: 14px;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-container {
        flex-direction: column;
        height: auto;
        min-height: 500px;
    }
    
    .chat-sidebar {
        width: 100%;
        max-height: 200px;
    }
    
    .chat-message {
        max-width: 85%;
    }
}
</style>

<div class="chat-container">
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h6><i class="fa-solid fa-comments"></i> Conversas</h6>
        </div>
        <div class="chat-search">
            <input type="text" id="chatSearchInput" placeholder="Buscar aluno..." class="form-control">
        </div>
        <div class="chat-list" id="chatList">
            @forelse($alunos as $aluno)
                @php
                    $alunoAvatarUrl = $aluno->avatar ? asset($aluno->avatar) : '';
                    $lastMsg = $lastMessagesByAluno[$aluno->id] ?? null;
                    $hasUnread = $lastMsg && $lastMsg['sender_type'] === 'aluno';
                @endphp
                <div class="chat-item {{ $hasUnread ? 'has-unread' : '' }}" data-aluno-id="{{ $aluno->id }}" data-aluno-avatar="{{ $alunoAvatarUrl }}" data-aluno-name="{{ e($aluno->name) }}" data-last-msg-id="{{ $lastMsg['id'] ?? '' }}" data-last-msg-sender="{{ $lastMsg['sender_type'] ?? '' }}" data-last-ts="{{ $lastMsg['ts'] ?? 0 }}" onclick="selectAluno({{ $aluno->id }}, '{{ addslashes($aluno->name) }}', '{{ addslashes($alunoAvatarUrl) }}')">
                    @if($hasUnread)
                        <span class="chat-item-unread" title="Nova mensagem do aluno"></span>
                    @endif
                    <div class="chat-item-header">
                        <div class="chat-avatar">
                            @if($aluno->avatar)
                                <img src="{{ asset($aluno->avatar) }}" alt="{{ $aluno->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <span style="display: none;">{{ strtoupper(substr($aluno->name, 0, 1)) }}</span>
                            @else
                                {{ strtoupper(substr($aluno->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="chat-item-info">
                            <h6 class="chat-item-name">{{ $aluno->name }}</h6>
                            <p class="chat-item-time">{{ $lastMsg ? $lastMsg['created_at'] : 'Clique para conversar' }}</p>
                        </div>
                    </div>
                    <p class="chat-item-preview">{{ $lastMsg ? ($lastMsg['sender_type'] === 'aluno' ? 'Nova mensagem do aluno' : 'Você enviou') : 'Clique para iniciar conversa' }}</p>
                </div>
            @empty
                <div class="chat-empty-state">
                    <i class="fa-solid fa-comments"></i>
                    <h6>Nenhum aluno encontrado</h6>
                    <p>Quando houver alunos cadastrados, você poderá conversar com eles aqui.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="chat-main">
        <div class="chat-main-header" id="chatMainHeader" style="display: none;">
            <div class="chat-main-header-info">
                <div class="chat-avatar" id="chatMainAvatar"></div>
                <div>
                    <h6 id="chatMainName">Selecione um aluno</h6>
                    <span class="badge bg-success" id="chatMainStatus">Chat</span>
                </div>
            </div>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="chat-empty-state">
                <i class="fa-solid fa-comment-dots"></i>
                <h6>Nenhuma conversa selecionada</h6>
                <p>Selecione um aluno na lista ao lado para iniciar uma conversa.</p>
            </div>
        </div>
        
        <div class="chat-input-area" id="chatInputArea" style="display: none;">
            <form class="chat-input-form" id="chatForm" onsubmit="sendMessage(event)">
                <textarea 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Digite sua mensagem..." 
                    rows="1"
                    onkeydown="handleKeyDown(event)"
                ></textarea>
                <button type="submit" class="chat-send-btn" id="chatSendBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let selectedAlunoId = null;
let selectedAlunoName = null;
let selectedAlunoAvatarUrl = '';
let chatPollTimer = null;
let chatListPollTimer = null;
let chatLastSeenMsgId = 0;
let chatLastConversations = {}; // aluno_id -> last_message_id (para detectar nova mensagem)
const CHAT_PRODUTO_ID = {{ $produto->id }};
const CHAT_PRODUTO_UUID = '{{ $produto->uuid }}';
const CHAT_CSRF = '{{ csrf_token() }}';
const PRODUTOR_INITIAL = '{{ substr(auth()->user()->name ?? "A", 0, 1) }}'.toUpperCase();

function chatMarkSeen(alunoId, maxMsgId) {
    if (alunoId && maxMsgId) localStorage.setItem('chat_seen_' + CHAT_PRODUTO_ID + '_' + alunoId, String(maxMsgId));
    document.querySelectorAll('.chat-item[data-aluno-id="' + alunoId + '"]').forEach(function(el) {
        el.classList.remove('has-unread');
        var dot = el.querySelector('.chat-item-unread');
        if (dot) dot.remove();
    });
}

function chatShowToast(msg) {
    var existing = document.getElementById('chatToastNewMsg');
    if (existing) existing.remove();
    var toast = document.createElement('div');
    toast.id = 'chatToastNewMsg';
    toast.className = 'chat-toast-new-msg';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(function() { if (toast.parentNode) toast.remove(); }, 3000);
}

function chatNotifyNewMessage(alunoName, bodyPreview) {
    chatShowToast('Nova mensagem de ' + alunoName);
    if ('Notification' in window && Notification.permission === 'granted') {
        try {
            new Notification('Nova mensagem no chat', {
                body: bodyPreview ? (alunoName + ': ' + bodyPreview) : alunoName,
                icon: '/favicon.ico'
            });
        } catch (e) {}
    }
}

function chatRequestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

function pollChatList() {
    fetch('/produtos/' + CHAT_PRODUTO_UUID + '/chat/last-messages', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.conversations || !data.conversations.length) return;
        var listEl = document.getElementById('chatList');
        if (!listEl) return;
        var items = listEl.querySelectorAll('.chat-item');
        var byAlunoId = {};
        items.forEach(function(item) {
            byAlunoId[item.dataset.alunoId] = item;
        });
        data.conversations.forEach(function(c) {
            var item = byAlunoId[c.aluno_id];
            if (!item) return;
            item.dataset.lastMsgId = c.last_message_id || '';
            item.dataset.lastMsgSender = c.last_sender_type || '';
            item.dataset.lastTs = c.last_ts || 0;
            var timeEl = item.querySelector('.chat-item-time');
            var previewEl = item.querySelector('.chat-item-preview');
            if (timeEl) timeEl.textContent = c.last_created_at || 'Clique para conversar';
            if (previewEl) previewEl.textContent = c.last_sender_type === 'aluno' ? (c.last_body_preview || 'Nova mensagem do aluno') : (c.last_body_preview || 'Você enviou');
            var hasUnread = c.last_sender_type === 'aluno' && c.last_message_id;
            var seen = parseInt(localStorage.getItem('chat_seen_' + CHAT_PRODUTO_ID + '_' + c.aluno_id) || '0', 10);
            if (hasUnread && c.last_message_id > seen) {
                item.classList.add('has-unread');
                if (!item.querySelector('.chat-item-unread')) {
                    var dot = document.createElement('span');
                    dot.className = 'chat-item-unread';
                    dot.title = 'Nova mensagem do aluno';
                    item.appendChild(dot);
                }
                var prevId = chatLastConversations[c.aluno_id];
                if (prevId !== undefined && c.last_message_id > prevId && selectedAlunoId !== c.aluno_id) {
                    chatNotifyNewMessage(c.aluno_name, c.last_body_preview);
                }
            } else {
                item.classList.remove('has-unread');
                var dot = item.querySelector('.chat-item-unread');
                if (dot) dot.remove();
            }
            chatLastConversations[c.aluno_id] = c.last_message_id || 0;
            listEl.appendChild(item);
        });
    })
    .catch(function() {});
}

function selectAluno(alunoId, alunoName, alunoAvatarUrl) {
    selectedAlunoId = alunoId;
    selectedAlunoName = alunoName || '';
    selectedAlunoAvatarUrl = alunoAvatarUrl || '';
    
    document.querySelectorAll('.chat-item').forEach(item => {
        item.classList.remove('active');
        if (parseInt(item.dataset.alunoId) === alunoId) {
            item.classList.add('active');
        }
    });
    
    const header = document.getElementById('chatMainHeader');
    const avatarEl = document.getElementById('chatMainAvatar');
    const nameEl = document.getElementById('chatMainName');
    
    header.style.display = 'flex';
    if (selectedAlunoAvatarUrl) {
        avatarEl.innerHTML = '<img src="' + selectedAlunoAvatarUrl.replace(/"/g, '&quot;') + '" alt="" onerror="this.outerHTML=\'<span>\' + (selectedAlunoName.charAt(0) || \'A\').toUpperCase() + \'</span>\'">';
    } else {
        avatarEl.innerHTML = '';
        avatarEl.textContent = (selectedAlunoName.charAt(0) || 'A').toUpperCase();
    }
    nameEl.textContent = selectedAlunoName;
    
    document.getElementById('chatInputArea').style.display = 'block';
    
    if (chatPollTimer) clearInterval(chatPollTimer);
    loadChatHistory();
    chatPollTimer = setInterval(loadChatHistory, 3000);
}

function loadChatHistory() {
    if (!selectedAlunoId) return;
    const messagesContainer = document.getElementById('chatMessages');
    fetch('/produtos/' + CHAT_PRODUTO_UUID + '/chat/' + selectedAlunoId + '/messages', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const emptyState = messagesContainer.querySelector('.chat-empty-state');
        if (!data.messages || data.messages.length === 0) {
            if (emptyState) {
                emptyState.innerHTML = '<i class="fa-solid fa-comment-dots"></i><h6>Conversa com ' + escapeHtml(selectedAlunoName) + '</h6><p>Nenhuma mensagem ainda. Envie a primeira!</p>';
                emptyState.style.display = 'flex';
            }
            messagesContainer.querySelectorAll('.chat-message').forEach(el => el.remove());
            chatMarkSeen(selectedAlunoId, 0);
            chatLastSeenMsgId = 0;
            return;
        }
        if (emptyState) emptyState.style.display = 'none';
        messagesContainer.querySelectorAll('.chat-message').forEach(el => el.remove());
        var maxId = 0;
        var lastMsgFromAluno = false;
        data.messages.forEach(function(m) {
            if (m.id > maxId) { maxId = m.id; lastMsgFromAluno = (m.sender_type === 'aluno'); }
            const isSent = m.sender_type === 'user';
            const initial = isSent ? PRODUTOR_INITIAL : (selectedAlunoName.charAt(0) || 'A').toUpperCase();
            const div = document.createElement('div');
            div.className = 'chat-message' + (isSent ? ' sent' : '');
            div.innerHTML = '<div class="chat-message-avatar">' + initial + '</div><div class="chat-message-content"><p class="chat-message-text">' + escapeHtml(m.body) + '</p><p class="chat-message-time">' + escapeHtml(m.created_at) + '</p></div>';
            messagesContainer.appendChild(div);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        chatMarkSeen(selectedAlunoId, maxId);
        if (lastMsgFromAluno && maxId > chatLastSeenMsgId && chatLastSeenMsgId > 0) {
            chatShowToast('Nova mensagem de ' + selectedAlunoName);
        }
        chatLastSeenMsgId = maxId;
    })
    .catch(function() {
        const emptyState = messagesContainer.querySelector('.chat-empty-state');
        if (emptyState) {
            emptyState.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i><h6>Erro ao carregar</h6><p>Tente novamente.</p>';
            emptyState.style.display = 'flex';
        }
    });
}

function sendMessage(event) {
    event.preventDefault();
    if (!selectedAlunoId) {
        alert('Por favor, selecione um aluno primeiro.');
        return;
    }
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    if (!message) return;
    
    input.value = '';
    input.style.height = 'auto';
    
    fetch('/produtos/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CHAT_CSRF,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            produto_id: CHAT_PRODUTO_ID,
            aluno_id: selectedAlunoId,
            body: message
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.message) {
            loadChatHistory();
        }
    })
    .catch(() => alert('Erro ao enviar. Tente novamente.'));
}

function addMessage(text, isSent) {
    const messagesContainer = document.getElementById('chatMessages');
    const emptyState = messagesContainer.querySelector('.chat-empty-state');
    if (emptyState) emptyState.remove();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'chat-message' + (isSent ? ' sent' : '');
    const now = new Date();
    const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    const initial = isSent ? PRODUTOR_INITIAL : (selectedAlunoName.charAt(0) || 'A').toUpperCase();
    messageDiv.innerHTML = '<div class="chat-message-avatar">' + initial + '</div><div class="chat-message-content"><p class="chat-message-text">' + escapeHtml(text) + '</p><p class="chat-message-time">' + time + '</p></div>';
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function handleKeyDown(event) {
    const textarea = event.target;
    
    // Enter sem Shift envia mensagem
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
    
    // Auto-resize textarea
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Busca de alunos
document.getElementById('chatSearchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.chat-item');
    
    items.forEach(item => {
        const name = item.querySelector('.chat-item-name').textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

// Inicializar estado das conversas (evitar notificação no primeiro poll)
document.querySelectorAll('.chat-item').forEach(function(item) {
    var id = item.dataset.lastMsgId;
    if (id && item.dataset.alunoId) chatLastConversations[item.dataset.alunoId] = parseInt(id, 10) || 0;
});

// Poll da lista: reordenar por última mensagem e notificações
pollChatList();
if (chatListPollTimer) clearInterval(chatListPollTimer);
chatListPollTimer = setInterval(pollChatList, 4000);

// Pedir permissão de notificação ao interagir com o chat (ex.: ao abrir a aba Chat)
document.querySelector('.chat-container')?.addEventListener('click', function requestOnce() {
    chatRequestNotificationPermission();
    document.querySelector('.chat-container').removeEventListener('click', requestOnce);
}, { once: true });
</script>
