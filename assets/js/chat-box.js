/**
 * Chat Box (hỗ trợ khách hàng) — sieuthicode
 * Polling AJAX 4s, tương thích hosting cPanel (không cần WebSocket).
 * Render tin nhắn bằng textContent để chống stored XSS.
 */
(function () {
    'use strict';

    var listEl = document.getElementById('chat-list');
    if (!listEl) {
        return; // Không phải trang chat
    }

    var messagesEl = document.getElementById('chat-messages');
    var loadingEl = document.getElementById('chat-loading');
    var emptyEl = document.getElementById('chat-empty');
    var errorEl = document.getElementById('chat-error');
    var retryEl = document.getElementById('chat-retry');
    var composerEl = document.getElementById('chat-composer');
    var inputEl = document.getElementById('chat-input');
    var sendBtn = document.getElementById('chat-send-btn');
    var attachBtn = document.getElementById('chat-attach-btn');
    var attachInput = document.getElementById('chat-attachment');
    var attachPreview = document.getElementById('chat-attachment-preview');
    var attachThumb = document.getElementById('chat-attachment-thumb');
    var attachRemove = document.getElementById('chat-attachment-remove');
    var closedEl = document.getElementById('chat-closed');
    var reopenBtn = document.getElementById('chat-reopen');
    var statusText = document.getElementById('chat-status-text');
    var backBtn = document.getElementById('chat-back');
    var wrapEl = document.querySelector('.chat-wrap');

    var POLL_INTERVAL = 4000;
    var lastId = 0;
    var sending = false;
    var pollTimer = null;
    var csrfToken = composerEl.querySelector('input[name="csrf_token"]').value;

    function setState(state) {
        loadingEl.style.display = state === 'loading' ? '' : 'none';
        emptyEl.style.display = state === 'empty' ? '' : 'none';
        errorEl.style.display = state === 'error' ? '' : 'none';
    }

    function scrollToBottom(force) {
        var nearBottom = messagesEl.scrollHeight - messagesEl.scrollTop - messagesEl.clientHeight < 120;
        if (force || nearBottom) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }

    function buildMessageNode(msg) {
        var li = document.createElement('li');
        li.className = 'chat-item ' + (msg.sender_role === 'admin' ? 'is-admin' : 'is-user');
        li.dataset.id = msg.id;

        if (msg.sender_role === 'admin') {
            var avatar = document.createElement('img');
            avatar.className = 'chat-avatar';
            avatar.src = '/assets/images/anhdaidien.svg';
            avatar.alt = 'CSKH';
            li.appendChild(avatar);
        }

        var wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap';

        if (msg.sender_role === 'admin') {
            var sender = document.createElement('div');
            sender.className = 'chat-sender';
            sender.textContent = 'Hỗ trợ viên';
            wrap.appendChild(sender);
        }

        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble';

        if (msg.attachment) {
            var img = document.createElement('img');
            img.className = 'chat-attachment-img';
            img.src = msg.attachment;
            img.alt = 'Ảnh đính kèm';
            img.loading = 'lazy';
            img.addEventListener('click', function () {
                window.open(msg.attachment, '_blank', 'noopener');
            });
            bubble.appendChild(img);
        }
        if (msg.message) {
            var textNode = document.createElement('span');
            textNode.textContent = msg.message; // textContent => chống XSS
            bubble.appendChild(textNode);
        }

        var time = document.createElement('div');
        time.className = 'chat-time';
        time.textContent = msg.time_label + ' ' + msg.date_label;

        wrap.appendChild(bubble);
        wrap.appendChild(time);
        li.appendChild(wrap);
        return li;
    }

    function appendMessages(messages, forceScroll) {
        var added = false;
        messages.forEach(function (msg) {
            if (msg.id > lastId) {
                lastId = msg.id;
            }
            if (listEl.querySelector('[data-id="' + msg.id + '"]')) {
                return; // tránh trùng khi 2 tab/poll cùng chạy
            }
            listEl.appendChild(buildMessageNode(msg));
            added = true;
        });
        if (added) {
            setState('ok');
            scrollToBottom(forceScroll);
        }
    }

    function applyConversationStatus(status) {
        var closed = status === 'closed';
        closedEl.style.display = closed ? '' : 'none';
        composerEl.style.display = closed ? 'none' : '';
        if (statusText) {
            statusText.textContent = closed ? 'Hội thoại đã đóng' : 'Sẵn sàng hỗ trợ bạn';
            statusText.style.color = closed ? '#e4a11b' : '#31a24c';
        }
    }

    function apiGet(url) {
        return fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.json(); });
    }

    function apiPost(url, formData) {
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        }).then(function (res) { return res.json(); });
    }

    function loadInitial() {
        setState('loading');
        apiGet('/model/chat/messages')
            .then(function (json) {
                if (json.status !== 'success') {
                    setState('error');
                    return;
                }
                var messages = json.data.messages || [];
                if (messages.length === 0) {
                    setState('empty');
                } else {
                    setState('ok');
                    appendMessages(messages, true);
                }
                var conv = json.data.conversation;
                applyConversationStatus(conv && conv.status ? conv.status : 'open');
                markRead();
            })
            .catch(function () {
                setState('error');
            });
    }

    function poll() {
        if (lastId === 0) {
            return;
        }
        apiGet('/model/chat/messages?after_id=' + lastId)
            .then(function (json) {
                if (json.status !== 'success') {
                    return;
                }
                var messages = json.data.messages || [];
                if (messages.length > 0) {
                    appendMessages(messages, false);
                    markRead();
                }
                applyConversationStatus(json.data.conversation.status);
            })
            .catch(function () { /* giữ nguyên, thử lại ở chu kỳ sau */ });
    }

    function markRead() {
        var fd = new FormData();
        fd.append('csrf_token', csrfToken);
        apiPost('/model/chat/read', fd).then(function () {
            document.querySelectorAll('.chat-badge-unread').forEach(function (badge) {
                badge.style.display = 'none';
                badge.textContent = '0';
            });
        }).catch(function () {});
    }

    function autoGrowInput() {
        inputEl.style.height = 'auto';
        inputEl.style.height = Math.min(inputEl.scrollHeight, 110) + 'px';
    }

    function clearAttachment() {
        attachInput.value = '';
        attachPreview.style.display = 'none';
        attachThumb.src = '';
    }

    function doSend() {
        var text = inputEl.value.trim();
        var hasFile = attachInput.files && attachInput.files.length > 0;
        if ((!text && !hasFile) || sending) {
            return;
        }
        sending = true;
        sendBtn.disabled = true;

        var fd = new FormData();
        fd.append('csrf_token', csrfToken);
        fd.append('message', inputEl.value);
        if (hasFile) {
            fd.append('attachment', attachInput.files[0]);
        }

        apiPost('/model/chat/send', fd)
            .then(function (json) {
                if (json.status === 'success' && json.data && json.data.message) {
                    if (emptyEl.style.display !== 'none') {
                        setState('ok');
                    }
                    appendMessages([json.data.message], true);
                    inputEl.value = '';
                    autoGrowInput();
                    clearAttachment();
                    applyConversationStatus('open');
                } else {
                    if (json.data && json.data.conversation_status) {
                        applyConversationStatus(json.data.conversation_status);
                    }
                    alert(json.msg || 'Không gửi được tin nhắn');
                }
            })
            .catch(function () {
                alert('Lỗi kết nối, vui lòng thử lại');
            })
            .finally(function () {
                sending = false;
                sendBtn.disabled = false;
                inputEl.focus();
            });
    }

    // ---- Sự kiện ----
    composerEl.addEventListener('submit', function (e) {
        e.preventDefault();
        doSend();
    });

    inputEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            doSend();
        }
    });
    inputEl.addEventListener('input', autoGrowInput);

    attachBtn.addEventListener('click', function () {
        attachInput.click();
    });
    attachInput.addEventListener('change', function () {
        if (attachInput.files && attachInput.files.length > 0) {
            var file = attachInput.files[0];
            if (file.size > 5 * 1024 * 1024) {
                alert('Ảnh đính kèm tối đa 5MB');
                clearAttachment();
                return;
            }
            attachThumb.src = URL.createObjectURL(file);
            attachPreview.style.display = '';
        }
    });
    attachRemove.addEventListener('click', clearAttachment);

    if (reopenBtn) {
        reopenBtn.addEventListener('click', function () {
            var fd = new FormData();
            fd.append('csrf_token', csrfToken);
            apiPost('/model/chat/reopen', fd)
                .then(function (json) {
                    if (json.status === 'success') {
                        applyConversationStatus('open');
                        poll();
                    } else {
                        alert(json.msg || 'Không thể mở lại hội thoại');
                    }
                })
                .catch(function () {
                    alert('Lỗi kết nối, vui lòng thử lại');
                });
        });
    }

    if (retryEl) {
        retryEl.addEventListener('click', loadInitial);
    }

    // Mobile: nút back chuyển sang panel thông tin hỗ trợ
    if (backBtn && wrapEl) {
        backBtn.addEventListener('click', function () {
            wrapEl.classList.toggle('show-info');
        });
    }

    // ---- Khởi động ----
    loadInitial();
    pollTimer = setInterval(poll, POLL_INTERVAL);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            clearInterval(pollTimer);
        } else {
            poll();
            pollTimer = setInterval(poll, POLL_INTERVAL);
        }
    });
})();
