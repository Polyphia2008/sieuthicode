/**
 * Admin Chat Box (hòm thư hỗ trợ) — sieuthicode
 * Polling AJAX, dùng textContent để chống stored XSS.
 */
(function () {
    'use strict';

    var listPanel = document.getElementById('admin-chat-list-panel');
    if (!listPanel) {
        return; // Không phải trang admin chat
    }

    var wrapEl = document.querySelector('.admin-chat-wrap');
    var convListEl = document.getElementById('admin-chat-conversations');
    var searchEl = document.getElementById('admin-chat-search');
    var filterStatusEl = document.getElementById('admin-chat-filter-status');
    var paginationEl = document.getElementById('admin-chat-pagination');
    var titleEl = document.getElementById('admin-chat-title');
    var statusActionsEl = document.getElementById('admin-chat-status-actions');
    var closeBtn = document.getElementById('admin-chat-close-btn');
    var openBtn = document.getElementById('admin-chat-open-btn');
    var backBtn = document.getElementById('admin-chat-back');
    var messagesEl = document.getElementById('admin-chat-messages');
    var placeholderEl = document.getElementById('admin-chat-placeholder');
    var msgListEl = document.getElementById('admin-chat-list');
    var composerEl = document.getElementById('admin-chat-composer');
    var inputEl = document.getElementById('admin-chat-input');
    var sendBtn = document.getElementById('admin-chat-send-btn');
    var attachBtn = document.getElementById('admin-chat-attach-btn');
    var attachInput = document.getElementById('admin-chat-attachment');
    var attachPreview = document.getElementById('admin-chat-attachment-preview');
    var attachThumb = document.getElementById('admin-chat-attachment-thumb');
    var attachRemove = document.getElementById('admin-chat-attachment-remove');
    var totalUnreadEl = document.getElementById('admin-chat-total-unread');

    var csrfToken = composerEl.querySelector('input[name="csrf_token"]').value;
    var POLL_INTERVAL = 4000;
    var LIST_REFRESH = 8000;

    var state = {
        page: 1,
        q: '',
        status: '',
        activeId: 0,
        activeStatus: 'open',
        lastId: 0,
        sending: false
    };

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

    function setTotalUnread(total) {
        totalUnreadEl.textContent = total > 99 ? '99+' : String(total);
        totalUnreadEl.style.display = total > 0 ? '' : 'none';
    }

    function renderConversations(items) {
        convListEl.innerHTML = '';
        if (!items.length) {
            var empty = document.createElement('div');
            empty.className = 'text-muted text-center py-4';
            empty.textContent = 'Không có hội thoại nào.';
            convListEl.appendChild(empty);
            return;
        }
        items.forEach(function (item) {
            var row = document.createElement('div');
            row.className = 'admin-chat-conv-item' + (item.id === state.activeId ? ' active' : '');
            row.dataset.id = item.id;

            var avatar = document.createElement('div');
            avatar.className = 'admin-chat-conv-avatar';
            avatar.textContent = (item.username || '?').charAt(0);

            var main = document.createElement('div');
            main.className = 'admin-chat-conv-main';

            var top = document.createElement('div');
            top.className = 'admin-chat-conv-top';
            var name = document.createElement('div');
            name.className = 'admin-chat-conv-name';
            name.textContent = item.username;
            var time = document.createElement('div');
            time.className = 'admin-chat-conv-time';
            time.textContent = item.last_message_label || '';
            top.appendChild(name);
            top.appendChild(time);

            var preview = document.createElement('div');
            preview.className = 'admin-chat-conv-preview';
            var prefix = item.last_sender_role === 'admin' ? 'Bạn: ' : '';
            preview.textContent = item.last_message ? prefix + item.last_message : 'Chưa có tin nhắn';

            var meta = document.createElement('div');
            meta.className = 'admin-chat-conv-meta';
            var status = document.createElement('span');
            status.className = 'admin-chat-conv-status ' + item.status;
            status.textContent = item.status === 'open' ? 'Đang mở' : 'Đã đóng';
            meta.appendChild(status);
            if (item.unread_admin > 0) {
                var unread = document.createElement('span');
                unread.className = 'admin-chat-conv-unread';
                unread.textContent = item.unread_admin;
                meta.appendChild(unread);
            }

            main.appendChild(top);
            main.appendChild(preview);
            main.appendChild(meta);
            row.appendChild(avatar);
            row.appendChild(main);

            row.addEventListener('click', function () {
                openConversation(item.id, item.username, item.status);
            });
            convListEl.appendChild(row);
        });
    }

    function renderPagination(pg) {
        paginationEl.innerHTML = '';
        if (!pg || pg.total_pages <= 1) {
            return;
        }
        var ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';
        for (var p = 1; p <= pg.total_pages; p++) {
            (function (pageNum) {
                var li = document.createElement('li');
                li.className = 'page-item' + (pageNum === pg.page ? ' active' : '');
                var a = document.createElement('a');
                a.className = 'page-link admin-chat-pagination-btn';
                a.href = 'javascript:void(0)';
                a.textContent = pageNum;
                a.addEventListener('click', function () {
                    state.page = pageNum;
                    loadConversations();
                });
                li.appendChild(a);
                ul.appendChild(li);
            })(p);
        }
        paginationEl.appendChild(ul);
    }

    function loadConversations() {
        var url = '/model/admin/chat/conversations?page=' + state.page
            + '&status=' + encodeURIComponent(state.status)
            + '&q=' + encodeURIComponent(state.q);
        apiGet(url).then(function (json) {
            if (json.status !== 'success') {
                return;
            }
            renderConversations(json.data.conversations || []);
            renderPagination(json.data.pagination);
            setTotalUnread(json.data.total_unread || 0);
        }).catch(function () {});
    }

    function buildMessageNode(msg) {
        var li = document.createElement('li');
        li.className = 'chat-item ' + (msg.sender_role === 'admin' ? 'is-admin' : 'is-user');
        li.dataset.id = msg.id;

        if (msg.sender_role === 'user') {
            var avatar = document.createElement('div');
            avatar.className = 'admin-chat-conv-avatar chat-avatar';
            avatar.style.width = '30px';
            avatar.style.height = '30px';
            avatar.style.flex = '0 0 30px';
            avatar.textContent = '?';
            li.appendChild(avatar);
        }

        var wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap';

        var sender = document.createElement('div');
        sender.className = 'chat-sender';
        sender.textContent = msg.sender_role === 'admin' ? 'Bạn (Admin)' : 'Khách hàng';
        wrap.appendChild(sender);

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
            textNode.textContent = msg.message;
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
            if (msg.id > state.lastId) {
                state.lastId = msg.id;
            }
            if (msgListEl.querySelector('[data-id="' + msg.id + '"]')) {
                return;
            }
            msgListEl.appendChild(buildMessageNode(msg));
            added = true;
        });
        if (added) {
            placeholderEl.style.display = 'none';
            scrollToBottom(forceScroll);
        }
    }

    function scrollToBottom(force) {
        var nearBottom = messagesEl.scrollHeight - messagesEl.scrollTop - messagesEl.clientHeight < 120;
        if (force || nearBottom) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }

    function applyStatus(status) {
        state.activeStatus = status;
        closeBtn.style.display = status === 'open' ? '' : 'none';
        openBtn.style.display = status === 'closed' ? '' : 'none';
    }

    function openConversation(id, username, status) {
        state.activeId = id;
        state.lastId = 0;
        msgListEl.innerHTML = '';
        titleEl.textContent = 'Hội thoại: ' + username;
        statusActionsEl.style.display = '';
        composerEl.style.display = '';
        applyStatus(status);
        if (wrapEl) {
            wrapEl.classList.add('show-msg');
        }
        loadMessages(true);
        markRead();
    }

    function loadMessages(force) {
        if (!state.activeId) {
            return;
        }
        var url = '/model/admin/chat/messages?conversation_id=' + state.activeId
            + (state.lastId > 0 ? '&after_id=' + state.lastId : '');
        apiGet(url).then(function (json) {
            if (json.status !== 'success') {
                return;
            }
            appendMessages(json.data.messages || [], force);
            applyStatus(json.data.conversation.status);
        }).catch(function () {});
    }

    function markRead() {
        if (!state.activeId) {
            return;
        }
        var fd = new FormData();
        fd.append('csrf_token', csrfToken);
        fd.append('conversation_id', state.activeId);
        apiPost('/model/admin/chat/read', fd).then(function () {
            loadConversations();
        }).catch(function () {});
    }

    function setStatus(newStatus) {
        if (!state.activeId) {
            return;
        }
        var fd = new FormData();
        fd.append('csrf_token', csrfToken);
        fd.append('conversation_id', state.activeId);
        fd.append('status', newStatus);
        apiPost('/model/admin/chat/status', fd).then(function (json) {
            if (json.status === 'success') {
                applyStatus(json.data.conversation_status);
                state.lastId = 0;
                msgListEl.innerHTML = '';
                loadMessages(true);
                loadConversations();
            } else {
                alert(json.msg || 'Không thể cập nhật trạng thái');
            }
        }).catch(function () {
            alert('Lỗi kết nối, vui lòng thử lại');
        });
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
        if ((!text && !hasFile) || state.sending || !state.activeId) {
            return;
        }
        state.sending = true;
        sendBtn.disabled = true;

        var fd = new FormData();
        fd.append('csrf_token', csrfToken);
        fd.append('conversation_id', state.activeId);
        fd.append('message', inputEl.value);
        if (hasFile) {
            fd.append('attachment', attachInput.files[0]);
        }

        apiPost('/model/admin/chat/send', fd)
            .then(function (json) {
                if (json.status === 'success' && json.data && json.data.message) {
                    appendMessages([json.data.message], true);
                    inputEl.value = '';
                    autoGrowInput();
                    clearAttachment();
                    loadConversations();
                } else {
                    if (json.data && json.data.conversation_status) {
                        applyStatus(json.data.conversation_status);
                    }
                    alert(json.msg || 'Không gửi được phản hồi');
                }
            })
            .catch(function () {
                alert('Lỗi kết nối, vui lòng thử lại');
            })
            .finally(function () {
                state.sending = false;
                sendBtn.disabled = false;
                inputEl.focus();
            });
    }

    // ---- Sự kiện ----
    var searchTimer = null;
    searchEl.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            state.q = searchEl.value.trim();
            state.page = 1;
            loadConversations();
        }, 350);
    });
    filterStatusEl.addEventListener('change', function () {
        state.status = filterStatusEl.value;
        state.page = 1;
        loadConversations();
    });

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

    closeBtn.addEventListener('click', function () {
        setStatus('closed');
    });
    openBtn.addEventListener('click', function () {
        setStatus('open');
    });
    if (backBtn && wrapEl) {
        backBtn.addEventListener('click', function () {
            wrapEl.classList.remove('show-msg');
        });
    }

    // ---- Khởi động ----
    loadConversations();
    setInterval(loadConversations, LIST_REFRESH);
    setInterval(function () {
        if (state.activeId) {
            loadMessages(false);
        }
    }, POLL_INTERVAL);
})();
