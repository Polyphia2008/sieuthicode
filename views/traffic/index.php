<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) { new Redirect('/login'); exit; }
$title = 'Nhiệm vụ Traffic | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$userId = (int) $data_user['id'];
$tasks = $db->get_list(
    "SELECT t.*,(SELECT COUNT(*) FROM `traffic_attempts` a WHERE a.`task_id`=t.`id`"
    . " AND a.`user_id`='".$userId."' AND DATE(a.`created_at`)=CURDATE()"
    . " AND a.`status` IN ('issued','completed')) AS used_today"
    . " FROM `traffic_tasks` t WHERE t.`status`=1 ORDER BY t.`id` DESC"
);
$history = $db->get_list(
    "SELECT a.*,t.`title` FROM `traffic_attempts` a JOIN `traffic_tasks` t ON t.`id`=a.`task_id`"
    . " WHERE a.`user_id`='".$userId."' ORDER BY a.`id` DESC LIMIT 30"
);
$earningStats = $db->get_row(
    "SELECT COUNT(*) AS completed_total,COALESCE(SUM(`reward`),0) AS earned_total,"
    . "SUM(DATE(`completed_at`)=CURDATE()) AS completed_today FROM `traffic_attempts`"
    . " WHERE `user_id`='".$userId."' AND `status`='completed'"
) ?: [];
$statusLabels = [
    'issued' => ['Đang thực hiện','is-progress'],
    'completed' => ['Đã hoàn thành','is-completed'],
    'failed' => ['Khởi tạo thất bại','is-failed'],
    'expired' => ['Đã hết hạn','is-expired'],
];
?>
<link rel="stylesheet" href="/assets/css/traffic.css?v=<?= (int)(@filemtime(APP_ROOT.'/assets/css/traffic.css')?:1) ?>">
<nav class="earning-mobile-nav"><a href="/kiem-tien-online" class="active"><i class="fas fa-coins"></i> Nhiệm vụ</a><a href="#traffic-history"><i class="fas fa-clock-rotate-left"></i> Lịch sử</a><a href="/customer/balance"><i class="fas fa-wallet"></i> Số dư</a></nav>
<section class="screen traffic-page"><div class="center">
  <header class="traffic-banner">
    <div class="traffic-banner-copy"><span class="traffic-eyebrow"><i class="fas fa-bolt"></i> KIẾM TIỀN ONLINE</span><h1>Hoàn thành nhiệm vụ,<br><strong>nhận thưởng tự động</strong></h1><p>Hệ thống tạo phiên xác thực riêng và cộng tiền ngay khi bạn vượt link thành công.</p><a href="#available-tasks" class="traffic-primary-cta"><i class="fas fa-wand-magic-sparkles"></i> Bắt đầu nhận nhiệm vụ</a></div>
    <div class="traffic-banner-visual"><div class="traffic-coin coin-one">₫</div><div class="traffic-coin coin-two">₫</div><i class="fas fa-chart-line"></i></div>
  </header>

  <div class="traffic-stats">
    <article><span class="stat-icon is-blue"><i class="fas fa-list-check"></i></span><div><small>Nhiệm vụ đang mở</small><strong><?= count($tasks) ?></strong></div></article>
    <article><span class="stat-icon is-green"><i class="fas fa-circle-check"></i></span><div><small>Hoàn thành hôm nay</small><strong><?= (int)($earningStats['completed_today']??0) ?></strong></div></article>
    <article><span class="stat-icon is-orange"><i class="fas fa-sack-dollar"></i></span><div><small>Tổng thưởng đã nhận</small><strong><?= format_cash((int)($earningStats['earned_total']??0)) ?>đ</strong></div></article>
    <article><span class="stat-icon is-purple"><i class="fas fa-wallet"></i></span><div><small>Số dư hiện tại</small><strong><?= format_cash((int)$data_user['money']) ?>đ</strong></div></article>
  </div>

  <?php if(!empty($_SESSION['traffic_error'])):?><div class="traffic-alert is-error"><i class="fas fa-triangle-exclamation"></i><span><?=htmlspecialchars($_SESSION['traffic_error'],ENT_QUOTES);unset($_SESSION['traffic_error']);?></span></div><?php endif;?>
  <?php if(!empty($_SESSION['traffic_success'])):?><div class="traffic-alert is-success"><i class="fas fa-circle-check"></i><span><?=htmlspecialchars($_SESSION['traffic_success'],ENT_QUOTES);unset($_SESSION['traffic_success']);?></span></div><?php endif;?>

  <div class="traffic-content-grid">
    <main id="available-tasks">
      <div class="traffic-section-title"><div><span>NHIỆM VỤ KHẢ DỤNG</span><h2>Chọn nhiệm vụ để bắt đầu</h2></div><i class="fas fa-arrow-trend-up"></i></div>
      <div class="traffic-tasks">
      <?php if (!$tasks): ?>
        <div class="traffic-empty-state"><i class="fas fa-inbox"></i><h3>Hiện chưa có nhiệm vụ Traffic</h3><p>Vui lòng quay lại sau khi admin tạo nhiệm vụ mới.</p><?php if(is_admin_account($data_user)):?><a href="/cpanel/traffic" class="traffic-start">Mở trang quản lý</a><?php endif;?></div>
      <?php else: foreach($tasks as $task): $used=(int)($task['used_today']??0);$max=max(1,(int)$task['max_per_day']);$remaining=max(0,$max-$used);$percent=min(100,($used/$max)*100); ?>
        <article class="traffic-task-card">
          <div class="task-card-top"><span class="task-number">#<?= (int)$task['id'] ?></span><span class="task-reward"><i class="fas fa-coins"></i> +<?=format_cash($task['reward'])?>đ</span></div>
          <h3><?=htmlspecialchars($task['title'],ENT_QUOTES)?></h3>
          <p><i class="fas fa-shield-halved"></i> Xác thực tự động theo phiên trình duyệt và địa chỉ mạng</p>
          <div class="task-quota"><div><span>Lượt hôm nay</span><strong><?=$used?>/<?=$max?></strong></div><div class="quota-track"><i style="width:<?=$percent?>%"></i></div></div>
          <?php if($remaining<=0):?><button class="traffic-start is-disabled" disabled><i class="fas fa-ban"></i> Đã hết lượt hôm nay</button>
          <?php else:?><form class="traffic-start-form" method="post" action="/model/traffic/start"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="task_id" value="<?=$task['id']?>"><button class="traffic-start" type="submit"><i class="fas fa-play"></i> Nhận nhiệm vụ <small>Còn <?=$remaining?> lượt</small></button></form><?php endif;?>
        </article>
      <?php endforeach; endif; ?>
      </div>
    </main>
    <aside class="traffic-howto"><h3><i class="fas fa-route"></i> Quy trình xác thực</h3><ol><li><b>1</b><div><strong>Nhận nhiệm vụ</strong><span>Tạo mã phiên dùng một lần</span></div></li><li><b>2</b><div><strong>Vượt link</strong><span>Hoàn thành yêu cầu của provider</span></div></li><li><b>3</b><div><strong>Quay về hệ thống</strong><span>Kiểm tra tài khoản, phiên, thiết bị và IP</span></div></li><li><b>4</b><div><strong>Nhận thưởng</strong><span>Cộng tiền tự động vào số dư</span></div></li></ol><div class="traffic-security-note"><i class="fas fa-lock"></i><p>Dùng cùng trình duyệt và mạng trong suốt quá trình. Không chia sẻ link hoặc đổi VPN.</p></div></aside>
  </div>

  <section id="traffic-history" class="traffic-history"><div class="traffic-section-title"><div><span>HOẠT ĐỘNG GẦN ĐÂY</span><h2>Lịch sử nhiệm vụ</h2></div><i class="fas fa-clock-rotate-left"></i></div><div class="traffic-table-wrap"><table><thead><tr><th>Nhiệm vụ</th><th>Thời gian</th><th>Trạng thái</th><th>Thưởng</th></tr></thead><tbody><?php if(!$history):?><tr><td colspan="4" class="traffic-history-empty">Bạn chưa tham gia nhiệm vụ nào.</td></tr><?php else:foreach($history as $h):$status=$statusLabels[$h['status']]??['Không xác định','is-unknown'];?><tr><td><strong><?=htmlspecialchars($h['title'],ENT_QUOTES)?></strong><small>#<?= (int)$h['id'] ?></small></td><td><?=date('d/m/Y H:i',strtotime($h['created_at']))?></td><td><span class="traffic-status <?=$status[1]?>"><?=$status[0]?></span></td><td class="history-reward">+<?=format_cash($h['reward'])?>đ</td></tr><?php endforeach;endif;?></tbody></table></div></section>
</div></section>

<div class="traffic-loading-modal" id="traffic-loading-modal" aria-hidden="true"><div class="loading-card"><div class="loading-spinner"><i class="fas fa-link"></i></div><h3>Đang tạo nhiệm vụ...</h3><p>Hệ thống đang tạo phiên xác thực và lấy link từ nhà cung cấp. Vui lòng không đóng trang.</p><div class="loading-progress"><i></i></div></div></div>
<script>
document.querySelectorAll('.traffic-start-form').forEach(function(form){form.addEventListener('submit',function(){var modal=document.getElementById('traffic-loading-modal');modal.classList.add('is-visible');modal.setAttribute('aria-hidden','false');var btn=form.querySelector('button');btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';});});
<?php if(($_GET['traffic']??'')==='success'):?>document.addEventListener('DOMContentLoaded',function(){if(typeof alertSuccess==='function')alertSuccess('Hoàn thành nhiệm vụ','Tiền thưởng đã được cộng vào số dư');history.replaceState({},document.title,'/kiem-tien-online');});<?php endif;?>
</script>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php');?>
