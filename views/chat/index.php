<?php
/**
 * Trang /chat-box — khung chat hỗ trợ khách hàng (thành viên).
 * Layout tham khảo hành vi các trang chat phổ biến: panel trái thông tin CSKH,
 * panel phải tin nhắn. Dùng branding/màu của project hiện tại.
 */

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';

if (!$user) {
    new Redirect('/login');
    exit;
}

$title = 'Chat hỗ trợ | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');

$chatCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/chat-box.css') ?: 1);
$chatJsVersion = (string) (@filemtime(APP_ROOT . '/assets/js/chat-box.js') ?: 1);
$supportName = 'Hỗ trợ ' . $db->site('title');
?>
<link href="/assets/css/chat-box.css?v=<?= htmlspecialchars($chatCssVersion, ENT_QUOTES) ?>" rel="stylesheet">

<section class="screen chat-screen">
    <div class="center">
        <div class="chat-wrap">
            <!-- Panel trái: thông tin kênh hỗ trợ -->
            <aside class="chat-side" id="chat-side">
                <div class="chat-side-head">
                    <img class="chat-side-avatar" src="/assets/images/anhdaidien.svg" alt="CSKH">
                    <div class="chat-side-title">
                        <h3><?= htmlspecialchars($supportName, ENT_QUOTES) ?></h3>
                        <p class="chat-side-status"><span class="chat-dot"></span> Hỗ trợ trực tuyến</p>
                    </div>
                </div>
                <div class="chat-side-body">
                    <p class="chat-side-desc">
                        Chào mừng bạn đến với kênh chăm sóc khách hàng của
                        <strong><?= htmlspecialchars($db->site('title'), ENT_QUOTES) ?></strong>.
                        Nếu gặp khó khăn trong quá trình sử dụng, hãy nhắn tin cho chúng tôi —
                        đội ngũ hỗ trợ sẽ phản hồi sớm nhất có thể.
                    </p>
                    <ul class="chat-side-meta">
                        <li><i class="far fa-clock"></i> Thời gian làm việc: <?= htmlspecialchars((string) $db->site('time_support'), ENT_QUOTES) ?></li>
                        <li><i class="fas fa-headset"></i> Hotline: <?= htmlspecialchars((string) $db->site('hotline'), ENT_QUOTES) ?></li>
                    </ul>
                    <a class="chat-side-faq" href="/faq"><i class="far fa-circle-question"></i> Câu hỏi thường gặp (FAQs)</a>
                </div>
            </aside>

            <!-- Panel phải: vùng tin nhắn -->
            <div class="chat-main" id="chat-main">
                <div class="chat-header">
                    <button type="button" class="chat-back" id="chat-back" aria-label="Quay lại">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <img class="chat-header-avatar" src="/assets/images/anhdaidien.svg" alt="CSKH">
                    <div class="chat-header-info">
                        <h3><?= htmlspecialchars($supportName, ENT_QUOTES) ?> <i class="fas fa-circle-check chat-verified"></i></h3>
                        <p id="chat-status-text">Đang kết nối...</p>
                    </div>
                </div>

                <div class="chat-messages" id="chat-messages">
                    <div class="chat-loading" id="chat-loading">
                        <i class="fas fa-spinner fa-spin"></i> Đang tải tin nhắn...
                    </div>
                    <div class="chat-empty" id="chat-empty" style="display:none">
                        <i class="far fa-comments"></i>
                        <p>Chưa có tin nhắn nào. Hãy gửi tin nhắn đầu tiên để được hỗ trợ!</p>
                    </div>
                    <div class="chat-error" id="chat-error" style="display:none">
                        <i class="fas fa-triangle-exclamation"></i>
                        <p>Không tải được tin nhắn. <a href="javascript:void(0)" id="chat-retry">Thử lại</a></p>
                    </div>
                    <ul class="chat-list" id="chat-list"></ul>
                </div>

                <div class="chat-closed" id="chat-closed" style="display:none">
                    <p><i class="fas fa-lock"></i> Hội thoại đã được quản trị viên đóng.</p>
                    <button type="button" class="chat-reopen-btn" id="chat-reopen">Mở lại hội thoại</button>
                </div>

                <form class="chat-composer" id="chat-composer" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES) ?>">
                    <input type="file" id="chat-attachment" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp" hidden>
                    <button type="button" class="chat-attach-btn" id="chat-attach-btn" aria-label="Đính kèm ảnh">
                        <i class="far fa-image"></i>
                    </button>
                    <div class="chat-input-wrap">
                        <div class="chat-attachment-preview" id="chat-attachment-preview" style="display:none">
                            <img id="chat-attachment-thumb" alt="Ảnh đính kèm">
                            <button type="button" id="chat-attachment-remove" aria-label="Bỏ ảnh">&times;</button>
                        </div>
                        <textarea id="chat-input" name="message" rows="1" maxlength="<?= CHAT_MAX_MESSAGE_LENGTH ?>" placeholder="Nhập tin nhắn..."></textarea>
                    </div>
                    <button type="submit" class="chat-send-btn" id="chat-send-btn" aria-label="Gửi tin nhắn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/chat-box.js?v=<?= htmlspecialchars($chatJsVersion, ENT_QUOTES) ?>"></script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
