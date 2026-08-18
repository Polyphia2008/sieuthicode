<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/chat.php';
if (!$user) { new Redirect('/login'); exit; }
chat_presence_touch((int) $data_user['id']);
$title = 'Danh sách người dùng | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$q = trim((string) ($_GET['q'] ?? ''));
$where = '`banned` = 0';
if ($q !== '') {
    $where .= " AND (`username` LIKE '%" . $db->escape($q) . "%' OR `name` LIKE '%" . $db->escape($q) . "%')";
}
$users = $db->get_list(
    "SELECT u.`id`,u.`username`,u.`name`,u.`level`,p.`last_seen` FROM `users` u"
    . " LEFT JOIN `chat_presence` p ON p.`user_id`=u.`id` WHERE " . $where
    . " ORDER BY (u.`level` IN ('admin','superadmin')) DESC,u.`username` ASC LIMIT 200"
);
?>
<link rel="stylesheet" href="/assets/css/chat-directory.css?v=<?= (int) (@filemtime(APP_ROOT.'/assets/css/chat-directory.css') ?: 1) ?>">
<section class="screen user-directory-screen"><div class="center">
  <div class="directory-heading"><h1>Danh sách người dùng của chúng tôi</h1><p>Tổng có <?= count($users) ?> người dùng</p></div>
  <form class="directory-search" method="get"><input name="q" value="<?= htmlspecialchars($q,ENT_QUOTES) ?>" placeholder="Tìm kiếm tên người dùng"><button aria-label="Tìm"><i class="fa fa-search"></i></button></form>
  <div class="directory-grid">
  <?php foreach ($users as $item):
    $display = trim((string)$item['name']) ?: (string)$item['username'];
    $online = chat_is_online($item['last_seen'] ?? null);
    $isStaff = in_array($item['level'], ['admin','superadmin'], true);
  ?>
    <article class="directory-card">
      <div class="directory-avatar"><img src="/assets/images/anhdaidien.svg" alt=""><span class="presence-dot <?= $online?'is-online':'' ?>"></span></div>
      <div class="directory-info"><h3><?= htmlspecialchars($display,ENT_QUOTES) ?><?= $isStaff?' <i class="fas fa-circle-check verified-user"></i>':'' ?></h3><p>@<?= htmlspecialchars($item['username'],ENT_QUOTES) ?></p><small><?= $online?'Đang hoạt động':'Không hoạt động' ?></small></div>
      <?php if ($isStaff): ?><a class="directory-chat" href="/chat-box/<?= rawurlencode($item['username']) ?>" title="Nhắn tin"><i class="far fa-comment-dots"></i></a><?php endif; ?>
    </article>
  <?php endforeach; ?>
  </div>
</div></section>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php'); ?>
