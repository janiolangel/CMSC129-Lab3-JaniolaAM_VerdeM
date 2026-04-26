const anchor    = document.getElementById('chat-anchor');
const toggle    = document.getElementById('chat-toggle');
const chatWin   = document.getElementById('chat-window');
const closeBtn  = document.getElementById('chat-close');
const expandBtn = document.getElementById('chat-expand');
const input     = document.getElementById('chat-input');
const sendBtn   = document.getElementById('chat-send');
const messages  = document.getElementById('chat-messages');
const badge     = document.getElementById('unread-badge');

let isOpen = false;
let isExpanded = false;
let conversationHistory = [];

// --- Open / Close ---
function openChat() {
    isOpen = true;
    chatWin.classList.remove('hidden');
    setTimeout(() => chatWin.classList.add('visible'), 10);
    badge.classList.remove('show');
    input.focus();
}

function closeChat() {
    isOpen = false;
    chatWin.classList.remove('visible');
    setTimeout(() => chatWin.classList.add('hidden'), 250);
}

closeBtn.addEventListener('click', closeChat);

// --- Expand / Collapse ---
expandBtn.addEventListener('click', () => {
    isExpanded = !isExpanded;
    if (isExpanded) {
        chatWin.style.width  = '480px';
        chatWin.style.height = '640px';
        expandBtn.textContent = '⊠';
    } else {
        chatWin.style.width  = '360px';
        chatWin.style.height = '500px';
        expandBtn.textContent = '⛶';
    }
});

// --- Drag Logic ---
let dragging   = false;
let wasDragged = false;
let startX, startY, origRight, origBottom;

toggle.addEventListener('mousedown', (e) => {
    dragging   = true;
    wasDragged = false;
    startX     = e.clientX;
    startY     = e.clientY;
    const rect = anchor.getBoundingClientRect();
    origRight  = window.innerWidth  - rect.right;
    origBottom = window.innerHeight - rect.bottom;
    e.preventDefault();
});

document.addEventListener('mousemove', (e) => {
    if (!dragging) return;
    const dx = e.clientX - startX;
    const dy = e.clientY - startY;
    if (Math.abs(dx) > 8 || Math.abs(dy) > 8) wasDragged = true;
    anchor.style.right  = Math.max(8, origRight  - dx) + 'px';
    anchor.style.bottom = Math.max(8, origBottom - dy) + 'px';
    anchor.style.left   = 'auto';
    anchor.style.top    = 'auto';
});

document.addEventListener('mouseup', () => {
    if (dragging && !wasDragged) {
        isOpen ? closeChat() : openChat();
    }
    dragging   = false;
    wasDragged = false;
});

// --- Textarea Auto-resize ---
input.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 80) + 'px';
});

// --- Fill Input from Suggestion Chips ---
function fillInput(text) {
    input.value = text;
    input.focus();
}

// --- Add Message to Chat ---
function addMessage(text, role) {
    const div = document.createElement('div');
    div.className = `chat-msg ${role}`;
    if (role === 'ai loading') {
        div.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
    } else {
        div.textContent = text;
    }
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return div;
}

// --- Send Message to Backend ---
async function sendMessage() {
    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

    addMessage(text, 'user');
    conversationHistory.push({ role: 'user', content: text });
    const loading = addMessage('', 'ai loading');

    try {
        const listId = new URLSearchParams(window.location.search).get('list_id');
        const res = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: text,
                list_id: listId,
                history: conversationHistory.slice(-10)
            })
        });

        const data = await res.json();
        loading.remove();
        const reply = data.reply || 'Sorry, I could not process that.';
        addMessage(reply, 'ai');
        conversationHistory.push({ role: 'assistant', content: reply });

        if (!isOpen) { badge.classList.add('show'); }
    } catch (err) {
        loading.remove();
        addMessage('Error: Could not connect to assistant.', 'ai');
    }

    sendBtn.disabled = false;
    input.focus();
}

// --- Event Listeners for Send ---
sendBtn.addEventListener('click', sendMessage);
input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});