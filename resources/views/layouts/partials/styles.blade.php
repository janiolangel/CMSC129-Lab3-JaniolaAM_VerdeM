<style>
    /* ─── Base ─────────────────────────────────────────────────── */
    * { font-family: 'DM Sans', sans-serif; }
    .logo-font { font-family: 'Space Mono', monospace; }
    body { background-color: #0f1923; color: #e2e8f0; }

    /* ─── Layout ────────────────────────────────────────────────── */
    .sidebar { background-color: #141f2e; border-right: 1px solid #1e2d3d; }
    .card    { background-color: #141f2e; border: 1px solid #1e2d3d; border-radius: 12px; }

    /* ─── Sidebar List Items ────────────────────────────────────── */
    .list-item        { border-radius: 8px; transition: all 0.2s; }
    .list-item:hover  { background-color: #1e2d3d; }
    .list-item.active { background-color: #1e3a2f; border-left: 3px solid #4ade80; }

    /* ─── Circle Progress ───────────────────────────────────────── */
    .circle-progress   { width: 70px; height: 70px; border-radius: 50%; border: 3px solid #1e2d3d; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; position: relative; }
    .circle-completed  { border-color: #4ade80; color: #4ade80; }
    .circle-inprogress { border-color: #60a5fa; color: #60a5fa; }
    .circle-notstarted { border-color: #94a3b8; color: #94a3b8; }

    /* ─── Badges ────────────────────────────────────────────────── */
    .badge-green  { background-color: #166534; color: #4ade80; border-radius: 20px; padding: 2px 10px; font-size: 12px; }
    .badge-red    { background-color: #7f1d1d; color: #f87171; border-radius: 20px; padding: 2px 10px; font-size: 12px; }
    .badge-yellow { background-color: #eab308; color: #000;    border-radius: 20px; padding: 2px 10px; font-size: 12px; font-weight: 700; }

    /* ─── Buttons ───────────────────────────────────────────────── */
    .btn-yellow       { background-color: #eab308; color: #000; border-radius: 8px; padding: 6px 16px; font-weight: 600; font-size: 13px; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-yellow:hover { background-color: #ca8a04; }

    .delete-btn        { color: #f87171; background: none; border: none; cursor: pointer; font-size: 13px; padding: 3px 8px; border-radius: 4px; transition: all 0.2s; }
    .delete-btn:hover  { background-color: #7f1d1d33; }
    .restore-btn       { color: #4ade80; background: none; border: none; cursor: pointer; font-size: 13px; padding: 3px 8px; border-radius: 4px; transition: all 0.2s; }
    .restore-btn:hover { background-color: #166534; }

    .add-list-btn       { background-color: #eab308; color: #000; border: none; border-radius: 8px; padding: 8px 12px; font-weight: 700; cursor: pointer; font-size: 16px; }
    .add-list-btn:hover { background-color: #ca8a04; }

    /* ─── Form Inputs ───────────────────────────────────────────── */
    select, input[type="text"], input[type="date"] {
        background-color: #1e2d3d;
        border: 1px solid #2d3d50;
        color: #e2e8f0;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 13px;
    }
    select:focus, input:focus { outline: none; border-color: #4ade80; }

    .new-list-input       { background-color: #1e2d3d; border: 1px solid #2d3d50; color: #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 13px; width: 100%; }
    .new-list-input:focus { outline: none; border-color: #4ade80; }

    /* ─── Task Cards ────────────────────────────────────────────── */
    .task-card       { background-color: #1a2637; border: 1px solid #1e2d3d; border-radius: 8px; padding: 12px 16px; margin-bottom: 8px; transition: all 0.2s; }
    .task-card:hover { border-color: #2d3d50; transform: translateY(-1px); }

    .priority-high   { border-left: 3px solid #f87171; }
    .priority-medium { border-left: 3px solid #eab308; }
    .priority-low    { border-left: 3px solid #4ade80; }

    .status-badge { font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 500; }
    .status-0     { background-color: #1e2d3d; color: #94a3b8; }
    .status-1     { background-color: #1e3a5f; color: #60a5fa; }
    .status-2     { background-color: #1e3a2f; color: #4ade80; }

    /* ─── Misc UI ───────────────────────────────────────────────── */
    .summary-dot            { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .active-tasks-indicator { background-color: #166534; color: #4ade80; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: 600; }
    .alert-success          { background-color: #1e3a2f; border: 1px solid #166534; color: #4ade80; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; font-size: 14px; }

    .empty-state      { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; color: #475569; }
    .empty-state span { font-size: 36px; margin-bottom: 8px; }

    /* ─── Scrollbar ─────────────────────────────────────────────── */
    ::-webkit-scrollbar       { width: 4px; }
    ::-webkit-scrollbar-track { background: #0f1923; }
    ::-webkit-scrollbar-thumb { background: #1e2d3d; border-radius: 2px; }

    /* ─── Chat Widget ───────────────────────────────────────────── */
    #chat-anchor {
        position: fixed;
        bottom: 28px; right: 28px;
        z-index: 1000;
        width: 60px; height: 60px;
    }

    #chat-toggle {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eab308 0%, #f59e0b 100%);
        color: #000;
        border: 3px solid rgba(253,230,138,0.6);
        cursor: grab;
        font-size: 26px;
        box-shadow: 0 6px 28px rgba(234,179,8,0.5), 0 2px 10px rgba(0,0,0,0.5);
        display: flex; align-items: center; justify-content: center;
        transition: box-shadow 0.2s, transform 0.15s;
        user-select: none;
        position: relative; z-index: 2;
    }
    #chat-toggle:hover  { box-shadow: 0 8px 36px rgba(234,179,8,0.7), 0 4px 12px rgba(0,0,0,0.4); transform: scale(1.07); }
    #chat-toggle:active { cursor: grabbing; transform: scale(0.97); }
    #chat-toggle::before {
        content: '';
        position: absolute; inset: -6px;
        border-radius: 50%;
        border: 2px solid rgba(234,179,8,0.3);
        animation: pulseRing 2.5s ease-out infinite;
    }
    @keyframes pulseRing {
        0%        { transform: scale(1);    opacity: 0.7; }
        70%, 100% { transform: scale(1.35); opacity: 0;   }
    }

    #chat-window {
        position: absolute;
        bottom: calc(100% + 14px); right: 0;
        width: 360px; height: 500px;
        background: #141f2e;
        border: 1px solid #253447;
        border-radius: 18px;
        display: flex; flex-direction: column;
        box-shadow: 0 16px 50px rgba(0,0,0,0.7), 0 4px 16px rgba(0,0,0,0.3);
        overflow: hidden;
        transform-origin: bottom right;
        transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease;
        z-index: 1;
        resize: both; min-width: 280px; min-height: 360px;
    }
    #chat-window.hidden  { transform: scale(0.85) translateY(10px); opacity: 0; pointer-events: none; }
    #chat-window.visible { transform: scale(1) translateY(0); opacity: 1; }

    #chat-header {
        background: linear-gradient(135deg, #1a2d40 0%, #1a3530 100%);
        padding: 13px 16px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid #253447;
        cursor: move; flex-shrink: 0; user-select: none;
    }
    #chat-header-left    { display: flex; align-items: center; gap: 9px; }
    #chat-header-actions { display: flex; gap: 4px; }
    #chat-title    { font-size: 13px; font-weight: 600; color: #e2e8f0; letter-spacing: 0.2px; }
    #chat-subtitle { font-size: 10px; color: #64748b; margin-top: 1px; }

    .chat-status-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 8px #4ade80;
        animation: blink 2s ease-in-out infinite;
    }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    .hbtn {
        background: none; border: none; cursor: pointer;
        color: #64748b; font-size: 13px;
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }
    .hbtn:hover { background: #253447; color: #e2e8f0; }

    #chat-messages {
        flex: 1; overflow-y: auto;
        padding: 14px 12px;
        display: flex; flex-direction: column; gap: 10px;
        scroll-behavior: smooth;
    }
    #chat-messages::-webkit-scrollbar       { width: 3px; }
    #chat-messages::-webkit-scrollbar-thumb { background: #253447; border-radius: 2px; }

    .chat-msg {
        max-width: 82%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13px; line-height: 1.55;
        word-break: break-word;
        animation: msgIn 0.2s ease;
    }
    @keyframes msgIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0);   }
    }
    .chat-msg.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #eab308, #d97706);
        color: #000; font-weight: 500;
        border-bottom-right-radius: 4px;
    }
    .chat-msg.ai {
        align-self: flex-start;
        background: #1e2d3d;
        color: #e2e8f0; border: 1px solid #253447;
        border-bottom-left-radius: 4px;
    }

    .typing-dots span {
        display: inline-block;
        width: 5px; height: 5px; border-radius: 50%;
        background: #64748b; margin: 0 2px;
        animation: dot 1.2s ease-in-out infinite;
    }
    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes dot {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
        40%            { transform: scale(1.1); opacity: 1;   }
    }

    #chat-suggestions { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 12px 8px; flex-shrink: 0; }
    .chip       { background: #1e2d3d; border: 1px solid #253447; color: #94a3b8; font-size: 11px; padding: 4px 10px; border-radius: 20px; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .chip:hover { background: #253447; color: #e2e8f0; border-color: #eab308; }

    #chat-input-area {
        padding: 10px 12px 12px;
        border-top: 1px solid #1e2d3d;
        display: flex; gap: 8px; flex-shrink: 0;
        background: #141f2e;
    }
    #chat-input {
        flex: 1;
        background: #1e2d3d; border: 1px solid #253447;
        color: #e2e8f0; border-radius: 10px;
        padding: 9px 13px; font-size: 13px;
        outline: none; resize: none; max-height: 80px;
        transition: border-color 0.15s; font-family: inherit;
    }
    #chat-input:focus { border-color: #eab308; }

    #chat-send {
        background: linear-gradient(135deg, #eab308, #ca8a04);
        color: #000; border: none; border-radius: 10px;
        padding: 9px 15px; font-size: 13px; font-weight: 700;
        cursor: pointer; align-self: flex-end; font-family: inherit;
        transition: opacity 0.15s, transform 0.1s;
    }
    #chat-send:hover    { opacity: 0.88; }
    #chat-send:active   { transform: scale(0.95); }
    #chat-send:disabled { opacity: 0.35; cursor: not-allowed; }

    #unread-badge {
        position: absolute; top: -2px; right: -2px;
        width: 18px; height: 18px;
        background: #ef4444; border-radius: 50%;
        font-size: 10px; font-weight: 700; color: #fff;
        display: none; align-items: center; justify-content: center;
        border: 2px solid #0f1923; z-index: 3;
    }
    #unread-badge.show { display: flex; }
</style>