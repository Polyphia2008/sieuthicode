<?php
/**
 * Trang /cpanel/chat-box — hòm thư hỗ trợ khách hàng dành cho admin.
 * Layout 2 panel dùng class Dashmix đang có của admin.
 */

$title = 'Chat hỗ trợ';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/chat.php';

$adminChatCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/admin-chat-box.css') ?: 1);
$adminChatJsVersion = (string) (@filemtime(APP_ROOT . '/assets/js/admin-chat-box.js') ?: 1);
?>
<link href="/assets/css/admin-chat-box.css?v=<?= htmlspecialchars($adminChatCssVersion, ENT_QUOTES) ?>" rel="stylesheet">

<main id="main-container">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-3">
            <h3 class="mb-1">Chat hỗ trợ khách hàng</h3>
            <span class="badge bg-danger rounded-pill" id="admin-chat-total-unread" style="display:none">0</span>
        </div>

        <div class="admin-chat-wrap">
            <!-- Danh sách hội thoại -->
            <div class="block block-rounded admin-chat-list-panel" id="admin-chat-list-panel">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Hội thoại</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" id="admin-chat-search" placeholder="Tìm username...">
                        </div>
                        <div class="col-12">
                            <select class="form-select form-select-sm" id="admin-chat-filter-status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="open">Đang mở</option>
                                <option value="closed">Đã đóng</option>
                            </select>
                        </div>
                    </div>
                    <div id="admin-chat-conversations" class="admin-chat-conversations">
                        <div class="text-muted text-center py-4" id="admin-chat-list-loading">
                            <i class="fa fa-spinner fa-spin"></i> Đang tải...
                        </div>
                    </div>
                    <nav id="admin-chat-pagination" class="mt-2"></nav>
                </div>
            </div>

            <!-- Panel tin nhắn -->
            <div class="block block-rounded admin-chat-msg-panel" id="admin-chat-msg-panel">
                <div class="block-header block-header-default">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" id="admin-chat-back">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    <h3 class="block-title" id="admin-chat-title">Chọn một hội thoại</h3>
                    <div class="block-options" id="admin-chat-status-actions" style="display:none">
                        <button type="button" class="btn btn-sm btn-alt-danger" id="admin-chat-close-btn">
                            <i class="fa fa-lock"></i> Đóng
                        </button>
                        <button type="button" class="btn btn-sm btn-alt-success" id="admin-chat-open-btn" style="display:none">
                            <i class="fa fa-unlock"></i> Mở lại
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full d-flex flex-column admin-chat-msg-body">
                    <div class="admin-chat-messages" id="admin-chat-messages">
                        <div class="text-muted text-center py-5" id="admin-chat-placeholder">
                            <i class="far fa-comments fa-2x mb-2"></i>
                            <p class="mb-0">Chọn một hội thoại bên trái để xem tin nhắn.</p>
                        </div>
                        <ul class="chat-list" id="admin-chat-list"></ul>
                    </div>

                    <form class="chat-composer" id="admin-chat-composer" style="display:none" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES) ?>">
                        <input type="file" id="admin-chat-attachment" name="attachment" accept="image/jpeg,image/png,image/gif,image/webp" hidden>
                        <button type="button" class="chat-attach-btn" id="admin-chat-attach-btn" aria-label="Đính kèm ảnh">
                            <i class="far fa-image"></i>
                        </button>
                        <div class="chat-input-wrap">
                            <div class="chat-attachment-preview" id="admin-chat-attachment-preview" style="display:none">
                                <img id="admin-chat-attachment-thumb" alt="Ảnh đính kèm">
                                <button type="button" id="admin-chat-attachment-remove" aria-label="Bỏ ảnh">&times;</button>
                            </div>
                            <textarea id="admin-chat-input" name="message" rows="1" maxlength="<?= CHAT_MAX_MESSAGE_LENGTH ?>" placeholder="Nhập phản hồi..."></textarea>
                        </div>
                        <button type="submit" class="chat-send-btn" id="admin-chat-send-btn" aria-label="Gửi">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="/assets/js/admin-chat-box.js?v=<?= htmlspecialchars($adminChatJsVersion, ENT_QUOTES) ?>"></script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
