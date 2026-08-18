<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';
if (!$user) { new Redirect('/login'); exit; }
chat_presence_touch((int) $data_user['id']);
$title = 'Tin nhắn | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$chatCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/chat-box.css') ?: 1);
$directoryCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/chat-directory.css') ?: 1);
$targetUsername = trim((string) ($_GET['admin'] ?? ''));
?>
<link href="/assets/css/chat-box.css?v=<?= htmlspecialchars($chatCssVersion,ENT_QUOTES) ?>" rel="stylesheet">
<link href="/assets/css/chat-directory.css?v=<?= htmlspecialchars($directoryCssVersion,ENT_QUOTES) ?>" rel="stylesheet">
<?php if ($targetUsername === ''): ?>
<section class="screen chat-directory-screen"><div class="center"><div class="chat-directory-card">
  <div class="chat-directory-header">
    <div><h2><i class="fas fa-message"></i> Danh sách tin nhắn</h2><a href="/users" class="find-users"><i class="fas fa-user-plus"></i> Tìm bạn bè</a></div>
    <div class="current-chat-user"><img src="/assets/images/anhdaidien.svg" alt=""><span><?= htmlspecialchars($data_user['username'],ENT_QUOTES) ?></span><i class="presence-dot is-online"></i></div>
  </div>
  <div class="chat-directory-search"><i class="fas fa-search"></i><input id="chat-contact-search" placeholder="Tìm kiếm cuộc trò chuyện..."></div>
  <div id="chat-contact-list" class="chat-contact-list"><div class="chat-contact-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div></div>
</div></div></section>
<script src="/assets/js/chat-contacts.js?v=<?= (int)(@filemtime(APP_ROOT.'/assets/js/chat-contacts.js')?:1) ?>"></script>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php'); return; endif;
$target = $db->get_row(
    "SELECT u.`id`,u.`username`,u.`name`,u.`level`,p.`last_seen` FROM `users` u"
    . " LEFT JOIN `chat_presence` p ON p.`user_id`=u.`id`"
    . " WHERE u.`username`='".$db->escape($targetUsername)."' AND u.`level` IN ('admin','superadmin') AND u.`banned`=0 LIMIT 1"
);
if (!$target) { echo '<section class="screen"><div class="center"><div class="alert alert-warning">Quản trị viên không tồn tại.</div></div></section>'; require realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php'); return; }
$targetName = trim((string)$target['name']) ?: (string)$target['username'];
$targetOnline = chat_is_online($target['last_seen'] ?? null);
$chatJsVersion = (string) (@filemtime(APP_ROOT . '/assets/js/chat-box.js') ?: 1);
?>
<section class="screen chat-screen"><div class="center"><div class="chat-wrap">
  <aside class="chat-side" id="chat-side"><div class="chat-side-head"><img class="chat-side-avatar" src="/assets/images/anhdaidien.svg" alt=""><div class="chat-side-title"><h3><?= htmlspecialchars($targetName,ENT_QUOTES) ?></h3><p class="chat-side-status"><span class="chat-dot <?= $targetOnline?'':'is-offline' ?>"></span> <?= $targetOnline?'Đang hoạt động':'Không hoạt động' ?></p></div></div><div class="chat-side-body"><p class="chat-side-desc">Bạn đang trò chuyện trực tiếp với <strong><?= htmlspecialchars($targetName,ENT_QUOTES) ?></strong>.</p><a class="chat-side-faq" href="/chat-box"><i class="fas fa-list"></i> Danh sách tin nhắn</a></div></aside>
  <div class="chat-main" id="chat-main" data-admin-id="<?= (int)$target['id'] ?>">
    <div class="chat-header"><a class="chat-back" id="chat-back" href="/chat-box" aria-label="Quay lại"><i class="fas fa-arrow-left"></i></a><img class="chat-header-avatar" src="/assets/images/anhdaidien.svg" alt=""><div class="chat-header-info"><h3><?= htmlspecialchars($targetName,ENT_QUOTES) ?> <i class="fas fa-circle-check chat-verified"></i></h3><p id="chat-status-text" data-presence-label="<?= $targetOnline?'Đang hoạt động':'Không hoạt động' ?>"><?= $targetOnline?'Đang hoạt động':'Không hoạt động' ?></p></div><button type="button" class="chat-delete-history" id="chat-delete-history" title="Xóa lịch sử"><i class="far fa-trash-can"></i><span>Xóa lịch sử</span></button></div>
    <div class="chat-messages" id="chat-messages"><div class="chat-loading" id="chat-loading"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div><div class="chat-empty" id="chat-empty" style="display:none"><i class="far fa-comments"></i><p>Chưa có tin nhắn. Hãy bắt đầu cuộc trò chuyện!</p></div><div class="chat-error" id="chat-error" style="display:none"><p>Không tải được tin nhắn. <a href="javascript:void(0)" id="chat-retry">Thử lại</a></p></div><ul class="chat-list" id="chat-list"></ul></div>
    <div class="chat-closed" id="chat-closed" style="display:none"><p><i class="fas fa-lock"></i> Hội thoại đã đóng.</p><button type="button" class="chat-reopen-btn" id="chat-reopen">Mở lại</button></div>
    <form class="chat-composer" id="chat-composer" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(),ENT_QUOTES) ?>"><input type="hidden" name="admin_id" value="<?= (int)$target['id'] ?>"><input type="file" id="chat-attachment" accept="image/jpeg,image/png,image/gif,image/webp" hidden><button type="button" class="chat-attach-btn" id="chat-attach-btn"><i class="far fa-image"></i></button><div class="chat-input-wrap"><div class="chat-attachment-preview" id="chat-attachment-preview" style="display:none"><img id="chat-attachment-thumb" alt=""><button type="button" id="chat-attachment-remove">&times;</button></div><textarea id="chat-input" rows="1" maxlength="<?= CHAT_MAX_MESSAGE_LENGTH ?>" placeholder="Nhập tin nhắn..."></textarea></div><button type="submit" class="chat-send-btn" id="chat-send-btn"><i class="fas fa-paper-plane"></i></button></form>
  </div>
</div></div></section>
<script src="/assets/js/chat-box.js?v=<?= htmlspecialchars($chatJsVersion,ENT_QUOTES) ?>"></script>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php'); ?>
