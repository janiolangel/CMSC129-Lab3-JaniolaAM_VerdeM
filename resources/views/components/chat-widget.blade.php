<!-- ===== CHAT WIDGET ===== -->
<div id="chat-anchor">
    <div id="unread-badge">1</div>
    <button id="chat-toggle" title="Open AI Assistant">🤖</button>
    <div id="chat-window" class="hidden">
        <div id="chat-header">
            <div id="chat-header-left">
                <div class="chat-status-dot"></div>
                <div>
                    <div id="chat-title">heyToday! Assistant</div>
                    <div id="chat-subtitle">Always here to help</div>
                </div>
            </div>
            <div id="chat-header-actions">
                <button class="hbtn" id="chat-expand" title="Expand">⛶</button>
                <button class="hbtn" id="chat-close" title="Close">✕</button>
            </div>
        </div>
        <div id="chat-messages">
            <div class="chat-msg ai">Hi! I'm your task assistant. Ask me anything about your tasks! 👋</div>
        </div>
        <div id="chat-suggestions">
            <button class="chip" onclick="fillInput('What tasks are due today?')">📅 Due today</button>
            <button class="chip" onclick="fillInput('Show high priority tasks')">🔴 High priority</button>
            <button class="chip" onclick="fillInput('What did I complete?')">✅ Completed</button>
        </div>
        <div id="chat-input-area">
            <textarea id="chat-input" rows="1" placeholder="Ask about your tasks..."></textarea>
            <button id="chat-send">Send</button>
        </div>
    </div>
</div>