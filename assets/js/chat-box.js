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
    var deleteHistoryBtn = document.getElementById('chat-delete-history');
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
        if (msg.sender_role === 'user') {
            var delivery = document.createElement('div');
            delivery.className = 'chat-delivery-status';
            delivery.textContent = msg.is_read ? 'Đã xem' : 'Đã gửi';
            wrap.appendChild(delivery);
        }
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

    function applyReadStatus(lastReadUserMessageId) {
        var readUpTo = parseInt(lastReadUserMessageId, 10) || 0;
        listEl.querySelectorAll('.chat-item.is-user[data-id]').forEach(function (item) {
            var status = item.querySelector('.chat-delivery-status');
            if (!status) {
                return;
            }
            var messageId = parseInt(item.dataset.id, 10) || 0;
            status.textContent = readUpTo > 0 && messageId <= readUpTo ? 'Đã xem' : 'Đã gửi';
            status.classList.toggle('is-read', readUpTo > 0 && messageId <= readUpTo);
        });
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
                applyReadStatus(conv ? conv.last_read_user_message_id : 0);
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
                applyReadStatus(json.data.conversation.last_read_user_message_id);
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

    if (deleteHistoryBtn) {
        deleteHistoryBtn.addEventListener('click', function () {
            if (!window.confirm('Xóa toàn bộ lịch sử trò chuyện? Thao tác này không thể hoàn tác.')) {
                return;
            }
            deleteHistoryBtn.disabled = true;
            var fd = new FormData();
            fd.append('csrf_token', csrfToken);
            apiPost('/model/chat/delete', fd)
                .then(function (json) {
                    if (json.status !== 'success') {
                        alert(json.msg || 'Không thể xóa lịch sử trò chuyện');
                        return;
                    }
                    listEl.innerHTML = '';
                    lastId = 0;
                    clearAttachment();
                    inputEl.value = '';
                    autoGrowInput();
                    applyConversationStatus('open');
                    setState('empty');
                })
                .catch(function () {
                    alert('Lỗi kết nối, vui lòng thử lại');
                })
                .finally(function () {
                    deleteHistoryBtn.disabled = false;
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

    // ---- Mobile layout: đo thực tế, MỘT nguồn chiều cao duy nhất ----
    // Không dùng offset cố định chưa đo. JS đo và set trực tiếp chiều cao khả dụng
    // cho .chat-wrap (inline style) theo công thức:
    //   height = (visualViewport.height + visualViewport.offsetTop)
    //            - top thực tế của chat-wrap (đã gồm header đang hiển thị)
    //            - chiều cao bottom nav đang visible (.plugbar / .footer-mobile)
    // visualViewport đã tự co theo bàn phím -> KHÔNG trừ keyboard gap lần nữa
    // (tránh trừ hai lần như bản 100dvh + vv-offset cũ). Desktop không ảnh hưởng.
    var MOBILE_MQ = window.matchMedia('(max-width: 991.98px)');

    function firstVisibleElement(ids) {
        for (var i = 0; i < ids.length; i++) {
            var el = document.getElementById(ids[i]);
            if (!el) {
                continue;
            }
            var cs = window.getComputedStyle(el);
            if (cs.display === 'none' || cs.visibility === 'hidden') {
                continue;
            }
            var rect = el.getBoundingClientRect();
            if (rect.height > 0) {
                return el;
            }
        }
        return null;
    }

    // Top thực tế của khung chat — đã bao gồm mọi header đang hiển thị phía trên.
    // Fallback (hiếm): đo chính header đang hiển thị (#menu desktop / #menu-mobile mobile).
    function measureChatTop() {
        var top = wrapEl ? wrapEl.getBoundingClientRect().top : 0;
        if (!(top > 0)) {
            var header = firstVisibleElement(['menu', 'menu-mobile']);
            top = header ? header.getBoundingClientRect().bottom : 0;
        }
        return Math.max(0, top);
    }

    // Chiều cao bottom nav ĐANG VISIBLE và bám đáy viewport hiện tại.
    function measureBottomNavHeight(viewportBottom) {
        var navH = 0;
        var candidates = document.querySelectorAll('.plugbar, .footer-mobile');
        for (var i = 0; i < candidates.length; i++) {
            var el = candidates[i];
            var cs = window.getComputedStyle(el);
            if (cs.display === 'none' || cs.visibility === 'hidden') {
                continue;
            }
            if (cs.position !== 'fixed' && cs.position !== 'sticky') {
                continue;
            }
            var rect = el.getBoundingClientRect();
            if (rect.height <= 0) {
                continue;
            }
            // Chỉ tính nav thực sự bám đáy vùng nhìn thấy; lấy phần chồng lấn lớn nhất.
            var overlap = viewportBottom - Math.max(rect.top, 0);
            if (rect.bottom >= viewportBottom - 2 && overlap > 0) {
                navH = Math.max(navH, Math.min(overlap, rect.height));
            }
        }
        return navH;
    }

    function updateChatHeight() {
        if (!wrapEl) {
            return;
        }
        if (!MOBILE_MQ.matches) {
            // Desktop: bỏ inline height, layout CSS gốc quyết định — không ảnh hưởng.
            wrapEl.style.height = '';
            wrapEl.style.minHeight = '';
            return;
        }
        var vv = window.visualViewport;
        // Nguồn chiều cao duy nhất: visualViewport (đã co theo bàn phím) khi có,
        // ngược lại window.innerHeight. offsetTop bù phần viewport bị đẩy khi scroll/zoom.
        var viewportBottom = vv ? (vv.height + vv.offsetTop) : window.innerHeight;
        var top = measureChatTop();
        var navH = measureBottomNavHeight(viewportBottom);
        var avail = Math.max(240, Math.round(viewportBottom - top - navH));
        wrapEl.style.height = avail + 'px';
        wrapEl.style.minHeight = avail + 'px';
        // Giữ composer + tin mới nhất trong tầm nhìn (nếu đang gần đáy).
        scrollToBottom(false);
    }

    updateChatHeight();
    // Script này được chèn TRƯỚC footer (nơi render .plugbar/.footer-mobile),
    // nên lần đo đầu có thể chưa thấy bottom nav -> đo lại khi DOM parse xong
    // và khi trang load hoàn tất để trừ đúng chiều cao nav (tránh chồng lấn).
    document.addEventListener('DOMContentLoaded', updateChatHeight);
    window.addEventListener('load', updateChatHeight);
    window.addEventListener('resize', updateChatHeight);
    window.addEventListener('orientationchange', function () {
        // chờ orientation settle rồi đo lại
        setTimeout(updateChatHeight, 150);
    });
    // Trình duyệt mobile có thể auto-scroll document khi focus ô nhập;
    // đo lại theo vị trí scroll thực tế để wrap không bị top âm / thừa chiều cao.
    window.addEventListener('scroll', updateChatHeight, { passive: true });
    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', updateChatHeight);
        window.visualViewport.addEventListener('scroll', updateChatHeight);
    }
    // Căn lại ngay khi bàn phím ảo mở/đóng quanh ô nhập trên mobile.
    inputEl.addEventListener('focus', function () {
        setTimeout(updateChatHeight, 300); // chờ bàn phím animate xong
    });
    inputEl.addEventListener('blur', updateChatHeight);

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
